<?php
/*
 * Plugin Name:       Floodlight Cookie Consent
 * Description:       Manage cookies, allow consent and manage third party scripts
 * Version:           0.1
 * Requires PHP:      7.2
 * Author:            Floodlight Design
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       fld-cookie-consent
 */

if( !defined('ABSPATH') ) exit; // Exit if accessed directly

if( !class_exists('ACF') ) {
    add_action('admin_notices', function() {
        echo '<div class="error"><p>ACF Pro is required for this plugin to work.</p></div>';
    });
    return;
}

$GLOBALS['terms'] = array(
  'strictly_necessary' => 'Strictly Necessary',
  'functionality' => 'Functionality',
  'tracking_performance' => 'Tracking & Performance',
  'targeting_advertising' => 'Targeting & Advertising',
);


include_once  'post_types/cookie.php';
include_once  'post_types/consent.php';
include_once  'options-page.php';
include_once  'routes.php';
include_once  'consent-form.php';


$GLOBALS['beta'] = get_field('beta-mode', 'option') ?? false;

if(($GLOBALS['beta'] === '1' && $_GET['beta'] === 'enabled') || $GLOBALS['beta'] === '0' ){
  add_action('wp_footer', 'add_consent_form_to_body');
  add_action('wp_footer', 'add_scripts_to_body', 999);
}

add_action('wp_enqueue_scripts', 'fld_cookie_consent_enqueue_scripts');

function fld_cookie_consent_enqueue_scripts() {
  wp_enqueue_script('fld-consent-helpers', plugins_url('/js/helpers.js', __FILE__), array('jquery'), '1.0', true);
  wp_enqueue_script('fld-consent-form', plugins_url('/js/consent-form.js', __FILE__), array('jquery'), '1.0', true);
  wp_enqueue_style( 'fld-consent-form-style', plugins_url( 'css/consent-form.css', __FILE__ ), array(), '1.0.0', 'all' );
 
  // Localize the script with new data
  wp_localize_script('fld-consent-helpers', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}
