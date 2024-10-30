<!-- Start Wrap -->

<div class="wrap">
    <h1 class="wp-heading-inline">Media List<a href="javascript:;" class="page-title-action add-socialmedia">Add New</a></h1>
    
    <hr class="wp-header-end" />
    <script type="text/javascript">
        var bsoft_social_ajax_nonce='<?php echo wp_create_nonce('bsoft-social-ajax-nonce'); ?>';
    </script>
    <?PHP wp_enqueue_media();?>

    <div class="bsoft-main-area">
        <!--  Insert data -->
        <h2>Social List</h2>
        <form method='post' name='main_form' id='main_form'>
            <table class="form-table">
                <tbody>
                    <p id="bsoft_blank_error" class="bsoft-error"></p>
                    <tr>
                        <th>
                            <label for="bsoft_redirect_name">Name</label>
                        </th>
                        <td>
                            <input class="regular-text" type="text" name="bsoft_redirect_name[0]" id="bsoft_redirect_name">
                            <span id="bsoft_name_error" class="bsoft-error-text"></span>
                            <p>Ex: Enter icon Name like Facebook, Twitter etc...</p>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="bsoft_redirect_link">Link</label>
                        </th>
                        <td>
                            <input class="regular-text" type="text" name="bsoft_redirect_link[0]" id="bsoft_redirect_link" required>
                            <span id="bsoft_link_error" class="bsoft-error-text"></span>
                            <p>e.g. http://www.facebook.com etc...</p>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="upload_image_button[0]">Image</label>
                        </th>
                        <td>
                            <input id="upload_image_button[0]" class="button bsoft_upload_image_button" type="button" value="<?php _e( 'Upload image' ); ?>" />
                            <div id="img_preview" class="img-preview-inner"></div>
                            <input type="hidden" name="upload_image_button[0]" id="bsoft_image_attachment_id[0]">
                            <input type="hidden" name="row_id" id="row-id">
                            <img id="save_spinner" class="bsoft_spinner" src="<?php echo esc_attr(admin_url('images/spinner-2x.gif')); ?>" alt="...">
                        </td>
                    </tr>
                </tbody>
            </table>
        </form> 
        <p class="submit">
            <button id="btnsave" name="btnsave" class="button button-primary">Save</button>
        </p>
    </div>
