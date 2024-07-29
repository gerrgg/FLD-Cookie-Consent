<?php

// Hook for plugin activation
register_activation_hook(__FILE__, 'consent_post_type_activation');

// Hook for plugin deactivation
register_deactivation_hook(__FILE__, 'consent_post_type_deactivation');

// Register the custom post type
add_action('init', 'consent_post_type');
add_action('acf/init', 'register_consent_acf_fields' );

// Remove the WYSIWYG editor for the consent post type
add_action('init', 'remove_editor_from_consent_post_type');

function remove_editor_from_consent_post_type() {
  remove_post_type_support('consent', 'editor');
}


// Function to run on plugin activation
function consent_post_type_activation() {
  // Register the custom post type
  consent_post_type();

  // Register custom post type taxonomy
  register_consent_type_taxonomy();
  
  // Flush rewrite rules
  flush_rewrite_rules();
}

function consent_post_type() {
  $labels = array(
      'name'               => _x('Consent', 'post type general name', 'fld-consent-consent'),
      'singular_name'      => _x('Consent', 'post type singular name', 'fld-consent-consent'),
      'menu_name'          => _x('Consent', 'admin menu', 'fld-consent-consent'),
      'name_admin_bar'     => _x('Consent', 'add new on admin bar', 'fld-consent-consent'),
      'add_new'            => _x('Add New', 'consent', 'fld-consent-consent'),
      'add_new_item'       => __('Add New Consent', 'fld-consent-consent'),
      'new_item'           => __('New Consent', 'fld-consent-consent'),
      'edit_item'          => __('Edit Consent', 'fld-consent-consent'),
      'view_item'          => __('View Consent', 'fld-consent-consent'),
      'all_items'          => __('All Consents', 'fld-consent-consent'),
      'search_items'       => __('Search Consents', 'fld-consent-consent'),
      'parent_item_colon'  => __('Parent Consents:', 'fld-consent-consent'),
      'not_found'          => __('No consents found.', 'fld-consent-consent'),
      'not_found_in_trash' => __('No consents found in Trash.', 'fld-consent-consent')
  );

  $args = array(
      'labels'             => $labels,
      'public'             => true,
      'publicly_queryable' => true,
      'show_ui'            => true,
      'show_in_menu'       => true,
      'query_var'          => true,
      'rewrite'            => array('slug' => 'consent'),
      'capability_type'    => 'post',
      'has_archive'        => true,
      'hierarchical'       => false,
      'menu_position'      => null,
      'menu_icon'          => "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0Ij48cGF0aCBmaWxsPSIjZjBmNmZjOTkiIGQ9Ik0wIDB2MjRoMjR2LTI0aC0yNHptMTEgMTdsLTUtNS4yOTkgMS4zOTktMS40MyAzLjU3NCAzLjczNiA2LjU3Mi03LjAwNyAxLjQ1NSAxLjQwMy04IDguNTk3eiIvPjwvc3ZnPg=="
  );

  register_post_type('consent', $args);
}

function register_consent_acf_fields(){
  // Check if ACF is active and then add the fields
  if (function_exists('acf_add_local_field_group')) {
    // create_acf_fields_for_consent();
  }
}

// Function to create ACF fields for the custom post type
function create_acf_fields_for_consent() {
  acf_add_local_field_group(array(
      'key' => 'group_consent_fields',
      'title' => 'Consent Fields',
      'fields' => array(
        array(
          'key' => 'field_cookie_type',
          'label' => 'Cookie Type',
          'name' => 'cookie_type',
          'type' => 'taxonomy',
          'instructions' => 'Select the type of cookie',
          'required' => 1,
          'taxonomy' => 'cookie_type',  // Replace with your taxonomy slug
          'field_type' => 'multi_select', // Change to multi_select for multi-select field
          'allow_null' => 0,
          'add_term' => 0,  // Allow adding new terms
          'save_terms' => 1,
          'load_terms' => 1,
          'return_format' => 'object',  // Can be 'id', 'object', or 'name'
          'multiple' => 1,  // Set to 1 to allow multiple selections
          'ui' => 1,
        ),
      ),
      'location' => array(
          array(
              array(
                  'param' => 'post_type',
                  'operator' => '==',
                  'value' => 'consent',
              ),
          ),
      ),
  ));
}

// Function to run on plugin deactivation
function consent_post_type_deactivation() {
  // Unregister the custom post type
  unregister_post_type('consent');
  
  // Flush rewrite rules
  flush_rewrite_rules();
}