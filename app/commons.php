<?php
/**
 * Fle for common functionality to be used throughout the application.
 */
session_start();

/**
 * Connect to the database.
 */
function db_connect() {
  // Database credentials.
  $server = 'localhost';
  $username = 'root';
  $password = 'root';
  $database = 'twitterino';

  // Create connection.
  $dbcon = mysqli_connect($server, $username, $password, $database);

  // Check connection.
  if (!$dbcon) {
    die('Connection failed: ' . mysqli_connect_error());
  }

  return $dbcon;
}

/**
 * Print messages and errors at the top of the page, if there are any.
 */
function print_info() {
  $info = '<div class="messages">';
  // Go through the messages in the session.
  foreach($_SESSION['messages'] as $message) {
    $info .= '<div class="alert ' . $message['type'] . '">' . $message['message'] . '</div>';
  }

  $info .= '</div>';
  // Reset the messages.
  unset($_SESSION['messages']);
  return $info;
}

/**
 * Adding a message or an error to the messages session scope to be printed in
 * the header.
 */
function add_message($type, $message) {
  $_SESSION['messages'][] = array(
    'type' => $type,
    'message' => $message,
  );
}

/**
* Sanitize a string to prevent weird characters.
*/
function sanitize($string) {
  $string = filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS);
  return $string;
}

/**
 * Checks weather a user is logged in or not.
 */
function is_logged_in() {
  if (isset($_SESSION['user']))
    return true;

  return false;
}
