<?php
    /**
     *  @package fuse-update server
     *
     *  This is our base class for versions of both plugins and themes.
     */
    
    namespace Fuse\Plugin\UpdateServer\PostType;
    
    use Fuse\PostType;
    
    
    class Version extends PostType {
        
        /**
         *  Object constructor.
         */
        public function __construct ($slug) {
            $this->_parent_post_type = $slug;
            
            parent::__construct ($slug.'_version', __ ('Version', 'fuse'), __ ('Versions', 'fuse'), array (
                'public' => false,
                'publicly_queryable' => false,
                'rewrite' => false
            ));
        } // __construct ()
        
    } // class Version