<?php
    /**
     *  @package fuse-update-server
     *
     *  This is the version class for themes.
     */
    
    namespace Fuse\Plugin\UpdateServer\PostType\Version;
    
    use Fuse\Plugin\UpdateServer\PostType\Version;
    
    
    class Theme extends Version {
        
        /**
         *  Object constructor.
         */
        public function __construct () {
            parent::__construct ('theme');
        } // __construct ()
        
    } // class Theme