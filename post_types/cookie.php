<?php

// Hook for plugin activation
register_activation_hook(__FILE__, 'cookie_post_type_activation');

// Hook for plugin deactivation
register_deactivation_hook(__FILE__, 'cookie_post_type_deactivation');

// Register the custom post type
add_action('init', 'cookie_post_type');
add_action('acf/init', 'register_cookie_acf_fields' );

// register cookie type taxonomy
add_action('init', 'register_cookie_type_taxonomy');

// Remove the WYSIWYG editor for the cookie post type
add_action('init', 'remove_editor_from_cookie_post_type');

function remove_editor_from_cookie_post_type() {
    remove_post_type_support('cookie', 'editor');
}

// Function to run on plugin activation
function cookie_post_type_activation() {
  // Register the custom post type
  cookie_post_type();

  // Register custom post type taxonomy
  register_cookie_type_taxonomy();
  
  // Flush rewrite rules
  flush_rewrite_rules();
}

function cookie_post_type() {
    $labels = array(
        'name'               => _x('Cookies', 'post type general name', 'fld-cookie-consent'),
        'singular_name'      => _x('Cookie', 'post type singular name', 'fld-cookie-consent'),
        'menu_name'          => _x('Cookies', 'admin menu', 'fld-cookie-consent'),
        'name_admin_bar'     => _x('Cookie', 'add new on admin bar', 'fld-cookie-consent'),
        'add_new'            => _x('Add New', 'cookie', 'fld-cookie-consent'),
        'add_new_item'       => __('Add New Cookie', 'fld-cookie-consent'),
        'new_item'           => __('New Cookie', 'fld-cookie-consent'),
        'edit_item'          => __('Edit Cookie', 'fld-cookie-consent'),
        'view_item'          => __('View Cookie', 'fld-cookie-consent'),
        'all_items'          => __('All Cookies', 'fld-cookie-consent'),
        'search_items'       => __('Search Cookies', 'fld-cookie-consent'),
        'parent_item_colon'  => __('Parent Cookies:', 'fld-cookie-consent'),
        'not_found'          => __('No cookies found.', 'fld-cookie-consent'),
        'not_found_in_trash' => __('No cookies found in Trash.', 'fld-cookie-consent')
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'cookie'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGZpbGw9IiNmMGY2ZmM5OSIgZD0iTTEyLjA3OCAwYzYuNTg3LjA0MiAxMS45MjIgNS40MDMgMTEuOTIyIDEyIDAgNi42MjMtNS4zNzcgMTItMTIgMTJzLTEyLTUuMzc3LTEyLTEyYzMuODg3IDEuMDg3IDcuMzg4LTIuMzkzIDYtNiA0LjAwMy43MDcgNi43ODYtMi43MjIgNi4wNzgtNnptMS40MjIgMTdjLjgyOCAwIDEuNS42NzIgMS41IDEuNXMtLjY3MiAxLjUtMS41IDEuNS0xLjUtLjY3Mi0xLjUtMS41LjY3Mi0xLjUgMS41LTEuNXptLTYuODM3LTNjMS4xMDQgMCAyIC44OTYgMiAycy0uODk2IDItMiAyLTItLjg5Ni0yLTIgLjg5Ni0yIDItMnptMTEuMzM3LTNjMS4xMDQgMCAyIC44OTYgMiAycy0uODk2IDItMiAyLTItLjg5Ni0yLTIgLjg5Ni0yIDItMnptLTYtMWMuNTUyIDAgMSAuNDQ4IDEgMXMtLjQ0OCAxLTEgMS0xLS40NDgtMS0xIC40NDgtMSAxLTF6bS05LTNjLjU1MiAwIDEgLjQ0OCAxIDFzLS40NDggMS0xIDEtMS0uNDQ4LTEtMSAuNDQ4LTEgMS0xem0xMy41LTJjLjgyOCAwIDEuNS42NzIgMS41IDEuNXMtLjY3MiAxLjUtMS41IDEuNS0xLjUtLjY3Mi0xLjUtMS41LjY3Mi0xLjUgMS41LTEuNXptLTE1LTJjLjgyOCAwIDEuNS42NzIgMS41IDEuNXMtLjY3MiAxLjUtMS41IDEuNS0xLjUtLjY3Mi0xLjUtMS41LjY3Mi0xLjUgMS41LTEuNXptNi0yYy44MjggMCAxLjUuNjcyIDEuNSAxLjVzLS42NzIgMS41LTEuNSAxLjUtMS41LS42NzItMS41LTEuNS42NzItMS41IDEuNS0xLjV6bS0zLjUtMWMuNTUyIDAgMSAuNDQ4IDEgMXMtLjQ0OCAxLTEgMS0xLS40NDgtMS0xIC40NDgtMSAxLTF6Ii8+PC9zdmc+"
    );

    register_post_type('cookie', $args);
}

