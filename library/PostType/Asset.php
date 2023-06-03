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
            add_meta_box ('fuse_updateserver_asset_data_meta', __ ('Data Fields', 'fuse'), array ($this, 'dataMeta'), $this->getSlug (), 'normal', 'high');
        } // addMetaBoxes ()
        
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
         *  Save our posts values.
         */
        public function savePost ($post_id, $post) {
            // Data fields
            foreach ($this->data_fields as $key => $label) {
                if (array_key_exists ('fuse_updateserver_assest_data_'.$key, $_POST)) {
                    update_post_meta ($post_id, 'fuse_updateserver_assest_data_'.$key, $_POST ['fuse_updateserver_assest_data_'.$key]);
                } // if ()
                else {
                    delete_post_meta ($post_id, 'fuse_updateserver_assest_data_'.$key);
                } // else
            } // foreach ()
        } // savePost ()
        
    } // class Asset