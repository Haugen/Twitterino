<?php
/**
 * File for receiving and handeling form requests etc.
 */
include_once('commons.php');

$q = $_GET['q'];

/**
 * Check and prepare the input from user registration form. If everything is
 * OK, create the user. If not redirect to the front page with errors.
 */
if ($q == 'register-user') {
  // Sanitize the input.
  $email = sanitize($_POST["email"]);
  $pw = sanitize($_POST["password"]);
  $pw_again = sanitize($_POST["passwordAgain"]);

  // Check the e-mail address prpperly.
  $email = filter_var($email, FILTER_SANITIZE_SPECIAL_CHARS);
  if ($email == false) {
    add_message('alert-danger', 'Du har angivit en ogiltlig e-mail.');
  }

  // Check if password is matching.
  if ($pw != $pw_again) {
    add_message('alert-danger', 'Lösenorden stämmer inte överens med varandra.');
  }

  // If there is an error, put the message in a session.
  if (!empty($_SESSION['messages'])) {
    header("Location: index.php?q=register");
  }

  // If there is no errors, create the user and redirect to the frontpage.
  else {
    $user = array(
      'email' => $email,
      'password' => hash('md5', $pw)
    );

    include_once('user.php');
    register_user($user);
    add_message('alert-success', 'Din registrering är genomförd. Välkommen att logga in!');
    header("Location: index.php");
  }
}

/**
* Log out a user.
*/
if ($q == 'logout-user') {
  session_destroy();
  setcookie(session_name(),	"",	1);
  header("Location: index.php");
}

/**
 * Check and prepare input information and then try to log in a user.
 */
if ($q == 'login-user') {
  $email = sanitize($_POST["email"]);
  $pw = sanitize($_POST["password"]);

  $user = array(
    'email' => $email,
    'password' => hash('md5', $pw),
  );

  include_once('user.php');
  if (login_user($user)) {
    add_message('alert-success', 'Välkommen in i värmen!');
  }
  else {
    add_message('alert-danger', 'Fel e-mail eller lösenord.');
  }
  header("Location: index.php");
}
