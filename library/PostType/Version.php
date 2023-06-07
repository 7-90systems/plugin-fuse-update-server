<?php
    /**
     *  @package fuse-update server
     *
     *  This is our base class for versions of both plugins and themes.
     */
    
    namespace Fuse\Plugin\UpdateServer\PostType;
    
    use Fuse\PostType;
    
    
    class Version extends PostType {
        
        /**
         *  @var array Tese are our data fields
         */
        public $data_fields;
        
        
        
        
        /**
         *  Object constructor.
         */
        public function __construct ($slug) {
            $this->_parent_post_type = $slug;
            
            parent::__construct ($slug.'_version', __ ('Version', 'fuse'), __ ('Versions', 'fuse'), array (
                'public' => false,
                'publicly_queryable' => false,
                'rewrite' => false,
                'show_in_rest' => false,
                'supports' => array (
                    'title',
                    'editor'
                )
            ));
            
            $this->data_fields = array (
                'requires' => __ ('Minimum WordPress version', 'fuse'),
                'tested' => __ ('Tested up to WordPress version', 'fuse'),
                'requires_php' => __ ('Minimum PHP version', 'fuse')
            );
        } // __construct ()
        
        
        
        
        /**
         *  Add our meta boxes.
         */
        public function addMetaBoxes () {
            add_meta_box ('fuse_updateserver_version_download_meta', __ ('Download file', 'fuse'), array ($this, 'downloadMeta'), $this->getSlug (), 'normal', 'high');
            add_meta_box ('fuse_updateserver_version_data_meta', __ ('Data Fields', 'fuse'), array ($this, 'dataMeta'), $this->getSlug (), 'normal', 'high');
        } // addMetaBoxes ()
        
        /**
         *  Set up the download file meta box.
         */
        public function downloadMeta ($post) {
            $type = get_post_meta ($post->ID, 'fuse_updateserver_version_download_type', true);
            $upload = intval (get_post_meta ($post->ID, 'fuse_updateserver_version_upload', true));
            ?>
                <script type="text/javascript">
                    var fuse_update_server_download_select;
                    
                    jQuery (document).ready (function () {
                        fuse_update_server_download_select = jQuery ('#fuse_updateserver_version_download_type');
                        
                        fuseUpdateServerVersionDownloadSelect ();
                        fuse_update_server_download_select.change (fuseUpdateServerVersionDownloadSelect);
                    });
                    
                    function fuseUpdateServerVersionDownloadSelect () {
                        if (fuse_update_server_download_select.val () == 'local') {
                            jQuery ('#fuse-updateserver-download-local').show ();
                            jQuery ('#fuse-updateserver-download-remote').hide ();
                        } // if ()
                        else {
                            jQuery ('#fuse-updateserver-download-local').hide ();
                            jQuery ('#fuse-updateserver-download-remote').show ();
                        } // else
                    } // fuseUpdateServerVersionDownloadSelect ()
                </script>
                <table class="form-table">
                    <tr>
                        <th><?php _e ('Download type', 'fuse'); ?></th>
                        <td>
                            <select id="fuse_updateserver_version_download_type" name="fuse_updateserver_version_download_type">
                                <option value="local"<?php selected ($type, 'local'); ?>><?php _e ('Local file', 'fuse'); ?></option>
                                <option value="remote"<?php selected ($type, 'remote'); ?>><?php _e ('Remote file URL', 'fuse'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr id="fuse-updateserver-download-local">
                        <th><?php _e ('Upload file', 'fuse'); ?></th>
                        <td>
                            <?php if ($upload > 0): ?>
                                <?php
                                    $file = wp_get_attachment_url ($upload);
                                ?>
                                <a href="<?php echo esc_url ($file); ?>"><?php echo basename ($file); ?></a>
                                <br />
                                <br />
                            <?php endif; ?>
                            <input type="file" name="fuse_updateserver_version_upload" />
                        </td>
                    </tr>
                    <tr id="fuse-updateserver-download-remote">
                        <th><?php _e ('Remote file URL', 'fuse'); ?></th>
                        <td>
                            <input type="url" name="fuse_updateserver_version_remote" class="large-text" value="<?php esc_attr_e (get_post_meta ($post->ID, 'fuse_updateserver_version_remote', true)); ?>" />
                        </td>
                    </tr>
                </table>
            <?php
        } // downloadMeta ()
        
        /**
         *  Set up our data fields
         */
        public function dataMeta ($post) {

            ?>
                <table class="form-table">
                    <?php foreach ($this->data_fields as $key => $label): ?>
                    
                        <tr>
                            <th><?php echo $label; ?></th>
                            <td>
                                <input type="text" name="fuse_updateserver_version_data_<?php echo $key; ?>" value="<?php esc_attr_e (get_post_meta ($post->ID, 'fuse_updateserver_version_data_'.$key, true)); ?>" class="regular-text" />
                            </td>
                        </tr>
                    
                    <?php endforeach; ?>
                </table>
            <?php
        } // dataMeta ()
        
        
        
        
        /**
         *  Save the posts values.
         */
        public function savePost ($post_id, $post) {
            // Download file
            if (array_key_exists ('fuse_updateserver_version_download_type', $_POST)) {
                update_post_meta ($post_id, 'fuse_updateserver_version_download_type', $_POST ['fuse_updateserver_version_download_type']);
                update_post_meta ($post_id, 'fuse_updateserver_version_remote', $_POST ['fuse_updateserver_version_remote']);
                
                if (array_key_exists ('fuse_updateserver_version_upload', $_FILES) && $_FILES ['fuse_updateserver_version_upload']['size'] > 0 && $_FILES ['fuse_updateserver_version_upload']['error'] == 0) {
                    $file_id = \Fuse\Util::saveAttachmentFile ($_FILES ['fuse_updateserver_version_upload'], __ ('Verson download file', 'fuse'), $post);
                    
                    if ($file_id > 0) {
                        $current = get_post_meta ($post_id, 'fuse_updateserver_version_upload', true);
                        
                        if ($current > 0) {
                            wp_delete_attachment ($current, true);
                        } // if ()
                        
                        update_post_meta ($post_id, 'fuse_updateserver_version_upload', $file_id);
                    } // if ()
                } // if ()
            } // if ()
            
            // Data fields
            foreach ($this->data_fields as $key => $label) {
                if (array_key_exists ('fuse_updateserver_version_data_'.$key, $_POST)) {
                    update_post_meta ($post_id, 'fuse_updateserver_version_data_'.$key, $_POST ['fuse_updateserver_version_data_'.$key]);
                } // if ()
                else {
                    delete_post_meta ($post_id, 'fuse_updateserver_version_data_'.$key);
                } // else
            } // foreach ()
        } // savePost ()
        
    } // class Version