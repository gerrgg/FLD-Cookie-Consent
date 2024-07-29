<?php

add_action('wp_ajax_fetch_cookies', 'fetch_cookies_callback');
add_action('wp_ajax_nopriv_fetch_cookies', 'fetch_cookies_callback');

function fetch_cookies_callback() {
  $args = array(
    'post_type' => 'cookie',
    'posts_per_page' => -1, // Retrieve all posts
    'post_status'    => array( 'publish' ),
  );

  $cookies_query = new WP_Query($args);

  $cookies = array();

  if ($cookies_query->have_posts()) {
    while ($cookies_query->have_posts()) {
      $cookies_query->the_post();
      $cookie_id = get_the_ID();
      $cookie_title = get_the_title();
      $type = get_field('cookie_type');
      $choices = get_field_object('cookie_type')['choices'];
      $label = $choices[$type];
      // Customize the data you want to return
      $cookies[] = array(
        'id' => $cookie_id,
        'title' => $cookie_title,
        'taxonomy_slug' => $type,
        'taxonomy_label' => $label
      );
    }
  }

  wp_reset_postdata();

  // Send JSON response
  wp_send_json($cookies);
}

add_action('wp_ajax_accept_all_cookies', 'accept_all_cookies_callback');
add_action('wp_ajax_nopriv_accept_all_cookies', 'accept_all_cookies_callback');

function accept_all_cookies_callback(){
  $cookie_types = $_POST['cookie_types'];
  $hash = $_POST['hash'];
  $uuid = md5(uniqid(rand(), true));

  $post_data = array(
    'post_title'    => $uuid,
    'post_status'   => 'publish',
    'post_type'     => 'consent', 
  );

  $post_id = wp_insert_post($post_data);
  $term_ids = [];

  foreach(explode(',', $cookie_types) as $slug){
    $term = get_term_by('slug', $slug, 'cookie_type');
    array_push($term_ids, $term->term_id);
  }

  wp_set_object_terms($post_id, $term_ids, 'cookie_type');

  wp_send_json([
    'uuid' => $uuid,
    'post_id' => $post_id,
    'term_ids' => $term_ids
  ]);
}

add_action('wp_ajax_fetch_consents', 'fetch_consents_callback');
add_action('wp_ajax_nopriv_fetch_consents', 'fetch_consents_callback');

// takes the uuid, checks the category of cookies a user has consented to and returns a script for each cookie under that category
function fetch_consents_callback(){
  $uuid = $_POST['uuid'];
  
  $args = array(
    'name' => $uuid,
    'post_type' => 'consent',
    'posts_per_page' => 1 // We only need one post
  );

  $query = new WP_Query($args);
  $scripts = [];

  if ($query->have_posts()) {
    while ($query->have_posts()) {
      $query->the_post();
      $terms = wp_get_post_terms(get_the_ID(), 'cookie_type');

      $term_ids = array_map(function($term){
        return $term->term_id;
      }, $terms);

      $cookieArgs = array(
        'post_type' => 'cookie',
        'tax_query' => array(
          array(
              'taxonomy' => 'cookie_type',
              'field'    => 'term_id', // or 'term_id' depending on what you have
              'terms'    => $term_ids,
          ),
        ),
      );

      $cookieQuery = new WP_Query($cookieArgs);

      if ($cookieQuery->have_posts()) {
        while ($cookieQuery->have_posts()) {
          $cookieQuery->the_post();
          $javascript = get_field('cookie_javascript') ?? null;

          if($javascript){
            array_push($scripts, $javascript);
          }
        }

        wp_send_json($scripts);
      }
    }
  }
}