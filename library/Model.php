<?php
    /**
     *  @package fuse-update-server
     *
     *  This is our base asset model class.
     */
    
    namespace Fuse\Plugin\UpdateServer;
    
    
    class Model {
        
        /**
         *  @var WP_Post The post object that this model relates to.
         */
        protected $_post;
        
        
        
        /**
         *  Object constructor.
         *
         *  @param WP_Post|int $post The post object or ID.
         */
        public function __construct ($post) {
            if (is_numeric ($post)) {
                $post = get_post ($post);
            } // if ()
            
            $this->_post = $post;
        } // __construct ()
        
        
        
        
        /**
         *  Get the name for this assets.
         *
         *  @return string The assets name.
         */
        public function getName () {
            return $this->_post->post_title;
        } // getName ()
        
        /**
         *  Get the permalink for this asset.
         *
         *  @return string The paeg URL
         */
        public function getPageLink () {
            return get_permalink ($this->_post->ID);
        } // getPageLink ()
        
        
        
        /**
         *  Get the latest version for this assets.
         *
         *  @return Fuse\Plugin\UpdateServer\Version|NULL The latest version or a NULL value if none exist.
         */
        public function getLatestVersion () {
            $latest = NULL;
            $versions = $this->getVersions ();
            
            if (count ($versions) > 0) {
                $latest = $versions [0];
            } // if ()
            
            return $latest;
        } // getLatestVersion ()
        
        /**
         *  Get all of the versions for this asset.
         *
         *  @return array The versions list
         */
        public function getVersions () {
            return get_posts (array (
                'numberposts' => -1,
                'post_type' => $this->_post->post_type.'_version',
                'orderby' => 'title',
                'order' => 'DESC'
            ));
        } // getVersions ()
        
        /**
         *  Get the version downloads.
         *
         *  @return array The download files for each version.
         */
        public function getVersionDownloads () {
            $downloads = array ();
            
            foreach ($this->getVersions () as $version) {
                $file = $this->getVersionDownload ($version);
                
                if (empty ($file) === false) {
                    $downloads [$version->post_title] = $file;
                } // if ()
            } // foreach ()
            
            return $downloads;
        } // getVersionDownloads ()
         
         /**
          * Get the download link for the latest version.
          */
         public function getLatestVersionDownloadFile () {
            $file = '';

            $latest = $this->getLatestVersion ();
            
            if (empty ($latest) === false) {
                $file = $this->getVersionDownload ($latest);
            } // if ()
            
            return $file;
         } // getLatestVersionDownloadFile ()
         
         /**
          * Get the download file for the given version ID.
          *
          * @param WP_Post|int $version The version.
          *
          * return string The download URL.
          */
         public function getVersionDownload ($version) {
            if (is_numeric ($version)) {
                $version = get_post ($version);
            } // if ()
            
            $file = '';
            
            $type = get_post_meta ($version->ID, 'fuse_updateserver_version_download_type', true);
                
            $file_id = intval (get_post_meta ($version->ID, 'fuse_updateserver_version_upload', true));
                    
            if ($file_id > 0) {
                $file = wp_get_attachment_url ($file_id);
            } // if ()
            
            if (strlen ($file) > 0) {
                // Set up the download recording URL.
                $file = home_url ('/wp-json/fuseupdateserver/v1/download/'.basename ($file).'?vid='.$version->ID.'&s='.urlencode (base64_encode (home_url ())).'&key='.md5 (current_time ('mysql')), 'https');
            } // if ()
            
            return $file;
         } // getVersionDownload ()
         
         
         
         
         /**
          * Get the screenshots for this asset.
          *
          * return string The screenshot HTMML code.
          */
         public function getScreenshots () {
            $screenshots = '';
            
            $ids = get_post_meta ($this->_post->ID, 'fuse_updateserver_asset_screenshots', true);
            
            if (strlen ($ids) > 0) {
                $ids = explode (',', $ids);
                $ids = array_filter ($ids);
                
                if (count ($ids) > 0) {
                    foreach ($ids as $id) {
                        $image = wp_get_attachment_image_src ($id, 'full');
                        
                        $screenshots.= '<img src="'.esc_url ($image [0]).'" alt="'.esc_attr ($this->_post->ID).'" width="'.$image [1].'" height="'.$image [2].'" />';
                    } // foreach ()
                } // if ()
            } // if ()
            
            return $screenshots;
         } // getScreenshots ()
         
         
         
         
         /**
          * Get the authors name. This will include a HTML link if available
          *
          * @return string The authors name
          */
         public function getAuthor () {
            $author = get_post_meta ($this->_post->ID, 'fuse_updateserver_assest_data_author', true);
            $link = get_post_meta ($this->_post->ID, 'fuse_updateserver_assest_data_author_link', true);
            
            if (strlen ($link) > 0) {
                $author = '<a href="'.esc_url ($link).'" target="_blank">'.$author.'</a>';
                // $author = "<a href='".esc_url ($link)."' target='_blank'>".$author."</a>";
            } // if ()
            
            return $author;
         } // getAuthor ()
         
         
         
         
         /**
          * Get the count of downloads for this asset.
          *
          * return int The number of downloads.
          */
         public function getDownloadCount () {
            global $wpdb;
            
            $query = $wpdb->prepare ("SELECT
                COUNT(id)
            FROM ".$wpdb->prefix."fuse_updateserver_downloads
            WHERE asset_id = %d", $this->_post->ID);
            
            return $wpdb->get_var ($query);
         } // getDownloadCount ()
         
    } // class Model