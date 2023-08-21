<?php

function theme_enqueue_styles()
{
    wp_enqueue_style('main-css', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'theme_enqueue_styles');

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
        'public'             => false,
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





function form_log_list_shortcode()
{
    $args = array(
        'post_type' => 'form-log',
        'posts_per_page' => -1
    );

    $query = new WP_Query($args);
    $output = '';

    if ($query->have_posts()) {
        $output .= '<ul class="form-log-list">';
        while ($query->have_posts()) {
            $query->the_post();
            $output .= '<li><h2><b>Nome:</b> ' . get_the_title() . '</h2>';
            $output .= '<div class="custom-field field-msg"><b>Message:</b> ' . get_the_content() . '</div>';


            $phone = get_post_meta(get_the_ID(), 'phone', true);
            $email = get_post_meta(get_the_ID(), 'email', true);

            if ($phone) {
                $output .= '<div class="custom-field field-phone">';
                $output .= '<b>Phone:</b> ' . esc_html($phone);
                $output .= '</div>';
            }

            if ($email) {
                $output .= '<div class="custom-field field-email">';
                $output .= '<b>Email:</b> <a href="mailto:' . esc_html($email) . '">' . esc_html($email) . "</a>";
                $output .= '</div>';
            }


            $output .= '</li>';
        }
        $output .= '</ul>';
        wp_reset_postdata();
    } else {
        $output .= '<p>Nenhum registro encontrado.</p>';
    }

    return $output;
}
add_shortcode('form_log_list', 'form_log_list_shortcode');



function add_form_log_shortcode()
{
    ob_start();
    include get_template_directory() . '/page-form.php';
    return ob_get_clean();
}

add_shortcode('add_form_log', 'add_form_log_shortcode');
