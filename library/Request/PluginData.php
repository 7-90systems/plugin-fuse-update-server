<?php
    /**
     *  @packaeg fuse-update-server
     *
     *  Thusis our plugin data class.
     */
    
    namespace Fuse\Plugin\UpdateServer\Request;
    
    use Fuse\Plugin\UpdateServer\Request;
    use Fuse\Plugin\UpdateServer\Model;
    
    
    class PluginData extends Request {
        
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
                
                $plugin = get_posts (array (
                    'numberposts' => 1,
                    'post_type' => 'plugin',
                    'meta_query' => array (
                        array (
                            'key' => 'fuse_updateserver_asset_slug',
                            'val' => $slug
                        )
                    )
                ));
                
                if (count ($plugin) == 1) {
                    $plugin = new Model\Plugin ($plugin [0]);
                    
                    $latest = $plugin->getLatestVersion ();
                    
                    if (empty ($latest) === false) {
                        // Get our data
                        $plugin_page = $plugin->getPageLink ();
                        $download_file = $plugin->getLatestVersionDownloadFile ();
                        
                        $response = array (
                            'slug' => $slug,
                            'plugin' => $slug,
                            'name' => $plugin->getName (),
                            'version' => $latest->post_title,
                            'new_version' => $latest->post_title,
                            'date' => date ('Y-m-d', strtotime ($latest->post_date)),
                            'author' => $plugin->getAuthor (),
                            'requires' => get_post_meta ($latest->ID, 'fuse_updateserver_version_data_requires', true),
                            'tested' => get_post_meta ($latest->ID, 'fuse_updateserver_version_data_tested', true),
                            'requires_php' => get_post_meta ($latest->ID, 'fuse_updateserver_version_data_requires_php', true),
                            'homepage' => $plugin_page,
                            'downloaded' => '1000', /* TODO: ADd downloads  */
                            'package' => $download_file.'?key=' . md5('plugin.zip' . mktime(0,0,0,date("n"),date("j"),date("Y"))),
                            'file_name' => basename ($download_file),
                            'sections' => $plugin->getSections (),
                            'icons' => $plugin->getIcons (),
                            'banners' => $plugin->getBanners (),
                            'banners_rtl' => $plugin->getRtlBanners (),
                            'versions' => $plugin->getVersionDownloads ()
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
        
    } // class PluginData