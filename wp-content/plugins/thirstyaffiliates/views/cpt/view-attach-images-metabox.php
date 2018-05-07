<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

wp_nonce_field( 'thirsty_affiliates_cpt_nonce', '_thirstyaffiliates_nonce' );

if ( function_exists( 'wp_enqueue_media' ) && $legacy_uploader != 'yes' ) : ?>

    <div class="button-secondary" id="ta_upload_media_manager"
         data-editor="content"
         data-uploader-title="<?php esc_attr_e( 'Add Image To Affiliate Link' , 'thirstyaffiliates' ); ?>"
         data-uploader-button-text="<?php esc_attr_e('Add To Affiliate Link'); ?>" >
        <?php _e( 'Upload/Insert' , 'thirstyaffiliates' ); ?>
        <span class="wp-media-buttons-icon"></span>
    </div>

<?php else : ?>

    <div class="button-secondary" id="ta_upload_insert_img">
        <?php _e( 'Upload/Insert' , 'thirstyaffiliates' ); ?>
        <a class="thickbox" href="<?php echo esc_attr( admin_url( 'media-upload.php?post_id=' . $post->ID . '?type=image&TB_iframe=1' ) ); ?>">
            <span class="wp-media-buttons-icon"></span>
        </a>
    </div>

<?php endif; ?>

<script type="text/javascript">
    var wpActiveEditor = 'content';
</script>

<?php if ( ! empty( $attachments ) ) : ?>
    <div id="thirsty_image_holder" data-attachments="<?php echo esc_attr( json_encode( $attachments ) ); ?>">
        <?php foreach ( $attachments as $attachment_id ) {
            $img = wp_get_attachment_image_src( $attachment_id , 'full' );
    		include( $this->_constants->VIEWS_ROOT_PATH() . 'cpt/view-attach-images-metabox-single-image.php' );
        } ?>
    </div>
<?php endif; ?>
