<?php
    /**
     *  @package fuse-update-server
     *
     *  This is our plugin model.
     */
    
    namespace Fuse\Plugin\UpdateServer\Model;
    
    use Fuse\Plugin\UpdateServer\Model;
    
    
    class Plugin extends Model {
        
        /**
         *  Get the sections for this pluign.
         *
         *  @return array The sections.
         */
        public function getSections () {
            $sections = array ();
            
            $section_names = array (
                'description',
                'installation',
                'faq',
                'other notes'
            );
            
            foreach ($section_names as $id) {
                $content = get_post_meta ($this->_post->ID, 'fuse_updateserver_plugin_section_'.$id, true);
                
                if (strlen ($content) > 0) {
                    $sections [$id] = $content;
                } // if ()
            } // foreach ()
            
            // Changelog
            $versions = array ();
            
            foreach ($this->getVersions () as $version) {
                if (strlen ($version->post_content) > 0) {
                    $versions [$version->post_title] = $version->post_content;
                } // if ()
            } // foreach ()
            
            if (count ($versions) > 0) {
                $changelog = '';
                
                foreach ($versions as $v => $content) {
                    $changelog.= '<h4>'.$v.' ('.date ('jS F, Y', strtotime ($version->post_date)).')</h4>'.apply_filters ('the_content', $content);
                } // foreach ()
                
                $sections ['changelog'] = $changelog;
            } // if ()
            
            // Screenshots
            $screenshots = $this->getScreenshots ();
            
            if (strlen ($screenshots) > 0) {
                $sections ['screenshots'] = $screenshots;
            } // if ()
            
            return $sections;
        } // getSections ()
        
        
        
        
        
        /**
         *  Get the list of icons.
         *
         *  @return array The iocn URLs
         */
        public function getIcons () {
            $icons = array ();
            
            $types = array (
                '2x',
                '1x',
                'default',
                'svg'
            );
            
            foreach ($types as $type) {
                $icon = intval (get_post_meta ($this->_post->ID, 'fuse_updateserver_plugin_icon_'.$type, true));
                
                if ($icon > 0) {
                    $icons [$type] = wp_get_attachment_image_url ($icon, 'full');
                } // if ()
            } // foreach ()
            
            return $icons;
        } // getIcons ()
        
        /**
         *  Get the banners list.
         *
         *  @return array The banner URLs
         */
        public function getBanners ($rtl = false) {
            $banners = array ();
            
            $types = array (
                'low',
                'high'
            );
            
            foreach ($types as $type) {
                if ($rtl === true) {
                    $field = 'fuse_updateserver_plugin_banner_rtl_'.$type;
                } // if ()
                else {
                    $field = 'fuse_updateserver_plugin_banner_'.$type;
                } // else
                
                $banner = intval (get_post_meta ($this->_post->ID, $field, true));
                
                if ($banner > 0) {
                    $banners [$type] = wp_get_attachment_image_url ($banner, 'full');
                } // if ()
            } // foreach ()
            
            $ids = get_post_meta ($this->_post->ID, '', true);
            
            return $banners;
        } // getBanners ()
        
        /**
         *  Get the RTL banners list.
         *
         *  @return array The banner URLs
         */
        public function getRtlBanners () {
            return $this->getBanners (true);
        } // getRtlBanners ()
        
    } // class Plugin