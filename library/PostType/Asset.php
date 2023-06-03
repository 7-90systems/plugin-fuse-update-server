<?php
    /**
     *  @package fuse-update server
     *
     *  This is our base class for both plugins and themes.
     */
    
    namespace Fuse\Plugin\UpdateServer\PostType;
    
    use Fuse\PostType;
    
    
    class Asset extends PostType {
        
        /**
         *  Object constructor.
         */
        public function __construct ($slug, $name_single, $name_plural, $args) {
            $args = array_merge (array (
                'public' => true,
                'publicly_queryable' => true,
                'menu_icon' => 'dashicons-admin-generic'
            ),  $args);
            
            parent::__construct ($slug, $name_single, $name_plural, $args);
        } // __construct ()
        
    } // class Asset