function register_cookie_type_taxonomy() {
  $labels = array(
      'name'              => _x('Cookie Types', 'taxonomy general name'),
      'singular_name'     => _x('Cookie Type', 'taxonomy singular name'),
      'search_items'      => __('Search Cookie Types'),
      'all_items'         => __('All Cookie Types'),
      'parent_item'       => __('Parent Cookie Type'),
      'parent_item_colon' => __('Parent Cookie Type:'),
      'edit_item'         => __('Edit Cookie Type'),
      'update_item'       => __('Update Cookie Type'),
      'add_new_item'      => __('Add New Cookie Type'),
      'new_item_name'     => __('New Cookie Type'),
      'menu_name'         => __('Categories'),
  );

  $args = array(
      'hierarchical'      => true,
      'labels'            => $labels,
      'show_ui'           => true,
      'show_admin_column' => true,
      'query_var'         => true,
      'rewrite'           => array('slug' => 'cookie-types'),
  );

  register_taxonomy('cookie_type', array('cookie', 'consent'), $args);

  foreach ($GLOBALS['terms'] as $slug => $name) {
    if (!term_exists($slug, 'cookie_type')) {
      wp_insert_term($name, 'cookie_type', array('slug' => $slug));
    }
  }
}

function register_cookie_acf_fields(){
  // Check if ACF is active and then add the fields
  if (function_exists('acf_add_local_field_group')) {
    create_acf_fields_for_cookie();
  }
}

// Function to create ACF fields for the custom post type
function create_acf_fields_for_cookie() {
    acf_add_local_field_group(array(
        'key' => 'group_cookie_fields',
        'title' => 'Cookie Fields',
        'fields' => array(
          array(
            'key' => 'field_cookie_type',
            'label' => 'Cookie Type',
            'name' => 'cookie_type',
            'type' => 'taxonomy',
            'instructions' => 'Select the type of cookie',
            'required' => 1,
            'taxonomy' => 'cookie_type',  // Replace with your taxonomy slug
            'field_type' => 'select',
            'allow_null' => 0,
            'add_term' => 1,  // Allow adding new terms
            'save_terms' => 1,
            'load_terms' => 1,
            'return_format' => 'object',  // Can be 'id', 'object', or 'name'
            'multiple' => 0,  // Set to 1 if you want to allow multiple selections
            'ui' => 1,
        ),
        array(
          'key' => 'field_cookie_description',
          'label' => 'Purpose',
          'name' => 'cookie_description',
          'type' => 'text',
          'required' => 0,
        ),
          array(
              'key' => 'field_cookie_javascript',
              'label' => 'Javascript',
              'name' => 'cookie_javascript',
              'type' => 'textarea',
              'instructions' => 'Enter javascript nessacary for building script',
              'required' => 0,
          ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'cookie',
                ),
            ),
        ),
    ));
}

// Function to run on plugin deactivation
function cookie_post_type_deactivation() {
    // Unregister the custom post type
    unregister_post_type('cookie');

    // unregister custom taxonomy
    unregister_taxonomy('cookie_type');
    
    // Flush rewrite rules
    flush_rewrite_rules();
}
?>
