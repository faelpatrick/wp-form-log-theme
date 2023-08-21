<?php
function enqueue_custom_scripts()
{
    wp_enqueue_script('ajax-js', get_template_directory_uri() . '/ajax.js', array('jquery'), null, true);
    wp_localize_script('ajax-js', 'frontendajax', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');


function form_log_custom_post_type()
{
    $labels = array(
        'name'               => _x('Form Logs', 'post type general name', 'text-domain'),
        'singular_name'      => _x('Form Log', 'post type singular name', 'text-domain'),
        'menu_name'          => _x('Form Logs', 'admin menu', 'text-domain'),
        'name_admin_bar'     => _x('Form Log', 'add new on admin bar', 'text-domain'),
        'add_new'            => _x('Add New', 'form log', 'text-domain'),
        'add_new_item'       => __('Add New Form Log', 'text-domain'),
        'new_item'           => __('New Form Log', 'text-domain'),
        'edit_item'          => __('Edit Form Log', 'text-domain'),
        'view_item'          => __('View Form Log', 'text-domain'),
        'all_items'          => __('All Form Logs', 'text-domain'),
        'search_items'       => __('Search Form Logs', 'text-domain'),
        'parent_item_colon'  => __('Parent Form Logs:', 'text-domain'),
        'not_found'          => __('No form logs found.', 'text-domain'),
        'not_found_in_trash' => __('No form logs found in Trash.', 'text-domain')
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,  // It's a private post type, not visible to the public
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'form-log'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'supports'           => array('title', 'editor', 'custom-fields'),
    );

    register_post_type('form-log', $args);
}

add_action('init', 'form_log_custom_post_type');

function save_form_data()
{
    $name = sanitize_text_field($_POST['name']);
    $phone = sanitize_text_field($_POST['phone']);
    $email = sanitize_email($_POST['email']);
    $message = sanitize_textarea_field($_POST['message']);

    $post_id = wp_insert_post(array(
        'post_title' => $name,
        'post_content' => $message,
        'post_status' => 'publish',
        'post_type' => 'form-log',
    ));

    if ($post_id) {
        update_post_meta($post_id, 'phone', $phone);
        update_post_meta($post_id, 'email', $email);
        echo 'Success';
    } else {
        echo 'Error';
    }

    wp_die();
}
add_action('wp_ajax_save_form_data', 'save_form_data');
add_action('wp_ajax_nopriv_save_form_data', 'save_form_data');
