<?php
  $GLOBALS['dirpre'] = 'app/';
  require_once($dirpre.'includes/header.php');

  // Register functions to call. Try to have these in alphabetical order.
  Router::register('index', function() {
    GLOBALvarGet('CStudent')->index();
  });
  Router::register('backtoindex', function() {
    Controller::redirect('../index');
  });
  Router::register('editprofile', function() {
    GLOBALvarGet('CStudent')->edit();
  });
  Router::register('forgotpass', function() {
    GLOBALvarGet('CStudent')->forgotPass();
  });
  Router::register('home', function() {
    GLOBALvarGet('CStudent')->home();
    GLOBALvarGet('CSublet')->manage();
  });
  Router::register('housing/addsublet', function() {
    GLOBALvarGet('CSublet')->add();
  });
  Router::register('housing/editsublet', function() {
    GLOBALvarGet('CSublet')->edit();
  });
  Router::register('housing/search', function() {
    GLOBALvarGet('CSublet')->search();
  });
  Router::register('housing/sublet', function() {
    GLOBALvarGet('CSublet')->view();
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
  Router::register('S3', function() {
    GLOBALvarGet('CS3')->upload();
  });
  Router::register('whereto', function() {
    GLOBALvarGet('CStudent')->whereto();
  });

  // Map route to registered functions. Try to have these in alphabetical order,
  // and then in groupings.
  Router::route('/index.php', 'index');
  Router::route('/index', 'index');
  Router::route('/', 'index');

  Router::route('/housing', 'backtoindex');
  Router::route('/housing/index', 'backtoindex');
  Router::route('/housing/index.php', 'backtoindex');
  Router::route('/jobs', 'backtoindex');
  Router::route('/jobs/index', 'backtoindex');
  Router::route('/jobs/index.php', 'backtoindex');

  Router::route('/housing/editprofile', 'editprofile');
  Router::route('/housing/editprofile.php', 'editprofile');
  Router::route('/jobs/editprofile', 'editprofile');
  Router::route('/jobs/editprofile.php', 'editprofile');

  Router::route('/forgotpass', 'forgotpass');
  Router::route('/forgotpass.php', 'forgotpass');
  Router::route('/jobs/forgotpass', 'forgotpass');
  Router::route('/jobs/forgotpass.php', 'forgotpass');

  Router::route('/housing/home', 'home');
  Router::route('/housing/home.php', 'home');
  Router::route('/jobs/home', 'home');
  Router::route('/jobs/home.php', 'home');

  Router::route('/housing/search', 'housing/search');
  Router::route('/housing/search.php', 'housing/search');
  Router::route('/housing/sublet', 'housing/sublet');
  Router::route('/housing/sublet.php', 'housing/sublet');
  Router::route('/housing/addsublet', 'housing/addsublet');
  Router::route('/housing/addsublet.php', 'housing/addsublet');
  Router::route('/housing/editsublet', 'housing/editsublet');
  Router::route('/housing/editsublet.php', 'housing/editsublet');

  Router::route('/jobs/company', 'jobs/company');
  Router::route('/jobs/company.php', 'jobs/company');
  Router::route('/jobs/job', 'jobs/job');
  Router::route('/jobs/job.php', 'jobs/job');
  Router::route('/jobs/recruiter', 'jobs/recruiter');
  Router::route('/jobs/recruiter.php', 'jobs/recruiter');
  Router::route('/jobs/search', 'jobs/search');
  Router::route('/jobs/search.php', 'jobs/search');

  Router::route('/login', 'login');
  Router::route('/login.php', 'login');
  Router::route('/housing/login', 'login');
  Router::route('/housing/login.php', 'login');
  Router::route('/jobs/login', 'login');
  Router::route('/jobs/login.php', 'login');

  Router::route('/logout', 'logout');
  Router::route('/logout.php', 'logout');
  Router::route('/housing/logout', 'logout');
  Router::route('/housing/logout.php', 'logout');
  Router::route('/jobs/logout', 'logout');
  Router::route('/jobs/logout.php', 'logout');

  Router::route('/messages', 'messages');
  Router::route('/messages.php', 'messages');
  Router::route('/housing/messages', 'messages');
  Router::route('/housing/messages.php', 'messages');
  Router::route('/jobs/messages', 'messages');
  Router::route('/jobs/messages.php', 'messages');
  Router::route('/newmessage', 'newmessage');
  Router::route('/newmessage.php', 'newmessage');
  Router::route('/housing/newmessage', 'newmessage');
  Router::route('/housing/newmessage.php', 'newmessage');
  Router::route('/jobs/newmessage', 'newmessage');
  Router::route('/jobs/newmessage.php', 'newmessage');

  Router::route('/register', 'register');
  Router::route('/register.php', 'register');
  Router::route('/housing/register', 'register');
  Router::route('/housing/register.php', 'register');
  Router::route('/jobs/register', 'register');
  Router::route('/jobs/register.php', 'register');

  Router::route('/S3', 'S3');
  Router::route('/S3.php', 'S3');
  Router::route('/housing/S3', 'S3');
  Router::route('/housing/S3.php', 'S3');

  Router::route('/whereto', 'whereto');
  Router::route('/whereto.php', 'whereto');
  Router::route('/housing/whereto', 'whereto');
  Router::route('/housing/whereto.php', 'whereto');
  Router::route('/jobs/whereto', 'whereto');
  Router::route('/jobs/whereto.php', 'whereto');

  // Perform the routing.
  Router::run();

  require_once($dirpre.'includes/footer.php');
?>