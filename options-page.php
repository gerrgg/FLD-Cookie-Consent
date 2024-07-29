<?php 

if( function_exists('acf_add_options_page') ) {
  add_action('acf/init', 'my_acf_add_options_page');
  
  function my_acf_add_options_page() {
    acf_add_options_page(array(
        'page_title' 	=> 'Cookie Consent Settings',
        'menu_title'	=> 'FLD Cookie Settings',
        'menu_slug' 	=> 'fld-cookie-consent-settings',
        'capability'	=> 'edit_posts',
        'redirect'		=> false
    ));
  }

  acf_add_local_field_group(array(
    'key' => 'fld-cookie-consent-settings-group',
    'title' => 'Cookie Consent Settings',
    'fields' => array(
      array(
        'key' => 'field_checkbox_editor',
        'label' => 'Enable Beta Mode',
        'name' => 'beta-mode',
        'type' => 'true_false',
        'ui' => ['yes', 'no'],
        'instructions' => sprintf(
          'Enabling beta mode will requires a beta flag on the front to enable functionality. <a href="%s">%s</a>', 
          get_home_url() . '?beta=enabled',
          get_home_url() . '?beta=enabled',
        ),
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'default_value' => 0,

      ),
      array(
          'key' => 'field_wysiwyg_editor',
          'label' => 'Cookie Consent Message',
          'name' => 'cookie-consent-message',
          'type' => 'wysiwyg',
          'instructions' => 'Explain why the user is seeing this cookie consent form',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
          ),
          'default_value' => '',
          'tabs' => 'all',
          'toolbar' => 'full',
          'media_upload' => 0,
          'delay' => 0,
      ),
      array(
        'key' => 'cookie_bg',
        'label' => 'Widget Background Color',
        'name' => 'widget_background_color',
        'type' => 'color_picker',
        'instructions' => 'Select a background color for cookie widget',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'default_value' => '#333',
      ),
      array(
        'key' => 'cookie_fg',
        'label' => 'Widget Foreground Color',
        'name' => 'widget_foreground_color',
        'type' => 'color_picker',
        'instructions' => 'Select a foreground color for cookie widget',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'default_value' => '#D2B48C',
      ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'options_page',
                'operator' => '==',
                'value' => 'fld-cookie-consent-settings',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => '',
));
}