<?php
  $GLOBALS['dirpre'] = 'app/';
  require_once($dirpre.'includes/header.php');

  // Register functions to call.
  Router::register('index', function() {
    GLOBALvarGet('CStudent')->index();
  });
  Router::register('forgotpass', function() {
    GLOBALvarGet('CStudent')->forgotPass();
  });
  Router::register('jobs/index', function() {
    Controller::redirect('../index');
  });
  Router::register('jobs/editprofile', function() {
    GLOBALvarGet('CStudent')->edit();
  });
  Router::register('jobs/home', function() {
    GLOBALvarGet('CStudent')->home();
    GLOBALvarGet('CSublet')->manage();
  });
  Router::register('jobs/job', function() {
    GLOBALvarGet('CJob')->view();
  });
  Router::register('jobs/recruiter', function() {
    GLOBALvarGet('CRecruiter')->view();
  });
  Router::register('jobs/search', function() {
    GLOBALvarGet('CJob')->search();
  });
  Router::register('jobs/company', function() {
    GLOBALvarGet('CCompany')->view();
  });
  Router::register('login', function() {
    GLOBALvarGet('CStudent')->login();
  });
  Router::register('logout', function() {
    GLOBALvarGet('CStudent')->logout();
  });
  Router::register('messages', function() {
    GLOBALvarGet('CMessage')->reply();
  });
  Router::register('newmessage', function() {
    GLOBALvarGet('CMessage')->add();
  });
  Router::register('register', function() {
    GLOBALvarGet('CStudent')->register();
  });
  Router::register('whereto', function() {
    GLOBALvarGet('CStudent')->whereto();
  });

  // Map route to registered functions.
  Router::route('/index.php', 'index');
  Router::route('/index', 'index');
  Router::route('/', 'index');

  Router::route('/forgotpass', 'forgotpass');
  Router::route('/forgotpass.php', 'forgotpass');
  Router::route('/jobs/forgotpass', 'forgotpass');
  Router::route('/jobs/forgotpass.php', 'forgotpass');

  Router::route('/jobs/company', 'jobs/company');
  Router::route('/jobs/company.php', 'jobs/company');
  Router::route('/jobs/editprofile', 'jobs/editprofile');
  Router::route('/jobs/editprofile.php', 'jobs/editprofile');
  Router::route('/jobs', 'jobs/index');
  Router::route('/jobs/home', 'jobs/home');
  Router::route('/jobs/home.php', 'jobs/home');
  Router::route('/jobs/index', 'jobs/index');
  Router::route('/jobs/index.php', 'jobs/index');
  Router::route('/jobs/job', 'jobs/job');
  Router::route('/jobs/job.php', 'jobs/job');
  Router::route('/jobs/recruiter', 'jobs/recruiter');
  Router::route('/jobs/recruiter.php', 'jobs/recruiter');
  Router::route('/jobs/search', 'jobs/search');
  Router::route('/jobs/search.php', 'jobs/search');

  Router::route('/login', 'login');
  Router::route('/login.php', 'login');
  Router::route('/jobs/login', 'login');
  Router::route('/jobs/login.php', 'login');

  Router::route('/messages', 'messages');
  Router::route('/messages.php', 'messages');
  Router::route('/jobs/messages', 'messages');
  Router::route('/jobs/messages.php', 'messages');
  Router::route('/newmessage', 'newmessage');
  Router::route('/newmessage.php', 'newmessage');
  Router::route('/jobs/newmessage', 'newmessage');
  Router::route('/jobs/newmessage.php', 'newmessage');

  Router::route('/logout', 'logout');
  Router::route('/logout.php', 'logout');
  Router::route('/jobs/logout', 'logout');
  Router::route('/jobs/logout.php', 'logout');

  Router::route('/register', 'register');
  Router::route('/register.php', 'register');
  Router::route('/jobs/register', 'register');
  Router::route('/jobs/register.php', 'register');

  Router::route('/whereto', 'whereto');
  Router::route('/whereto.php', 'whereto');
  Router::route('/jobs/whereto', 'whereto');
  Router::route('/jobs/whereto.php', 'whereto');

  // Perform the routing.
  Router::run();

  require_once($dirpre.'includes/footer.php');
?>