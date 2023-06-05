<?php
    /**
     *  @package fuse-update-server
     *
     *  Let's get our plugin set up!
     */
    
    namespace Fuse\Plugin\UpdateServer;
    
    use Fuse\Traits\Singleton;
    
    
    class Setup {
        
        use Singleton;
        
        
        
        
        /*
         *  Initialise our class.
         */
        protected function _init () {
            // SEt up our post types.
            add_action ('init', array ($this, 'setupPostTypes'), 1);
            
            // Add our REST server
            $server =  Server::getInstance ();
            
            // Are we in theaadmin area?
            if (is_admin ()) {
                $admin = Admin::getInstance ();
            } // if ()
        } // _init ()
        
        
        
        
        /**
         *  Set up the post types.
         */
        public function setupPostTypes () {
            if (get_fuse_option ('fuse_updateserver_plugin') == 'yes') {
                $plugin = new PostType\Asset\Plugin ();
                $plugin_version = new PostType\Version\Plugin ();
            } // if ()
            
            if (get_fuse_option ('fuse_updateserver_theme') == 'yes') {
                $theme = new PostType\Asset\Theme ();
                $theme_version = new PostType\Version\Theme ();
            } // if ()
        } // setupPostTypes ()
        
    } // class Setup