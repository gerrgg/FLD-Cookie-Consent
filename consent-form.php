
<?php

function add_consent_form_to_body(){
  $consent_message = get_field('cookie-consent-message', 'option');
  $cookies = array();
  $unique_cookie_types = [];
  $bg_color = get_field('widget_background_color', 'option') ?? '#333';
  $fg_color = get_field('widget_foreground_color', 'option') ?? '#D2B48C';

  // get cookie data
  $args = array(
    'post_type' => 'cookie',
    'posts_per_page' => -1, // Retrieve all posts
    'post_status'    => array( 'publish' ),
  );

  $cookies_query = new WP_Query($args);

  if ($cookies_query->have_posts()) {
    while ($cookies_query->have_posts()) {
      $cookies_query->the_post();
      $cookie_id = get_the_ID();
      $cookie_title = get_the_title();
      $cookie_type = get_field('cookie_type');
      $description = get_field('cookie_description');
      $type = $cookie_type->slug;
      $label = $cookie_type->name;

      $cookie = [
        'id' => $cookie_id,
        'title' => $cookie_title,
        'taxonomy_label' => $label,
        'description' => $description
      ];

      if( ! isset($cookies[$type]) ){
        $cookies[$type] = [$cookie];
      } else {
        array_push($cookies[$type], $cookie);
      }

      if( ! isset($unique_cookie_types[$type]) ){
        $unique_cookie_types[$type] = $label;
      }
    }
  }


  // sort and prepare for output
  $reorder_types = reorder_cookie_types($unique_cookie_types);
  $hash = generate_cookie_hash($cookies);

  echo '<div id="fld-cookie-consent-root">';
    echo '<div class="wrap">';
      printf('<div class="std-content">%s</div>', $consent_message);
      echo '<form>';
        foreach($reorder_types as $key => $label){
          $nescessary = $key === 'strictly_necessary';

          printf(
            '<div class="form-group"><label><input %s type="checkbox" name="cookie_type[]" value="%s" />%s</label><button class="show-cookies"></button></div>', 
            $nescessary ? 'disabled checked' : '', 
            $key, 
            $label
          );

          echo '<div class="cookie-list">';
          foreach($cookies[$key] as $c){
            printf('<p>%s %s</p>', $c['title'], $c['description']);
          }
          echo '</div>';

        }
        printf('<input type="hidden" name="hash" value="%s" />', $hash);
      echo '</form>';
      echo '<div class="button-group"><button id="accept-all">Accept All</button><button id="confirm-choices">Confirm Choices</button></div>';
    echo '</div>';
  echo '</div>';
  printf( '<button class="floating-cookie-button" style="--bg-color: %s"><svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path fill="%s" d="M23.999 12.149c-.049 3.834-1.893 7.223-4.706 9.378-1.993 1.53-4.485 2.449-7.198 2.473-6.464.057-12.051-5.107-12.095-12 3.966 1.066 7.682-1.993 6-6 4.668.655 6.859-2.389 6.077-6 6.724.064 11.999 5.542 11.922 12.149zm-15.576-4.123c-.065 3.393-2.801 5.868-6.182 6.166 1.008 4.489 5.015 7.807 9.759 7.807 5.262 0 9.576-4.072 9.97-9.229.369-4.818-2.755-9.357-7.796-10.534-.277 2.908-2.381 5.357-5.751 5.79zm5.077 8.974c.828 0 1.5.672 1.5 1.5s-.672 1.5-1.5 1.5-1.5-.672-1.5-1.5.672-1.5 1.5-1.5zm-5.5-2.853c1.104 0 2 .896 2 2s-.896 2-2 2-2-.896-2-2 .896-2 2-2zm10-2.147c1.104 0 2 .896 2 2s-.896 2-2 2-2-.896-2-2 .896-2 2-2zm-5 0c.552 0 1 .448 1 1s-.448 1-1 1-1-.448-1-1 .448-1 1-1zm2.5-5c.828 0 1.5.672 1.5 1.5s-.672 1.5-1.5 1.5-1.5-.672-1.5-1.5.672-1.5 1.5-1.5zm-12.5 0c.552 0 1 .448 1 1s-.448 1-1 1-1-.448-1-1 .448-1 1-1zm-1.5-4c.828 0 1.5.672 1.5 1.5s-.672 1.5-1.5 1.5-1.5-.672-1.5-1.5.672-1.5 1.5-1.5zm6-2c.828 0 1.5.672 1.5 1.5s-.672 1.5-1.5 1.5-1.5-.672-1.5-1.5.672-1.5 1.5-1.5zm-3.5-1c.552 0 1 .448 1 1s-.448 1-1 1-1-.448-1-1 .448-1 1-1z"/></svg></button>', $bg_color, $fg_color);
}

function custom_compare($a, $b) {
  if ($a === 'Strictly Necessary') {
      return -1; // $a comes first
  }
  if ($b === 'Strictly Necessary') {
      return 1; // $b comes first
  }
  return strcasecmp($a, $b); // Alphabetical order (case-insensitive)
}

function reorder_cookie_types($cookie_types){
  $values = array_values($cookie_types);

  usort($values, 'custom_compare');

  $sorted_cookie_types = array();
  foreach ($values as $value) {
      $key = array_search($value, $cookie_types);
      $sorted_cookie_types[$key] = $value;
  }

  return $sorted_cookie_types;
}

function generate_cookie_hash($cookies){
  $raw = '';
  foreach($cookies as $cookie){
    $raw .= $cookie['id'];
  }

  return md5($raw);
}

function add_scripts_to_body(){
  $uuid = $_COOKIE['fldCookieConsentUUID'] ?? false;

  ?>
  <script>
    function cookie_accepted(term_id) {
      if( checkCookie('fldCookieConsentGiven') ){
        const consentGiven = getCookieValue('fldCookieConsentGiven').split(',');
        return consentGiven.includes(term_id);
      } else {
        return true;
      }
    }
  </script>
  <?php
  $cookieArgs = array(
    'post_type' => 'cookie',
    'numberofposts' => -1,
  );

  $cookieQuery = new WP_Query($cookieArgs);

  if ($cookieQuery->have_posts()) {
    while ($cookieQuery->have_posts()) {
      $cookieQuery->the_post();
      $javascript = get_field('cookie_javascript') ?? null;
      $type = get_field('cookie_type');

      if($javascript){
        printf('<script>if (cookie_accepted("%s")) { %s }</script>', $type->term_id, $javascript);
      }
    }
  }
}
