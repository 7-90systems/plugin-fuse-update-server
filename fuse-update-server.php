<?php
    /**
     *  @package fuse-update-server
     *  @version 1.0
     *
     *  Plugin Name: Fuse CMS WordPress Plugin &amp; Theme Update Server
     *  Plugin URI: https://fusecms.org/plugins/update-server
     *  Description: You can use this plugin to run your own self-hosted update server for WordPress themes and pluigns.
     *  Author: 7-90 Systems
     *  Author URI: https://7-90.com.au
     *  Version: 1.0
     *  Requires at least: 6.0
     *  Requires PHP: 7.4
     *  Text Domain: fuse
     *  Fuse Update Server: http://fusecms.org
     */
    
    namespace Fuse\Plugin\UpdateServer;
    
    
    define ('FUSE_PLUGIN_UPDATESERVER_BASE_URI', __DIR__);
    define ('FUSE_PLUGIN_UPDATESERVER_BASE_URL:', plugins_url ('', __FILE__));
    
    
    $fuse_update_server_setup = Setup::getInstance ();