<?php
if (!defined('ABSPATH')) {
    exit;
}

// Disables access to media attachment pages
function disable_media_pages_redirect() {
    if (is_attachment()) {
        wp_redirect(home_url());
        exit;
    }
}
add_action('template_redirect', 'disable_media_pages_redirect');

// Retrieves ignored media files
function disable_media_pages_get_ignored_files() {
    return (array) get_option('disable_media_pages_ignored_files', array());
}

// Checks if a media file should be ignored
function disable_media_pages_should_ignore_file($file) {
    $ignored_files = disable_media_pages_get_ignored_files();
    return in_array($file, $ignored_files);
}

// Adds a media file to the ignored files list
function disable_media_pages_add_ignored_file($file) {
    $ignored_files = disable_media_pages_get_ignored_files();
    $ignored_files[] = $file;
    update_option('disable_media_pages_ignored_files', $ignored_files);
}

// Removes a media file from the ignored files list
function disable_media_pages_remove_ignored_file($file) {
    $ignored_files = disable_media_pages_get_ignored_files();
    $index = array_search($file, $ignored_files);
    if ($index !== false) {
        unset($ignored_files[$index]);
        update_option('disable_media_pages_ignored_files', array_values($ignored_files));
    }
}

// Loads plugin text domain for translation
function disable_media_pages_load_textdomain() {
    load_plugin_textdomain('disable-media-pages', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'disable_media_pages_load_textdomain');
