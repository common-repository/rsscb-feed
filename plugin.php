<?php
/*
**************************************************************************

Plugin Name:  RSSCB Feed
Plugin URI:   http://www.bankofcanada.ca/
Version:      1.0
Description:  Adds an rsscb option to feeds
Author:       Michael Hall, Bank of Canada
        
**************************************************************************
*/

define('RSSCB_PLUGIN_DIR', __DIR__);
define('RSSCB_FEEDS_DIR', trailingslashit(RSSCB_PLUGIN_DIR).'feeds');

include(RSSCB_PLUGIN_DIR.'/BoC/Feed/RSSCB.php');

\BoC\Feed\RSSCB::init();
