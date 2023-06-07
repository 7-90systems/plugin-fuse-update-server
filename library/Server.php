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
            $setup = register_rest_route ('fuseupdateserver', '/v1/', array (
                // 'method' => 'POST',
                'methods' => \WP_REST_Server::CREATABLE,
                'callback' => array ($this, 'handleServer'),
                'permission_callback' => array ($this, 'permissionsAlwaysTrue')
            ));
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
            
            if (array_key_exists ($action, $actions)) {
                $request_class = '\Fuse\Plugin\UpdateServer\Request\\'.$actions [$action];
                
                $args = array_key_exists ('request', $_POST) ? unserialize (stripslashes ($_POST ['request'])) : array ();
                
                $request = new $request_class ();
                
                $result = $this->_arrayToObject ($request->call ($args));
/*
error_log ("  - Server action: '".$action."' - setting request handler class '".$actions [$action]."' (".$request_class.")");

ob_start ();
echo "Result:".PHP_EOL;
var_export ($result);
$tmp = ob_get_contents ();
ob_end_clean ();
error_log ($tmp);
*/
            } // if ()
            else {
                $result ['error'] = __ ('Invalid action requested', 'fuse');
            } // else
            
            echo json_encode ($result);
            die ();
        } // handleServer ()
        
        /**
         *  Allow all calls
         */
        public function permissionsAlwaysTrue () {
            return true;
        } // permissionsAlwaysTrue ()
        
        
        
        
        /**
         *  Convert an array into an object.
         */
        protected function _arrayToObject ($array) {
            $object = false;
            
            if (count ($array) > 0) {
                $object = new \stdClass ();
                
                foreach ($array as $key => $val) {
                    $object->{$key} = $val;
                } // foraech ()
            } // if ()
            
            return $object;
        } // _arrayToObject ()
        
    } // class Server