<?php
    /**
     *  @package fuse-update-server
     *
     *  This is our plugin post type.
     */
    
    namespace Fuse\Plugin\UpdateServer\PostType\Asset;
    
    use Fuse\Plugin\UpdateServer\PostType\Asset;
    
    
    class Plugin extends Asset {
        
        /**
         *  @var array Set up the sections.
         */
        protected $_sections;
        
        /**
         *  @var array Set up the icons.
         */
        protected $_icons;
        
        /**
         *  @var array Set up the banners.
         */
        protected $_banners;
        
        
        
        
        /**
         *  Objet constructor.
         */
        public function __construct () {
            parent::__construct ('plugin', __ ('Plugin', 'fuse'), __ ('Plugins', 'fuse'), array (
                'menu_icon' => 'dashicons-plugins-checked'
            ));
            
            $this->_sections = array (
                'description' => __ ('Description', 'fuse'),
                'installation' => __ ('Installation Info', 'fuse'),
                'faq' => __ ('FAQs', 'fuse'),
                'other notes' => __ ('Other Notes', 'fuse')
            );
            
            $this->_icons = array (
                '2x' => __ ('Large icon (2x)', 'fuse'),
                '1x' => __ ('Regular icon (1x)', 'fuse'),
                'default' => __ ('Default icon', 'fuse'),
                'svg' => __ ('SVG icon', 'fuse')
            );
            
            $this->_banners = array (
                'low' =>  __ ('Low resolution', 'fuse'),
                'high' => __ ('High Resolution', 'fuse')
            );
        } // __construct ()
        
        
        
        
        /**
         *  Set up our meta boxes.
         */
        public function addMetaBoxes () {
            parent::addMetaBoxes ();
            
            add_meta_box ('fuse_updateserver_plugin_sections_meta', __ ('Information Sections', 'fuse'), array ($this, 'sectionsMeta'), $this->getSlug (), 'normal', 'high');
            add_meta_box ('fuse_updateserver_plugin_icons_meta', __ ('Plugin Icons', 'fuse'), array ($this, 'iconsMeta'), $this->getSlug (), 'normal', 'high');
            add_meta_box ('fuse_updateserver_plugin_banners_meta', __ ('Plugin Banners', 'fuse'), array ($this, 'bannersMeta'), $this->getSlug (), 'normal', 'high');
            add_meta_box ('fuse_updateserver_plugin_banners_rtl_meta', __ ('Plugin Banners - RTL', 'fuse'), array ($this, 'bannersRtlMeta'), $this->getSlug (), 'normal', 'high');
        } // addMetaBoxes ()
        
        /**
         *  Set up our sections meta box.
         */
        public function sectionsMeta ($post) {
            ?>
                <table class="form-table">
                    <?php foreach ($this->_sections as $key => $label):  ?>
                    
                        <tr>
                            <th><?php echo $label; ?></th>
                            <td>
                                <textarea name="fuse_updateserver_plugin_section_<?php echo $key; ?>" class="large-text" rows="8"><?php echo stripslashes (get_post_meta ($post->ID, 'fuse_updateserver_plugin_section_'.$key, true)); ?></textarea>
                            </td>
                        </tr>
                    
                    <?php endforeach; ?>
                </table>
            <?php
        } // sectionsMeta ()
        
        /**
         *  Set up the icons meta box.
         */
        public function iconsMeta ($post) {
            ?>
                <p><?php _e ('Icons are shown in the plugin listing and update pages. Standard sizes are 128 pixels square for standar icons and 256 pixels square for large icons.', 'fuse'); ?></p>
                <table class="form-table">
                    <?php foreach ($this->_icons as $key => $label): ?>
                    
                        <tr>
                            <th><?php echo $label; ?></th>
                            <td>
                                <?php
                                    $input = new \Fuse\Input\Image ('fuse_updateserver_plugin_icon_'.$key, get_post_meta ($post->ID, 'fuse_updateserver_plugin_icon_'.$key, true));
                                    $input->render ();
                                ?>
                            </td>
                        </tr>
                    
                    <?php endforeach; ?>
                </table>
            <?php
        } // iconsMeta ()
        
        /**
         *  Set up the banners meta box.
         */
        public function bannersMeta ($post) {
            ?>
                <p><?php _e ('Banners are shown with the plugin update details.', 'fuse'); ?></p>
                <table class="form-table">
                    <?php foreach ($this->_banners as $key => $label): ?>
                    
                        <tr>
                            <th><?php echo $label; ?></th>
                            <td>
                                <?php
                                    $input = new \Fuse\Input\Image ('fuse_updateserver_plugin_banner_'.$key, get_post_meta ($post->ID, 'fuse_updateserver_plugin_banner_'.$key, true));
                                    $input->render ();
                                ?>
                            </td>
                        </tr>
                    
                    <?php endforeach; ?>
                </table>
            <?php
        } // bannersMeta ()
        
        /**
         *  Set up the RTL banners meta box.
         */
        public function bannersRtlMeta ($post) {
            ?>
                <p><?php _e ('Banners are shown with the plugin update details.', 'fuse'); ?></p>
                <table class="form-table">
                    <?php foreach ($this->_banners as $key => $label): ?>
                    
                        <tr>
                            <th><?php echo $label; ?></th>
                            <td>
                                <?php
                                    $input = new \Fuse\Input\Image ('fuse_updateserver_plugin_banner_rtl_'.$key, get_post_meta ($post->ID, 'fuse_updateserver_plugin_banner_rtl_'.$key, true));
                                    $input->render ();
                                ?>
                            </td>
                        </tr>
                    
                    <?php endforeach; ?>
                </table>
            <?php
        } // bannersRtlMeta ()
        
        
        
        
        /**
         *  Save the posts values.
         */
        public function savePost ($post_id, $post) {
            parent::savePost ($post_id, $post);
            
            // Sections
            foreach ($this->_sections as $key => $label) {
                if (array_key_exists ('fuse_updateserver_plugin_section_'.$key, $_POST)) {
                    update_post_meta ($post_id, 'fuse_updateserver_plugin_section_'.$key, $_POST ['fuse_updateserver_plugin_section_'.$key]);
                } // if ()
                else {
                    delete_post_meta ($post_id, 'fuse_updateserver_plugin_section_'.$key);
                } // else
            } // foreach ()
            
            // Icons
            foreach ($this->_icons as $key => $label) {
                if (array_key_exists ('fuse_updateserver_plugin_icon_'.$key, $_POST)) {
                    update_post_meta ($post_id, 'fuse_updateserver_plugin_icon_'.$key, $_POST ['fuse_updateserver_plugin_icon_'.$key]);
                } // if ()
                else {
                    delete_post_meta ($post_id, 'fuse_updateserver_plugin_icon_'.$key);
                } // else
            } // foreach ()
            
            // Banners
            foreach ($this->_banners as $key => $label) {
                if (array_key_exists ('fuse_updateserver_plugin_banner_'.$key, $_POST)) {
                    update_post_meta ($post_id, 'fuse_updateserver_plugin_banner_'.$key, $_POST ['fuse_updateserver_plugin_banner_'.$key]);
                } // if ()
                else {
                    delete_post_meta ($post_id, 'fuse_updateserver_plugin_banner_'.$key);
                } // else
                
                if (array_key_exists ('fuse_updateserver_plugin_banner_rtl_'.$key, $_POST)) {
                    update_post_meta ($post_id, 'fuse_updateserver_plugin_banner_rtl_'.$key, $_POST ['fuse_updateserver_plugin_banner_rtl_'.$key]);
                } // if ()
                else {
                    delete_post_meta ($post_id, 'fuse_updateserver_plugin_banner_rtl_'.$key);
                } // else
            } // foreach ()
        } // savePost ()
        
    } // class Plugin