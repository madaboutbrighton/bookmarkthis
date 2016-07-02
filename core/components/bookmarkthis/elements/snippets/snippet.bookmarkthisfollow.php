<?php
/**
 * BookmarkThisFollow
 *
 * @package bookmarkthis
 *
 * DESCRIPTION
 *
 * Returns a set of follow-me icon links
 *
 * PROPERTIES:
 *
 * &id          ID of resource being shared
 * &use         Comma separated list of items to be used from the data. If empty, all data items will be used
 * &exclude     Comma separated list of items to be excluded from the data
 * &titleItem   Resource variable to use for the title of each bookmarklet. Either pagetitle, longtitle, menutitle or description
 * &tagsItem    The resource variable or template variable to use for the tag of each bookmarklet. A template variable name must be prefixed with "tv."
 * &data        Name of chunk containing the JSON data items
 * &tpl         Name of chunk to apply to each item
 * &tplWrapper  Name of chunk to wrap all tpl items inside
 * &appendJS    Whether to append the JavaScript to the end of the resource output
 * &appendCSS   Whether to append the CSS to the end of the resource head
 * &popup       Whether to use a JavaScript popup window. If false, a standard anchor link is used
 *
 * USAGE:
 *
  [[!BookmarkThisFollow? &id=`[[*id]]`]]
 *  
  [[!BookmarkThisFollow?
    &id=`[[*id]]`
    &data=`myCustomDataChunk`
    &use=`googleplus, facebook, twitter, github`
  ]]
 *
 */

$bmt = $modx->getService(  'bookmarkthis',
                          'BookmarkThis',
                          $modx->getOption('bookmarkthis.core_path', null, $modx->getOption('core_path').'components/bookmarkthis/').'model/bookmarkthis/'
                        );
                          
if (!($bmt instanceof BookmarkThis)) return '';

return $bmt->getFollow($scriptProperties);