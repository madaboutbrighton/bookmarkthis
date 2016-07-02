<?php

/**
 * BookmarkThis
 *
 * Copyright 2016 by Mad About Brighton <mail@madaboutbrighton.net>
 * 
 * BookmarkThis is free software; you can copy, distribute, transmit and adapt it
 * under the terms of the Creative Commons attribution-ShareAlike 3.0 Unported License.
 * 
 * You must attribute BookmarkThis to Mad About Brighton. If you alter, transform, or build upon
 * BookmarkThis, you must distribute the resulting work only under the same or similar license to this one.
 *
 * BookmarkThis is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the Creative Commons attribution-ShareAlike 3.0 Unported License for more details.
 *
 * You should have received a copy of the license. If not, it can be viewed by visiting 
 * http://madaboutbrighton.net/projects/bookmarkthis
 *
 */

/**
 * This file is the main class file for BookmarkThis
 *
 * @copyright Copyright 2016 by Mad About Brighton <mail@madaboutbrighton.net>
 * @author west <west@madaboutbrighton.net>
 * @licence https://creativecommons.org/licenses/by-sa/3.0/
 * @package bookmarkthis
 */

class BookmarkThis
{
  /** @var modX|null A reference to the modX object */
  private $modx = null;
  
  /** @var array A collection of properties to adjust BookmarkThis behaviour */
  private $config = array();
  
  /** @var string|null The mode in which the items shall be retrieved - 'share' or 'follow' */
  private $mode = null;
  
  /** @var boolean|false Whether debugging is turned on */
  private $debug = false;
  
  /** @var string Relative path to the BookmarkThis cache in the modX cacheManager */
  private $cache = 'web/bookmarkthis/';
  
  /** @var string|null Encoded string that is used as a key for the modX cacheManager */
  private $cacheKey = null;
  
  /**
  * The BookmarkThis Constructor.
  *
  * Creates a new BookmarkThis object.
  *
  * @param modX &$modx A reference to the modX object.
  * @param array $config A collection of properties that modify BookmarkThis behaviour.
  * @return BookmarkThis A unique BookmarkThis instance.
  */
  public function __construct(modX &$modx, array $config = array())
  {
    $this->modx =& $modx;
    
    // allows you to set paths in different environments
    $basePath = $this->modx->getOption('bookmarkthis.core_path', $config, $this->modx->getOption('core_path').'components/bookmarkthis/');
    $assetsUrl = $this->modx->getOption('bookmarkthis.assets_url', $config, $this->modx->getOption('assets_url').'components/bookmarkthis/');
    
    $this->config = array_merge(array(
        'basePath' => $basePath,
        'corePath' => $basePath,
        'modelPath' => $basePath.'model/',
        'processorsPath' => $basePath.'processors/',
        'templatesPath' => $basePath.'templates/',
        'chunksPath' => $basePath.'elements/chunks/',
        'jsUrl' => $assetsUrl.'js/',
        'cssUrl' => $assetsUrl.'css/',
        'assetsUrl' => $assetsUrl,
        'connectorUrl' => $assetsUrl.'connector.php',
        ),$config);
        
  }
  
  /**
  * Public entry point to obtain follow-me links
  *
  * @param array $param A collection of properties that modify BookmarkThis behaviour.
  * @return string A set of follow-me icon links
  */
  public function getFollow($param)
  {
    $this->mode = 'follow';
    
    return $this->getItems($param);
  }

  /**
  * Public entry point to obtain share bookmarklets
  *
  * @param array $param A collection of properties that modify BookmarkThis behaviour.
  * @return string A set of share bookmarklets
  */
  public function getShare($param)
  {
    $this->mode = 'share';
    
    return $this->getItems($param);
  }

  /**
  * Returns a set of items using a JSON data configuration chunk
  *
  * @param array $param A collection of properties that modify BookmarkThis behaviour.
  * @return string A set of items
  */
  private function getItems($param)
  {
    $this->debug = true;
    
    $this->filesInclude(!empty($param['appendCSS']), !empty($param['appendJS']));
    
    $this->cacheKey = $this->getKey($param);
    
    if ($s = $this->cacheRead()) return $s;
        
    $this->setup($param);
    
    unset($param);
        
    if ($data = $this->getData())
    {
      $extra = array('customFields' => $this->getCustomFields());
      
      foreach ($data as $k => $v)
      {        
        //if ($this->debug) $this->log($this->dumpArray($v));
        
        if (!empty($v[$this->mode]))
        {
          $s.= $this->getChunk($this->config['tpl'], array_merge($v, $extra));
        }
      }
      
      if (!empty($this->config['tplWrapper'])) $s = $this->getChunk($this->config['tplWrapper'], array('items' => $s));
      
      $s = $this->getMe($this->config['me']) . $s;
      
      $s = $this->fillPlaceholders($s);
      
      $this->cacheWrite($s);
      
      return $s;
    }
  }

