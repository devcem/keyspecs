<?php

    /*
    Plugin Name: KeySpecs
    Plugin URI: http://keyspecs.org/
    Description: Put any product's features and specification information to your store in a stylish table automatically.
    Author: imagets
    Version: 1.0.0
    Author URI: https://keyspecs.org
    */

    define('KEYSPECS_PLUGIN_NAME', 'KeySpecs');
    define('KEYSPECS_PLUGIN_URL', 'https://keyspecs.org');
    define('KEYSPECS_PLUGIN_MAIN_FILE_PATH', __FILE__);
    define('KEYSPECS_DEFAULT_THEME', 'theme001');
    define('KEYSPECS_CONFIG_MENU_TEXT', 'KeySpecs Settings');

    require_once('plugin.php');
    KEYSPECS_Plugin_init();

    register_activation_hook(KEYSPECS_PLUGIN_MAIN_FILE_PATH,   array('KEYSPECS_Plugin', 'activate')); 
    register_deactivation_hook(KEYSPECS_PLUGIN_MAIN_FILE_PATH, array('KEYSPECS_Plugin', 'deactivate')); 
    register_uninstall_hook(KEYSPECS_PLUGIN_MAIN_FILE_PATH,    array('KEYSPECS_Plugin', 'uninstall'));