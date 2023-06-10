<?php
    /**
     *  @package fuse-update server
     *
     *  @filter fuse_updateserver_datafields_{SLUG}
     *
     *  This is our base class for both plugins and themes.
     */
    
    namespace Fuse\Plugin\UpdateServer\PostType;
    
    use Fuse\PostType;
    
    
    class Asset extends PostType {
        
        /** @var array The data fields
         */
        public $data_fields;
        
        
        
        
        /**
         *  Object constructor.
         */
        public function __construct ($slug, $name_single, $name_plural, $args) {
            $args = array_merge (array (
                'public' => true,
                'publicly_queryable' => true,
                'has_archive' => true,
                'rewrite' => true,
                'show_in_nav_menus' => true,
                'menu_icon' => 'dashicons-admin-generic'
            ),  $args);
            
            parent::__construct ($slug, $name_single, $name_plural, $args);
            
            $this->data_fields = apply_filters ('fuse_updateserver_datafields_'.$slug, array (
                'author' => __ ('Authors name', 'fuse'),
                'author_link' => __ ('Authors homepage URL', 'fuse')
            ));
        } // __construct ()
        
        
        
        
        /**
         *  Add our meta boxes
         */
        public function addMetaBoxes () {
            add_meta_box ('fuse_updateserver_asset_slug_meta', __ ('Update Slug', 'fuse'), array ($this, 'slugMeta'), $this->getSlug (), 'normal', 'high');
            add_meta_box ('fuse_updateserver_asset_data_meta', __ ('Data Fields', 'fuse'), array ($this, 'dataMeta'), $this->getSlug (), 'normal', 'high');
            add_meta_box ('fuse_updateserver_asset_screenshots_meta', __ ('Screenshots', 'fuse'), array ($this, 'screenshotsMeta'), $this->getSlug (), 'normal', 'high');
        } // addMetaBoxes ()
        
        /**
         *  Set the slug meta box.
         */
        public function slugMeta ($post) {
            ?>
                <table class="form-table">
                    <tr>
                        <th><?php _e ('Download server slug', 'fuse'); ?></th>
                        <td>
                            <input type="text" name="fuse_updateserver_asset_slug" value="<?php esc_attr_e (get_post_meta ($post->ID, 'fuse_updateserver_asset_slug', true)); ?>" class="regular-text" />
                            <?php if (strpos (get_class ($this), 'Plugin') !== false): ?>
                                <p><?php _e ('Plugins slugs are the plugin folder and main file name. eg: "test-update-plugin/test-update-plugin.php"'); ?></p>
                            <?php else: ?>
                                <p><?php _e ('Theme slugs are the theme folder. eg: "test-update-theme"'); ?></p>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            <?php
        } // slugMeta ()
        
        /**
         *  Set up our data fields.
         */
        public function dataMeta ($post) {
            ?>
                <table class="form-table">
                    <?php foreach ($this->data_fields as $key => $label): ?>
                    
                        <tr>
                            <th><?php echo $label; ?></th>
                            <td>
                                <input type="text" name="fuse_updateserver_assest_data_<?php echo $key; ?>" value="<?php esc_attr_e (get_post_meta ($post->ID, 'fuse_updateserver_assest_data_'.$key, true)); ?>" class="regular-text" />
                            </td>
                        </tr>
                    
                    <?php endforeach; ?>
                </table>
            <?php
        } // datMeta ()
        
        /**
         *  Set up the screenshots meta box.
         */
        public function screenshotsMeta ($post) {
            $input = new \Fuse\Input\Gallery ('fuse_updateserver_asset_screenshots', get_post_meta ($post->ID, 'fuse_updateserver_asset_screenshots', true));
            $input->render ();
        } // screenshotsMeta ()
        
        
        
        
        /**
         *  Save our posts values.
         */
        public function savePost ($post_id, $post) {
            // Slug
            if (array_key_exists ('fuse_updateserver_asset_slug', $_POST)) {
                update_post_meta ($post_id, 'fuse_updateserver_asset_slug', $_POST ['fuse_updateserver_asset_slug']);
            }// if ()
            
            // Data fields
            foreach ($this->data_fields as $key => $label) {
                if (array_key_exists ('fuse_updateserver_assest_data_'.$key, $_POST)) {
                    update_post_meta ($post_id, 'fuse_updateserver_assest_data_'.$key, $_POST ['fuse_updateserver_assest_data_'.$key]);
                } // if ()
                else {
                    delete_post_meta ($post_id, 'fuse_updateserver_assest_data_'.$key);
                } // else
            } // foreach ()
            
            // screenshots
            if (array_key_exists ('fuse_updateserver_asset_screenshots', $_POST)) {
                update_post_meta ($post_id, 'fuse_updateserver_asset_screenshots', $_POST ['fuse_updateserver_asset_screenshots']);
            } // if ()
        } // savePost ()
        
    } // class Asset