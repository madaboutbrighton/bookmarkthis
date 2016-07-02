<?php

$output = '';

switch ($options[xPDOTransport::PACKAGE_ACTION])
{
  case xPDOTransport::ACTION_INSTALL:

  //$output = '<h4>BookmarkThis Installer</h4>
  //<p>Thanks for choosing to install BookmarkThis!</p>';

  break;

  case xPDOTransport::ACTION_UPGRADE:
  case xPDOTransport::ACTION_UNINSTALL:
  break;
}

return $output;