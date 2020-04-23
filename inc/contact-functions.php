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
    $subject = 'The Hart Attack - message received from ' . $contactName;
    $headers = 'From: ' . $contactName . ' <' . $contactEmail . '>' . "\r\n";
    $headers .= 'Reply-To: ' . $contactEmail . "\r\n";
    $body = $contactMessage . "\r\n\r\n";
    $body .= $contactName . ' <' . $contactEmail . '>' . "\r\n";

    $sent = wp_mail($to, $subject, $body, $headers);
    if($sent) {
      echo json_encode(array(
        'status' => 1,
        'message' => 'Thank you, your message has been sent.'
      ));
      } else {
        echo json_encode(array(
          'status' => 0,
          'message' => 'Server error - please try again later.'
        ));
    }
  } else {
    echo json_encode(array(
      'status' => 0,
      'message' => 'Please complete all fields.'
    ));
  }

}