  /**
  * Obtains any custom fields, used by bookmarkthis.js
  *
  * @return string The custom fields
  */
  private function getCustomFields()
  {
    if (!empty($this->config['customFields']))
    {
      if ($data = json_decode($this->config['customFields']))
      {
        $a = array();
        
        foreach ($data as $k => $v)
        {
          $a[] = 'data-' . $k . '-query="' . $v['query'] . '"';
          $a[] = 'data-' . $k . '-attribute="' . $v['attribute'] . '"';
        }
        
        return implode(' ', $a);
      }
    }
  }
  
  /**
  * Sets the default configuration properties and merges them into $this->config
  *
  * @param array $param A collection of properties that modify BookmarkThis behaviour.
  * @return null
  */
  private function setup($param)
  {
    $this->debug = $this->getDebug($this->modx->getOption('debug', $param, false));
    
    $param['data'] = $this->modx->getOption('data', $param, 'bookmarkThisData');
    $param['appendCSS'] = $this->modx->getOption('appendCSS', $param, true);
    $param['size'] = $this->modx->getOption('size', $param, '36');
    $param['type'] = $this->modx->getOption('type', $param, 'rounded');

    switch ($this->mode)
    {
      case 'share':
      
        $param['me'] = 'Social bookmarks';
        $param['appendJS'] = $this->modx->getOption('appendJS', $param, true);
        $param['tpl'] = $this->modx->getOption('tpl', $param, 'bookmarkThisShare');
        $param['tplWrapper'] = $this->modx->getOption('tplWrapper', $param, 'bookmarkThisShareWrapper');
        
        break;
        
      case 'follow':

        $param['me'] = 'Follow-me links';
        $param['tpl'] = $this->modx->getOption('tpl', $param, 'bookmarkThisFollow');
        $param['tplWrapper'] = $this->modx->getOption('tplWrapper', $param, 'bookmarkThisFollowWrapper');
        
        break;
    }
    
    $this->config = array_merge($this->config, $param);
  }
  
  /**
  * If required, appends BookmarkThis JS and CSS to resource output
  *
  * @param boolean $includeCSS Whether to include the CSS file
  * @param boolean $includeJS Whether to include the JS file
  * @return null
  */
  private function filesInclude($includeCSS, $includeJS)
  { 
    if ($includeCSS) $this->modx->regClientCSS($this->config['cssUrl'] . 'bookmarkthis-min.css');
    
    if ($includeJS) $this->modx->regClientScript($this->config['jsUrl'] . 'bookmarkthis-min.js');
  }
  
  /**
  * Performs find and replace to populate placeholders
  *
  * @param string $s The string to be searched
  * @return string The processed string
  */
	private function fillPlaceholders($s)
	{
    $search[] = '{size}';
    $search[] = '{type}';
    $search[] = '{bookmarkthis_assets_url}';
    $search[] = '{assets_url}';

    $replace[] = $this->config['size'];
    $replace[] = $this->config['type'];
    $replace[] = $this->config['assetsUrl'];
    $replace[] = MODX_ASSETS_URL;

    return str_replace( $search, $replace, $s );
	}

  /**
  * Returns the BookmarkThis HTML comment prefix
  *
  * @param string $s The name of the sub-component
  * @return string The BookmarkThis HTML comment prefix
  */
  private function getMe($s)
  {
    return '<!-- ' . $s . ' generated by BookmarkThis @ madaboutbrighton.net -->' . "\r\n";
  }
  
  /**
  * Decodes the JSON data file chunk into an array, returning only the items requested and leaving out those to be excluded
  *
  * @return array Associative array of data items
  */
	private function getData()
	{
		if ($s = $this->getChunk($this->config['data']))
		{
      if ($bmks = json_decode($s, true))
      {
        $this->log('Found ' . count($bmks) . ' items');
        
        if (!empty($this->config['use']))
        {
          $bmks = $this->itemsUse($bmks, explode(',', $this->config['use']));
        }

        if (!empty($this->config['exclude']))
        {
          $bmks = $this->itemsExclude($bmks, explode(',', $this->config['exclude']));
        }
      
        $this->log('Using ' . count($bmks) . ' items');
      
        return $bmks;
        
      }
    }
	}

