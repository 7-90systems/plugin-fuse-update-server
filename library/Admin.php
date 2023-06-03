<?php
    /**
     *  @package fuse-update-server
     *
     *  This clas sets up our administration options.
     */
    
    namespace Fuse\Plugin\UpdateServer;
    
    use Fuse\Traits\Singleton;
    use Fuse\Forms\Component;
    
    
    class Admin {
        
        use Singleton;
        
        
        /**
         *  Let's get our class set up!
         */
        protected function _init () {
            add_filter ('fuse_settings_form_panels', array ($this, 'addSettingsPanel'));
        } // _init ()
        
        
        
        
        /**
         *  Add our admin settings panel.
         *
         *  @param array $panels The existing panels
         *
         *  @return array Our completed panels list.
         */
        public function addSettingsPanel ($panels) {
            $panels [] = new Component\Panel ('update_server', __ ('Update Server', 'fuse'), array (
                new Component\Field\Toggle ('fuse_updateserver_plugin', __ ('Plugins', 'fuse'), get_fuse_option ('fuse_updateserver_plugin')),
                new Component\Field\Toggle ('fuse_updateserver_theme', __ ('Themes', 'fuse'), get_fuse_option ('fuse_updateserver_theme'))
            ));
            
            return $panels;
        } // addSettingsPanel ()
        
    } // class Admin