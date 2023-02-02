<?php
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php' );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-includes/pluggable.php' );

  global $wpdb;
$data = json_decode(file_get_contents('php://input'), true);
$email =  sanitize_email($data['email']);

  if ( ! isset( $email ) || empty( $email ) ) {
    return new WP_Error( 'email_missing', 'Email is required', array( 'status' => 400 ) );
  }

  $table_name = 'customer_emails';
//   $timestamp = current_time( 'mysql' );

  $wpdb->insert(
    $table_name,
    array(
      'email' => $email,
    //   'timestamp' => $timestamp,
    )
  );

  if ( $wpdb->last_error ) {
    return new WP_Error( 'insert_error', 'Failed to insert customer email', array( 'status' => 500 ) );
  }
  send_custom_email($email);
  return new WP_REST_Response( array( 'message' => 'Customer email inserted successfully' ), 200 );


function send_custom_email( $email ) {


  $to = $email;
  $subject = "Your 10% Off Coupon";
  $message = "Hi!<br><br>Thanks for joining our mailing list! Your 10% off promo code is: <b>NEWVINTAGE</b><br>";
  $headers = array(
  'From: New Vintage Apparel <support@newvintageapparel.com>',
  'Content-Type: text/html; charset=UTF-8',
);

  if ( ! isset( $to ) || empty( $to ) ) {
    return new WP_Error( 'to_email_missing', 'To email is required', array( 'status' => 400 ) );
  }

  if ( ! isset( $subject ) || empty( $subject ) ) {
    return new WP_Error( 'subject_missing', 'Subject is required', array( 'status' => 400 ) );
  }

  if ( ! isset( $message ) || empty( $message ) ) {
    return new WP_Error( 'message_missing', 'Message is required', array( 'status' => 400 ) );
  }

  $mail = wp_mail( $to, $subject, $message, $headers );

  if ( ! $mail ) {
    return new WP_Error( 'send_email_error', 'Failed to send custom email', array( 'status' => 500 ) );
  }

  return new WP_REST_Response( array( 'message' => 'Custom email sent successfully' ), 200 );
}