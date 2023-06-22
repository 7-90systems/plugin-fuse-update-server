<?php
    /**
     *  @packaeg fuse-update-server
     *
     *  Thusis our theme update class.
     */
    
    namespace Fuse\Plugin\UpdateServer\Request;
    
    use Fuse\Plugin\UpdateServer\Request;
    use Fuse\Plugin\UpdateServer\Model;
    
    
    class ThemeUpdate extends Request {
        
        /**
         *  Get the data for this request.
         */
        public function call ($args = array ()) {
            $response = array (
                'success' => false,
                'error' => __ ('An unknown error has occurred.', 'fuse')
            );
            
            if (empty ($args)) {
                $args = $this->_args;
            } // if ()
            
            if (is_object ($args)) {
                $tmp = array ();
                
                foreach (get_object_vars ($args) as $key => $val) {
                    $tmp [$key] = $val;
                } // foreach ()
                
                $args = $tmp;
            } // if ()
            
            if (is_array ($args)) {
                $slug = array_key_exists ('slug', $args) ? $args ['slug'] : '';
                
                $theme = get_posts (array (
                    'numberposts' => 1,
                    'post_type' => 'theme',
                    'meta_query' => array (
                        array (
                            'key' => 'fuse_updateserver_asset_slug',
                            'val' => $slug
                        )
                    )
                ));
                
                if (count ($theme) == 1) {
                    $theme = new Model\Theme ($theme [0]);
                    
                    $latest = $theme->getLatestVersion ();
                    
                    if (empty ($latest) === false) {
                        $response = array (
                            'package' => $theme->getLatestVersionDownloadFile (),
                            'new_version' => $latest->post_title,
                            'url' => $theme->getPageLink ()
                        );
                    } // if ()
                    else {
                        $response = '';
                    } // else
                } // if ()
                else {
                    $response ['error'] = __ ('Invalid plugin requested.', 'fuse');
                } // else
            } // if ()
            else {
                $response ['error'] = __ ('No request data available.', 'fuse');
            } // else
/*
ob_start ();
var_export ($response);
$tmp = ob_get_contents ();
ob_end_clean ();
error_log ($tmp);
*/
            return $response;
        } // call ()
        
    } // class ThemeUpdate