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
            // Information server
            $setup = register_rest_route ('fuseupdateserver/v1', '/data/', array (
                'methods' => \WP_REST_Server::CREATABLE,
                'callback' => array ($this, 'handleServer'),
                'permission_callback' => array ($this, 'permissionsAlwaysTrue')
            ));
            
            // Download server
            $download = register_rest_route ('fuseupdateserver/v1', '/download/(?P<id>[^\/\?]+.zip)', array (
                'methods' => \WP_REST_Server::ALLMETHODS,
                'callback' => array ($this, 'handleDownload'),
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

error_log ("  - Server action: '".$action."' - setting request handler class '".$actions [$action]."' (".$request_class.")");

ob_start ();
echo "Result:".PHP_EOL;
var_export ($result);
$tmp = ob_get_contents ();
ob_end_clean ();
error_log ($tmp);

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
         *  Set up our file download systme.
         */
        public function handleDownload ($request) {
            $response = __ ('An unknown error has occured', 'fuse');
            
            if (array_key_exists ('vid', $_GET)) {
                $version = get_post ($_GET ['vid']);
                
                if (empty ($version) === false && in_array ($version->post_type, array ('plugin_version', 'theme_version'))) {
                    $file = '';
            
                    $type = get_post_meta ($version->ID, 'fuse_updateserver_version_download_type', true);
                    
                     $file_id = intval (get_post_meta ($version->ID, 'fuse_updateserver_version_upload', true));
                            
                    if ($file_id > 0) {
                            $file = wp_get_attachment_url ($file_id);
                    } // if ()
                    
                    if (empty ($file) === false) {
                        $this->_recordDownload ($version->ID);

                        header("Pragma: public");
                        header("Expires: 0");
                        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                        header("Cache-Control: public");
                        header("Content-Description: File Transfer");
                        header("Content-type: application/octet-stream");
                        header("Content-Disposition: attachment; filename=\"".basename ($file)."\"");
                        header("Content-Transfer-Encoding: binary");
                        readfile (get_attached_file ($file_id));
                        die ();
                    } // if ()
                    else {
                        $response = __ ('No download file available', 'fuse');
                    } // else
                } // if ()
                else {
                    $response = __ ('Invalid resource requested', 'fuse');
                } // else
            } // if ()
            else {
                $response = __ ('No resource requested', 'fuse');
            } // else
            
            
            echo $response;
            die ();
        } // handleDownload ()
        
        
        
        
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
        
        
        
        
        /**
         *  Record a download.
         *
         *  @param int $version_id The version ID.
         */
        protected function _recordDownload ($version_id) {
            global $wpdb;
            
            $version = get_post ($version_id);
            
            $remote_site = array_key_exists ('REMOTE_HOST', $_SERVER) ? $_SERVER ['REMOTE_HOST'] : NULL;
            $ip_address = array_key_exists ('REMOTE_ADDR', $_SERVER) ? $_SERVER ['REMOTE_ADDR'] : NULL;
            
            $wpdb->insert ($wpdb->prefix.'fuse_updateserver_downloads', array (
                'asset_id' => $version->post_parent,
                'version_id' => $version->ID,
                'download_date' => current_time ('mysql'),
                'remote_site' => $remote_site,
                'ip_address' => $ip_address
            ), array (
                '%d',
                '%d',
                '%d',
                '%s',
                '%s'
            ));
        } // _recordDownload ()
        
        
    } // class Server