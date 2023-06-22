<?php
// Function to disable media pages
function disable_media_pages_redirect() {
    global $post;

    if (is_attachment()) {
        wp_redirect(home_url());
        exit;
    }
}
add_action('template_redirect', 'disable_media_pages_redirect');

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

// Function to load plugin text domain for translation
function disable_media_pages_load_textdomain() {
    load_plugin_textdomain('disable-media-pages', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'disable_media_pages_load_textdomain');
