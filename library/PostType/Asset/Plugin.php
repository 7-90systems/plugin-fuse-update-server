<?php
    /**
     *  @package fuse-update-server
     *
     *  This is our plugin post type.
     */
    
    namespace Fuse\Plugin\UpdateServer\PostType\Asset;
    
    use Fuse\Plugin\UpdateServer\PostType\Asset;
    
    
    class Plugin extends Asset {
        
        /**
         *  Objet constructor.
         */
        public function __construct () {
            parent::__construct ('plugin', __ ('Plugin', 'fuse'), __ ('Plugins', 'fuse'), array (
                'menu_icon' => 'dashicons-plugins-checked'
            ));
        } // __construct ()
        
    } // class Plugin