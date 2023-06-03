<?php
    /**
     *  @package fuse-update-server
     *
     *  This is the version class for plugins.
     */
    
    namespace Fuse\Plugin\UpdateServer\PostType\Version;
    
    use Fuse\Plugin\UpdateServer\PostType\Version;
    
    
    class Plugin extends Version {
        
        /**
         *  Object constructor.
         */
        public function __construct () {
            parent::__construct ('plugin');
        } // __construct ()
        
    } // class Plugin