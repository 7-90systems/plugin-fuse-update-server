<?php
    /**
     *  @package fuse-update-server
     *
     *  This is our server class/
     */
    
    namespace Fuse\Plugin\UpdateServer;
    
    use Fuse\Traits\Singleton;
    
    
    class Server {
        
        use Singleton;
        
        
        
        
        /**
         *  Initialise our class.
         */
        protected function _init () {
            // Set up our routes
            add_action ('rest_api_init', array ($this, 'setupRestRoutes'));
        } // init ()
        
        
        
        
        /**
         *   Define our REST routes for our server endpoints.
         */
        public function setupRestRoutes () {
            $setup = register_rest_route ('fuseupdateserver', '/v1', array (
                'method' => 'post',
                'callback' => array ($this, 'handleServer'),
                'permissions_callback' => array ($this, 'permissionsAlwaysTrue')
            ));
error_log ("REST handler set up... '".$setup."'");
foreach ($_POST as $key => $val) {
    error_log ("    -  '".$key."' - '".$val."'");
}
        } // setupRestRoutes ()
        
        
        
        
        /**
         *  Handle a server call
         */
        public function handleServer ($request) {
            $result = array (
                'success' => false,
                'error' => __ ('An unknown error has occurred', 'fuse')
            );
            
            $actions = array (
                'plugin_information' => 'PluginData',
                'basic_check' => 'PluginUpdate',
                'theme_information' => 'ThemeData',
                'theme_update' => 'ThemeUpdate'
            );
            
            $action = array_key_exists ('action', $_POST) ? $_POST ['action'] : '';
            $args = array_key_exists ('request', $_POST) ? unserialize ($_POST ['request']) : array ();
error_log ("Server action: '".$action."'");
header ("HTTP/1.1 200 OK");
            
            
            
            echo json_encode ($result);
            die ();
        } // handleServer ()
        
        /**
         *  Allow all calls
         */
        public function permissionsAlwaysTrue () {
            return true;
        } // permissionsAlwaysTrue ()
        
    } // class Server