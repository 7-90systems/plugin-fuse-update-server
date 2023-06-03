<?php
    /**
     *  @package fuse-update-server
     *
     *  This is our theme post type.
     */
    
    namespace Fuse\Plugin\UpdateServer\PostType\Asset;
    
    use Fuse\Plugin\UpdateServer\PostType\Asset;
    
    
    class Theme extends Asset {
        
        /**
         *  Objet constructor.
         */
        public function __construct () {
            parent::__construct ('theme', __ ('Theme', 'fuse'), __ ('Themes', 'fuse'), array (
                'menu_icon' => 'dashicons-welcome-widgets-menus'
            ));
        } // __construct ()
        
    } // class Theme