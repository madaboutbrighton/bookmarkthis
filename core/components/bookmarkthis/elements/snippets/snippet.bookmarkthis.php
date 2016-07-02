<?php
/**
 * BookmarkThis
 *
 * @package bookmarkthis
 *
 * DESCRIPTION
 *
 * Returns a set of bookmarklets 
 *
 * PROPERTIES:
 *
 * &use           Comma separated list of items to be used from the data. If empty, all data items will be used
 * &exclude       Comma separated list of items to be excluded from the data
 * &data          Name of chunk containing the JSON data items
 * &tpl           Name of chunk to apply to each item
 * &tplWrapper    Name of chunk to wrap all tpl items inside
 * &appendJS      Whether to append the JavaScript to the end of the resource output
 * &appendCSS     Whether to append the CSS to the end of the resource head
 * &customFields  JSON data specifying the queries and attributes to obtain a bookmarklets title, description and tags
 *
 * USAGE:
 *
  [[!BookmarkThis]]
 *  
  [[!BookmarkThis?
    &id=`[[*id]]`
    &use=`googleplus, facebook, twitter, email`
  ]]
 *
 */

$bmt = $modx->getService(  'bookmarkthis',
                          'BookmarkThis',
                          $modx->getOption('bookmarkthis.core_path', null, $modx->getOption('core_path').'components/bookmarkthis/').'model/bookmarkthis/'
                        );
                          
if (!($bmt instanceof BookmarkThis)) return '';

return $bmt->getShare($scriptProperties);