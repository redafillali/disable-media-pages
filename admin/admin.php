<?php
// Function to get ignored media files
function disable_media_pages_get_ignored_files() {
    // Retrieve ignored media files from saved options
    $ignored_files = get_option('disable_media_pages_ignored_files', array());

    return $ignored_files;
}

// Function to check if a media file should be ignored
function disable_media_pages_should_ignore_file($file) {
    $ignored_files = disable_media_pages_get_ignored_files();

    // Check if the file is in the ignored files list
    if (in_array($file, $ignored_files)) {
        return true;
    }

    return false;
}

// Function to add a media file to the ignored files list
function disable_media_pages_add_ignored_file($file) {
    $ignored_files = disable_media_pages_get_ignored_files();

    // Add the file to the ignored files list
    $ignored_files[] = $file;

    // Update the options
    update_option('disable_media_pages_ignored_files', $ignored_files);
}

// Function to remove a media file from the ignored files list
function disable_media_pages_remove_ignored_file($file) {
    $ignored_files = disable_media_pages_get_ignored_files();

    // Find and remove the file from the ignored files list
    $index = array_search($file, $ignored_files);
    if ($index !== false) {
        unset($ignored_files[$index]);

        // Reindex the array keys
        $ignored_files = array_values($ignored_files);

        // Update the options
        update_option('disable_media_pages_ignored_files', $ignored_files);
    }
}

// Function to display the plugin options in the WordPress admin dashboard
function disable_media_pages_settings_page() {
    // Check if the user has the necessary permissions
    if (!current_user_can('manage_options')) {
        return;
    }

    // Check if the form has been submitted
    if (isset($_POST['disable_media_pages_submit'])) {
        // Check form security
        if (!wp_verify_nonce($_POST['disable_media_pages_nonce'], 'disable_media_pages_settings')) {
            wp_die('Security error');
        }

        // Retrieve the ignored media files from the form
        $ignored_files = isset($_POST['disable_media_pages_ignored_files']) ? $_POST['disable_media_pages_ignored_files'] : array();

        // Update the options
        update_option('disable_media_pages_ignored_files', $ignored_files);

        // Redirect to the options page with a success message
        wp_redirect(admin_url('options-general.php?page=disable-media-pages&updated=true'));
        exit;
    }

    // Retrieve the currently saved ignored media files
    $ignored_files = disable_media_pages_get_ignored_files();
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Disable Media Pages Plugin Settings', 'disable-media-pages'); ?></h1>

        <?php if (isset($_GET['updated'])) : ?>
            <div class="notice notice-success">
                <p><?php esc_html_e('Options updated successfully.', 'disable-media-pages'); ?></p>
            </div>
        <?php endif; ?>

        <form method="post" action="<?php echo esc_url(admin_url('options-general.php?page=disable-media-pages')); ?>">
            <?php wp_nonce_field('disable_media_pages_settings', 'disable_media_pages_nonce'); ?>

            <table class="form-table">
                <tr>
                    <th scope="row"><?php esc_html_e('Ignored Media Files:', 'disable-media-pages'); ?></th>
                    <td>
                        <ul>
                            <?php foreach ($ignored_files as $file) : ?>
                                <li><?php echo esc_html($file); ?> <a href="<?php echo esc_url(add_query_arg('remove_file', urlencode($file))); ?>"><?php esc_html_e('Remove', 'disable-media-pages'); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                        <input type="text" name="disable_media_pages_new_file" placeholder="<?php esc_attr_e('Absolute path of the file', 'disable-media-pages'); ?>">
                        <input type="submit" class="button button-primary" name="disable_media_pages_add_file" value="<?php esc_attr_e('Add', 'disable-media-pages'); ?>">
                    </td>
                </tr>
            </table>

            <?php submit_button(esc_html__('Save Options', 'disable-media-pages'), 'primary', 'disable_media_pages_submit'); ?>
        </form>
    </div>
    <?php
}

// Add a settings page for the plugin
function disable_media_pages_add_settings_page() {
    add_submenu_page(
        'options-general.php',
        esc_html__('Disable Media Pages', 'disable-media-pages'),
        esc_html__('Disable Media Pages', 'disable-media-pages'),
        'manage_options',
        'disable-media-pages',
        'disable_media_pages_settings_page'
    );
}

// Call the disable_media_pages_add_settings_page function during WordPress admin initialization
add_action('admin_menu', 'disable_media_pages_add_settings_page');
