<?php

add_action('rest_api_init', 'theHartAttackContact');

function theHartAttackContact(){
  register_rest_route('thehartattack/v1', 'manageContact', array(
    'methods' => 'POST',
    'callback' => 'sendMail'
  ));
}

function sendMail($data){

  $contactName = sanitize_text_field($data['contactName']);
  $contactEmail = sanitize_text_field($data['contactEmail']);
  $contactMessage = sanitize_text_field($data['contactMessage']);

  if ($contactName != '' AND $contactEmail != '' AND $contactMessage != ''){
    $to = 'dan@thehartattack.com';
    $subject = 'THA Contact Form - message received from ' . $contactName;
    $headers = 'From: ' . $contactEmail . "\r\n";
    $headers .= 'Reply-To: ' . $contactEmail . "\r\n";
    $body = $contactMessage . "\r\n\r\n";
    $body .= $contactName . ' <' . $contactEmail . '>' . "\r\n";

    $sent = wp_mail($to, $subject, $body, $headers);
    if($sent) {
      return 'true';
      } else {
      return 'false';
    }
  } else {
    return 'Please complete all fields.';
  }

}