  /**
  * Returns only the items specified
  *
  * @param array $bmks An index array of bookmark items
  * @param array $use An array of items to use/keep
  * @return array An array of the kept items, in the same order as the $use array
  */
  private function itemsUse($bmks, $use)
  {
    $a = array();

    foreach ($use as $v)
    {
      $v = trim($v);
      
      if (array_key_exists ($v, $bmks))
      {
        $a[$v] = $bmks[$v];
      }
    }
    
    return $a;
  }  
  
  /**
  * Returns array without the items specified
  *
  * @param array $bmks An index array of bookmark items
  * @param array $exclude An array of items to exclude
  * @return array An array of the original items without the excluded ones
  */
  private function itemsExclude($bmks, $exclude)
  {
    foreach ($exclude as $v)
    {
      $v = trim($v);

      unset($bmks[$v]);
    }
    
    return $bmks;
  }  

  /********** HELPER CLASSES **********/
     
  /**
  * Returns a dump of an array, for debugging purposes.
  *
  * @param array $a An array to be dumped
  * @param string $append String to be appended to each row
  * @return string The array transformed into a string
  */
  private function dumpArray($a, $append = "\n")
  {
    if (is_array($a))
    {
      foreach ($a as $k => $v)
      {
        $s.= $k . ' = ' . $v . $append;
      }
      
      return $s . $append . $append;
    }
  }
  
  /**
  * Transforms an array into an encoded string that is used as a key for the modX cacheManager
  *
  * @param array $a Any array to be transformed into a key
  * @return string A sha1 encoded string
  */
  private function getKey($param)
  {
    if (is_array($param))
    {
        $s = implode('', $param);

      } else {

        $s = get_class($this);
    }

    return sha1($s);
  }

  /**
  * Writes a string to the modX cacheManager
  *
  * @param string $s The string to be stored
  */
  private function cacheWrite($s)
  {
    $this->modx->cacheManager->set($this->cache . $this->cacheKey, $s);
  }

  /**
  * Reads a string from the modX cacheManager
  *
  * @return string The string that was stored against the key
  */
  private function cacheRead()
  {
    $s = $this->modx->cacheManager->get($this->cache . $this->cacheKey);

    if (!empty($s)) { return $s; }
  }
  
  /**
  * Calculate whether debug reporting should be on or off
  *
  * @param mixed $level The debug level passed in
  * @return boolean Whether debug reporting should be on or off
  */
  private function getDebug($level = false)
  {
    return ($level > 0 || $this->modx->getOption('debug') > 0);
  }
  
  /**
  * Log an entry if debug reporting is on
  *
  * @param string $s The debug message
  * @param integer $level The debug level modX::[LOG_LEVEL_FATAL, LOG_LEVEL_ERROR, LOG_LEVEL_WARN, LOG_LEVEL_INFO, LOG_LEVEL_DEBUG]
  * @return boolean Whether debug reporting should be on or off
  */
  private function log($s, $level = modX::LOG_LEVEL_ERROR)
  {
    if ($this->debug)
    {
      $this->modx->log($level, '[' . get_class($this) . '] ' . $s);
    }
  }

  /**
  * Processes a chunk. Attempts object first, then file based if not found
  *
  * @param string $name The name of the chunk
  * @param array $properties The settings for the chunk
  * @return string The content of the processed chunk
  */
  public function getChunk($name, $properties = array())
  {
      $chunk = null;
      
      if (!isset($this->chunks[$name]))
      {
          $chunk = $this->modx->getObject('modChunk', array('name' => $name));
          
          if (empty($chunk) || !is_object($chunk))
          {
            $chunk = $this->_getTplChunk($name);
            if ($chunk == false) return false;
          }
          
          $this->chunks[$name] = $chunk->getContent();
            
        } else {
          
          $o = $this->chunks[$name];
          $chunk = $this->modx->newObject('modChunk');
          $chunk->setContent($o);
      }
      
      $chunk->setCacheable(false);
      
      return $chunk->process($properties);
  }
  
  /**
  * Get the contents of a file based chunk
  *
  * @param string $name The name of the chunk
  * @param string $postfix The extension of the file based chunk
  * @return string The content of the file based chunk
  */
  private function _getTplChunk($name)
  {
      $chunk = false;
      
      $f = $this->config['chunksPath'] . 'chunk.' . strtolower($name) . '.tpl';
      
      if (file_exists($f))
      {
        $o = file_get_contents($f);
        $chunk = $this->modx->newObject('modChunk');
        $chunk->set('name',$name);
        $chunk->setContent($o);
      }
      
      return $chunk;
  }    
}

?>