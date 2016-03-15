<?php
  error_reporting(E_ALL & ~E_STRICT);
  ini_set('display_errors', '1');

  $GLOBALS['dirpre'] = 'app/';
  require_once($dirpre.'includes/header.php');

  // Register functions to call. Try to have these in alphabetical order.
  Router::register('admin/login', function() {
    AdminController::login();
  });
  Router::register('admin/questions', function() {
    AdminControllerQuestions::manage();
  });
  Router::register('backtoindex', function() {
    Controller::redirect("..");
  });
  Router::register('changepass', function() {
    GLOBALvarGet('CStudent')->changePass();
  });
  Router::register('confirm', function() {
    GLOBALvarGet('CStudent')->confirm();
  });
  Router::register('editprofile', function() {
    GLOBALvarGet('CStudent')->edit();
  });
  Router::register('employers/addcompany', function() {
    Controller::displayMetatags('/employers');
    GLOBALvarGet('CCompany')->add();
  });
  Router::register('employers/addjob', function() {
    Controller::displayMetatags('/employers');
    GLOBALvarGet('CJob')->add();
  });
  Router::register('employers/approve', function() {
    Controller::displayMetatags('/employers');
    GLOBALvarGet('CRecruiter')->approve();
  });
  Router::register('employers/buycredits', function() {
    Controller::displayMetatags('/employers');
    PaymentControllerAJAX::buyCredits();
  });
  Router::register('employers/buyplan', function() {
    Controller::displayMetatags('/employers');
    RecruiterController::buyPlan();
  });
  Router::register('employers/buyplanfinish', function() {
    PaymentControllerAJAX::buyPlan();
  });
  Router::register('employers/addcard', function() {
    PaymentControllerAJAX::addPaymentInfo();
  });
  Router::register('employers/removecard', function() {
    PaymentControllerAJAX::removePaymentInfo();
  });
  Router::register('employers/getcards', function() {
    PaymentControllerAJAX::getCards();
  });
  Router::register('employers/changepass', function() {
    Controller::displayMetatags('/employers');
    GLOBALvarGet('CRecruiter')->changePass();
  });
  Router::register('employers/company', function() {
    Controller::displayMetatags('/employers');
    GLOBALvarGet('CCompany')->view();
  });
  Router::register('employers/createcustom', function () {
    echo ApplicationControllerAJAX::createCustom();
  });
  Router::register('employers/deletecustom', function () {
    echo ApplicationControllerAJAX::deleteCustom();
  });
  Router::register('employers/searchcustom', function () {
    echo ApplicationControllerAJAX::searchCustom();
  });
  Router::register('employers/editapplication', function (array $restOfRoute) {
    Controller::displayMetatags('/employers');
    ApplicationController::edit($restOfRoute);
  });
  Router::register('employers/editcompany', function() {
    Controller::displayMetatags('/employers');
    GLOBALvarGet('CCompany')->edit();
  });
  Router::register('employers/deletejob', function (array $restOfRoute) {
    Controller::displayMetatags('/employers');
    JobController::delete($restOfRoute);
  });
  Router::register('employers/editjob', function() {
    Controller::displayMetatags('/employers');
    GLOBALvarGet('CJob')->edit();
  });
  Router::register('employers/editprofile', function() {
    Controller::displayMetatags('/employers');
    GLOBALvarGet('CRecruiter')->edit();
  });
  Router::register('employers/forgotpass', function() {
    Controller::displayMetatags('/employers');
    GLOBALvarGet('CRecruiter')->forgotPass();
  });
  Router::register('employers/home', function() {
    Controller::displayMetatags('/employers');
    RecruiterController::home();
  });
  Router::register('employers/index', function() {
    Controller::displayMetatags('/employers');
    GLOBALvarGet('CRecruiter')->index();
  });
  Router::register('employers/login', function() {
    Controller::displayMetatags('/employers');
    GLOBALvarGet('CRecruiter')->login();
  });
  Router::register('employers/loginregister', function() {
    Controller::displayMetatags('/employers');
    GLOBALvarGet('CRecruiter')->register();
    GLOBALvarGet('CRecruiter')->login();
  });
  Router::register('employers/logout', function() {
    Controller::displayMetatags('/employers');
    GLOBALvarGet('CRecruiter')->logout();
  });
  Router::register('employers/profile', function() {
    Controller::displayMetatags('/employers');
    // PROB THINK OF ANOTHER WAY OF REFACTORING THE FOLLOWING CODE
    if (isset($_SESSION['email'])) echo 'yay!';
    else echo 'nay...';
  });
  Router::register('employers/recruiter', function() {
    Controller::displayMetatags('/employers');
    GLOBALvarGet('CRecruiter')->view();
  });
  Router::register('employers/register', function() {
    Controller::displayMetatags('/employers');
    GLOBALvarGet('CRecruiter')->register();
  });
  Router::register('employers/S3', function() {
    Controller::displayMetatags('/employers');
    GLOBALvarGet('CS3')->upload();
  });
  Router::register('employers/viewapplicants', function (array $restOfRoute) {
    Controller::displayMetatags('/employers');
    ApplicationController::applicants($restOfRoute);
  });
  Router::register('employers/viewapplicants/tabunclaimed', function() {
    Controller::displayMetatags('/employers');
    ApplicationControllerAJAX::applicantsTabUnclaimed();
  });
  Router::register('employers/viewapplicants/tabclaimed', function() {
    Controller::displayMetatags('/employers');
    ApplicationControllerAJAX::applicantsTabClaimed();
  });
  Router::register('employers/viewapplicants/tabcredits', function() {
    Controller::displayMetatags('/employers');
    ApplicationControllerAJAX::applicantsTabCredits();
  });
  Router::register('employers/viewapplicants/moveapplications', function() {
    Controller::displayMetatags('/employers');
    ApplicationControllerAJAX::moveApplications();
  });
  Router::register('employers/viewapplicants/claimapplications', function() {
    Controller::displayMetatags('/employers');
    ApplicationControllerAJAX::claimApplications();
  });
  Router::register('faq', function() {
    GLOBALvarGet('CApp')->faq();
  });
  Router::register('favicon.ico', function() {
    Controller::redirect('app/assets/gfx/favicon.png');
  });
  Router::register('feedback', function() {
    GLOBALvarGet('CApp')->feedback();
  });
  Router::register('forgotpass', function() {
    GLOBALvarGet('CStudent')->forgotPass();
  });
  Router::register('graph', function() {
    GLOBALvarGet('CJob')->requireLogin();
    GLOBALvarGet('CStats')->graph();
  });
  Router::register('home', function() {
    StudentController::home();
    // GLOBALvarGet('CStudent')->home();
    // GLOBALvarGet('CSublet')->manage();
    // StudentController::manage();
  });
  Router::register('home/sublets', function() {
    StudentControllerAJAX::dashboardSublets();
  });
  Router::register('home/applications', function() {
    StudentControllerAJAX::dashboardApplications();
  });
  Router::register('home/messages', function() {
    StudentControllerAJAX::dashboardMessages();
  });
  Router::register('housing/addsublet', function() {
    GLOBALvarGet('CSublet')->add();
  });
  Router::register('housing/editsublet', function() {
    GLOBALvarGet('CSublet')->edit();
  });
  Router::register('housing/search', function() {
    Controller::displayMetatags('searchhousing');
    GLOBALvarGet('CSublet')->search();
  });
  Router::register('housing/sublet', function() {
    GLOBALvarGet('CSublet')->view();
  });
  Router::register('hubs/admin', function() {
    GLOBALvarGet('CSocial')->admin();
  });
  Router::register('hubs/adminapi', function() {
    echo GLOBALvarGet('CSocial')->adminapi();
  });
  Router::register('hubs/api', function() {
    echo GLOBALvarGet('CSocial')->api();
  });
  Router::register('hubs/hub', function() {
    echo GLOBALvarGet('CSocial')->hub();
  });
  Router::register('hubs/start', function() {
    echo GLOBALvarGet('CSocial')->index();
  });
  Router::register('index', function() {
    GLOBALvarGet('CStudent')->index();
  });
  Router::register('jobs/editprofile', function() {
    GLOBALvarGet('CStudent')->editStudentProfile();
  });
  Router::register('jobs/viewprofile', function () {
    GLOBALvarGet('CStudent')->viewStudentProfile();
  });
  Router::register('jobs/application', function (array $restOfRoute) {
    ApplicationController::view($restOfRoute);
  });
  Router::register('jobs/application/report', function () {
    ApplicationControllerAJAX::report();
  });
  Router::register('jobs/apply', function (array $restOfRoute) {
    ApplicationController::apply($restOfRoute);
  });
  Router::register('jobs/home', function() {
    StudentController::manage();
  });
  Router::register('jobs/job', function() {
    GLOBALvarGet('CJob')->view();
  });
  Router::register('jobs/recruiter', function() {
    GLOBALvarGet('CRecruiter')->view();
  });
  Router::register('jobs/search', function() {
    Controller::displayMetatags('searchjobs');
    GLOBALvarGet('CJob')->search();
  });
  Router::register('jobs/search/search', function() {
    JobControllerAJAX::search();
  });
  Router::register('jobs/companies', function() {
    CompanyController::viewAll();
  });
  Router::register('jobs/ajax/loadcompanies', function() {
    CompanyControllerAJAX::viewAll();
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
  Router::register('leaderboard', function() {
    AppController::leaderboard();
  });
  Router::register('messages', function() {
    GLOBALvarGet('CMessage')->reply();
  });
  Router::register('messagestats', function() {
    GLOBALvarGet('CStats')->requireLogin();
    GLOBALvarGet('CStats')->messages();
  });
  Router::register('migrate', function() {
    GLOBALvarGet('CMigrations')->migrate();
  });
  Router::register('newmessage', function() {
    GLOBALvarGet('CMessage')->add();
  });
  Router::register('privacy', function() {
    GLOBALvarGet('CApp')->privacy();
  });
  Router::register('redirect', function() {
    GLOBALvarGet('MJob')->incrementApply($_GET['id']);
    if(filter_var($_GET['url'], FILTER_VALIDATE_EMAIL)) {
      header("Location: mailto:" . $_GET['url']);
    } else {
      $link = $_GET['url'];
      if (!preg_match('`^(https?:\/\/)`', $_GET['url']))
        $link = "http://" . $link;
      header("Location: " . $link);
    }
  });
  Router::register('refer', function() {
    GLOBALvarGet('CStudent')->sendReferral();
  });
  Router::register('register', function() {
    GLOBALvarGet('CStudent')->register();
  });
  Router::register('runtests', function() {
    require_once($GLOBALS['dirpre'].'tests/runtests.php');
  });
  Router::register('S3', function() {
    GLOBALvarGet('CS3')->upload();
  });
  Router::register('S3/resume', function() {
    S3Controller::resume();
  });
  Router::register('stats', function() {
    GLOBALvarGet('CJob')->requireLogin();
    GLOBALvarGet('CStats')->loadStats();
  });
  Router::register('student/profile', function() {
    Controller::displayMetatags('student');
    GLOBALvarGet('CStudent')->view();
  });
  Router::register('team', function() {
    GLOBALvarGet('CApp')->team();
  });
  Router::register('terms', function() {
    GLOBALvarGet('CApp')->terms();
  });
  Router::register('whereto', function() {
    GLOBALvarGet('CStudent')->whereto();
  });

  // Map route to registered functions. Try to have these in alphabetical order,
  // and then in groupings.
  Router::route('/index', 'index');
  Router::route('/', 'index');

  Router::route('/admin/login', 'admin/login');
  Router::route('/admin/questions', 'admin/questions');

  Router::route('/housing', 'backtoindex');
  Router::route('/housing/index', 'backtoindex');
  Router::route('/jobs', 'backtoindex');
  Router::route('/jobs/index', 'backtoindex');
  Router::route('/hubs', 'backtoindex');
  Router::route('/hubs/index', 'backtoindex');

  Router::route('/changepass', 'changepass');
  Router::route('/confirm', 'confirm');

  Router::route('/housing/editprofile', 'editprofile');

  Router::route('/employers/addcompany', 'employers/addcompany');
  Router::route('/employers/addjob', 'employers/addjob');
  Router::route('/employers/approve', 'employers/approve');
  Router::route('/employers/ajax/buycredits', 'employers/buycredits');
  Router::route('/employers/ajax/addcard', 'employers/addcard');
  Router::route('/employers/ajax/removecard', 'employers/removecard');
  Router::route('/employers/ajax/getcards', 'employers/getcards');
  Router::route('/employers/buyplan', 'employers/buyplan');
  Router::route('/employers/ajax/buyplanfinish', 'employers/buyplanfinish');
  Router::route('/employers/changepass', 'employers/changepass');
  Router::route('/employers/company', 'employers/company');
  Router::route('/employers/createcustom', 'employers/createcustom');
  Router::route('/employers/deletecustom', 'employers/deletecustom');
  Router::route('/employers/searchcustom', 'employers/searchcustom');
  Router::routeTree('/employers/editapplication', 'employers/editapplication');
  Router::route('/employers/editcompany', 'employers/editcompany');
  Router::routeTree('/employers/deletejob', 'employers/deletejob');
  Router::route('/employers/editjob', 'employers/editjob');
  Router::route('/employers/editprofile', 'employers/editprofile');
  Router::route('/employers/forgotpass', 'employers/forgotpass');
  Router::route('/employers/home', 'employers/home');
  Router::route('/employers', 'employers/index');
  Router::route('/employers/index', 'employers/index');
  Router::route('/employers/login', 'employers/login');
  Router::route('/employers/loginregister', 'employers/loginregister');
  Router::route('/employers/logout', 'employers/logout');
  Router::route('/employers/profile', 'employers/profile');
  Router::route('/employers/recruiter', 'employers/recruiter');
  Router::route('/employers/register', 'employers/register');
  Router::routeTree('/employers/viewapplicants', 'employers/viewapplicants');
  Router::route('/employers/viewapplicants/ajax/tabunclaimed',
                'employers/viewapplicants/tabunclaimed');
  Router::route('/employers/viewapplicants/ajax/tabclaimed',
                'employers/viewapplicants/tabclaimed');
  Router::route('/employers/viewapplicants/ajax/tabcredits',
                'employers/viewapplicants/tabcredits');
  Router::route('/employers/viewapplicants/ajax/moveapplications',
                'employers/viewapplicants/moveapplications');
  Router::route('/employers/viewapplicants/ajax/claimapplications',
                'employers/viewapplicants/claimapplications');

  Router::route('/faq', 'faq');
  Router::route('/feedback', 'feedback');

  Router::route('/favicon.ico', 'favicon.ico');

  Router::route('/forgotpass', 'forgotpass');
  Router::route('/jobs/forgotpass', 'forgotpass');

  Router::route('/graph', 'graph');

  Router::route('/home', 'home');
  Router::route('/home/ajax/sublets', 'home/sublets');
  Router::route('/home/ajax/applications', 'home/applications');
  Router::route('/home/ajax/messages', 'home/messages');

  Router::route('/housing/search', 'housing/search');
  Router::route('/housing/sublet', 'housing/sublet');
  Router::route('/housing/addsublet', 'housing/addsublet');
  Router::route('/housing/editsublet', 'housing/editsublet');

  Router::route('/hubs/admin', 'hubs/admin');
  Router::route('/hubs/adminapi', 'hubs/adminapi');
  Router::route('/hubs/api', 'hubs/api');
  Router::route('/hubs/hub', 'hubs/hub');
  Router::route('/hubs/start', 'hubs/start');

  Router::routeTree('/jobs/application', 'jobs/application');
  Router::route('/jobs/application/report', 'jobs/application/report');
  Router::routeTree('/jobs/apply', 'jobs/apply');
  Router::route('/jobs/companies', 'jobs/companies');
  Router::route('/jobs/ajax/loadcompanies', 'jobs/ajax/loadcompanies');
  Router::route('/jobs/company', 'jobs/company');
  Router::route('/jobs/editprofile', 'jobs/editprofile');
  Router::route('/jobs/home', 'jobs/home');
  Router::route('/jobs/viewprofile', 'jobs/viewprofile');
  Router::route('/employers/job', 'jobs/job');
  Router::route('/jobs/job', 'jobs/job');
  Router::route('/jobs/recruiter', 'jobs/recruiter');
  Router::route('/employers/search', 'jobs/search');
  Router::route('/jobs/search', 'jobs/search');
  Router::route('/jobs/search/ajax/search', 'jobs/search/search');

  Router::route('/leaderboard', 'leaderboard');

  Router::route('/login', 'login');
  Router::route('/housing/login', 'login');
  Router::route('/jobs/login', 'login');

  Router::route('/logout', 'logout');
  Router::route('/housing/logout', 'logout');
  Router::route('/jobs/logout', 'logout');

  Router::route('/messagestats', 'messagestats');

  Router::route('/messages', 'messages');
  Router::route('/employers/messages', 'messages');
  Router::route('/housing/messages', 'messages');
  Router::route('/jobs/messages', 'messages');
  Router::route('/newmessage', 'newmessage');
  Router::route('/employers/newmessage', 'newmessage');
  Router::route('/housing/newmessage', 'newmessage');
  Router::route('/jobs/newmessage', 'newmessage');

  Router::route('/migrate', 'migrate');

  Router::route('/privacy', 'privacy');

  Router::route('/redirect', 'redirect');

  Router::route('/refer', 'refer');

  Router::route('/register', 'register');
  Router::route('/housing/register', 'register');
  Router::route('/jobs/register', 'register');

  Router::route('/runtests', 'runtests');

  Router::route('/S3', 'S3');
  Router::route('/employers/S3', 'S3');
  Router::route('/housing/S3', 'S3');
  Router::route('/S3/resume', 'S3/resume');
  Router::route('/student/profile', 'student/profile');

  Router::route('/stats', 'stats');

  Router::route('/team', 'team');

  Router::route('/terms', 'terms');

  Router::route('/whereto', 'whereto');
  Router::route('/housing/whereto', 'whereto');
  Router::route('/jobs/whereto', 'whereto');

  // Perform the routing.
  Router::run();

  require_once($dirpre.'includes/footer.php');
?>