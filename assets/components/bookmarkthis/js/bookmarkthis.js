/*
 * bookmarkthis
 *
 * @package bookmarkthis
 *   
 * DESCRIPTION:
 *
 * Displays a URL within a popup window
 *
 * USAGE:
*
    BookmarkThis.share('http://example.com/my-path', this);
*
*/
  
var BookmarkThis = new function()
{
  this.name = "bookmarkthis";
  
  this.share = function (path, e)
  {    
    var qTitle = this.getAttribute(e, 'data-title-query', '');
    var qDescription = this.getAttribute(e, 'data-description-query', "meta[name='description']");
    var qTags = this.getAttribute(e, 'data-tags-query', "meta[name='keywords']");
  
    var aTitle = this.getAttribute(e, 'data-title-attribute', '');
    var aDescription = this.getAttribute(e, 'data-description-attribute', 'content');
    var aTags = this.getAttribute(e, 'data-tags-attribute', 'content');
  
    var url = window.location.origin + window.location.pathname;
    
    if (qTitle)
    {
        title = this.query(qTitle, aTitle);
        
      } else {

        title = document.title;
    }
      
    description = this.query(qDescription, aDescription);
    
    tags = this.query(qTags, aTags);

    var find = ["{link}", "{title}", "{description}", "{tags}"];
    var replace = [url, title, description, tags];
    
    path = this.replace(find, replace, path);
    
    if (path.substring(0, 7) == 'mailto:')
    {
        window.location.href = path;
        
      } else {
      
        this.open(path);
        return false;
    }
    
    return false;
  };
  
  this.replace = function (find, replace, content)
  {
    if (find && replace && content)
    {
      for (var i = 0; i < find.length; i++)
      {
         content = content.replace(find[i], encodeURIComponent(replace[i]));
      }
    }
    
    return content;
  };
  
  this.open = function (url)
  {
    var D=800, A=500, C=screen.height, B=screen.width, H=Math.round((B/2)-(D/2)), G=0;

    if(C>A)
    {
      G=Math.round((C/2)-(A/2));
    }

    window.open(url, this.name, 'left='+H+', top='+G+', width='+D+', height='+A+', personalbar=0, toolbar=0, scrollbars=1, resizable=1');

    return false;
  };
  
  this.getAttribute = function (e, attribute, def)
  {
    try 
    {
        var s = e.getAttribute(attribute);
        
      } catch(err) {
        
        return def;
    }      
    
    if (s)
    {
        return s;
      } else {
        return def;
    }
  };
  
  this.query = function (query, attribute)
  {
    if (query)
    {
      var s = document.querySelector(query);
     
      if (s && attribute)
      {
        s = this.getAttribute(s, attribute);
        
        if (typeof s != "undefined")
        {
          return s;
        }
      }
    }
    
    return "";
  };
};