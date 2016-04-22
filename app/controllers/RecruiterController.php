<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  interface RecruiterControllerInterface {
    public static function buyPlan();
    public static function home();
  }

  class RecruiterController extends Controller
                            implements RecruiterControllerInterface {
    public static function buyPlan() {
      self::requireLogin();

      if (isset($_GET['code']) &&
          ($code = $_GET['code']) == PaymentControllerAJAX::BUYPLAN_DISCOUNT) {
        $discount = "'$_GET[code]'";
      } else {
        $discount = 'false';
      }

      self::render('recruiter/buyplan', [
        'discount' => $discount
      ]);
    }

    public static function home() {
      self::requireLogin();

      $me = self::data(RecruiterModel::me());

      $myId = $_SESSION['_id'];
      if ($me['photo'] == 'assets/gfx/defaultpic.png')
        $me['photo'] = $GLOBALS['dirpreFromRoute'] . $me['photo'];

      $companyId = $me['company'];
      $company = CompanyController::data(CompanyModel::get($me['company']));

      $jobs = JobModel::getByRecruiter($myId);
      $jobCount = count($jobs);

      // Process job listings and counts.
      $jobArray = [];
      $applicantCount = 0;
      $totalViewCount = 0;
      $totalApplyCount = 0;
      foreach ($jobs as $job) {
        $jobId = $job['_id'];
        $applicants = ApplicationModel::countByJob($jobId);

        $jobListing = [
          '_id' => $jobId->{'$id'},
          'title' => $job['title'],
          'location' => $job['location'],
          'applicants' => $applicants['submitted']
        ];
        $jobArray[] = $jobListing;

        $applicantCount += $applicants['submitted'];
        $totalViewCount += $job['stats']['views'];
        $totalApplyCount += $job['stats']['clicks'];
      }

      // Process messages.
      $messages = array_slice(array_reverse(iterator_to_array(
        MessageModel::findByParticipant($_SESSION['_id']->{'$id'})
      )), 0, 4);
      $replies = [];
      $unread = 0;
      foreach ($messages as $m) {
        $reply = array_pop($m['replies']);
        $reply['_id'] = $m['_id']->{'$id'};

        $from = $reply['from'];
        if (!$reply['read']) {
          $reply['read'] = (strcmp($from, $_SESSION['_id']) == 0);
        }
        if (!$reply['read']) $unread ++;

        MessageController::setFromNamePic($reply, $from);

        $reply['time'] = timeAgo($reply['time']);

        if (strlen($reply['msg']) > 100) {
          $reply['msg'] = substr($reply['msg'], 0, 97) . '...';
        }

        $replies[] = $reply;
      }

      $personalprof = [
        'firstname' => $me['firstname'],
        'lastname' => $me['lastname'],
        'title' => $me['title'],
        'photo' => $me['photo']
      ];

      $companyprof = [
        'name' => $company['name'],
        'logophoto' => $company['logophoto'],
        'location' => $company['location'],
        'jobcount' => $jobCount,
        'applicantcount' => $applicantCount
      ];

      $stats = [
        'views' => $totalViewCount,
        'clicks' => $totalApplyCount
      ];

      $messages = [
        'messages' => $replies,
        'unread' => $unread
      ];

      $jobListings = toJSON($jobArray);

      self::render('recruiter/home', [
        'personal' => $personalprof,
        'company' => $companyprof,
        'stats' => $stats,
        'messages' => toJSON($messages),
        'jobs' => $jobListings
      ]);
    }

    // Validation functions
    function isValidName($name) { // Works for first or last name
      if(strlen($name) > 100) return false;
      if(preg_match('`[0-9]`', $name)) return false;
      return true;
    }

    function data($data) {
      $email = strtolower($data['email']);
      $pass = $data['pass'];
      $firstname = clean($data['firstname']);
      $lastname = clean($data['lastname']);
      $company = $data['company'];
      $title = clean($data['title']);
      $phone = isset($data['phone']) ? clean($data['phone']) : '';
      $photo = isset($data['photo']) ?
        clean($data['photo']) : $GLOBALS['dirpre'].'assets/gfx/defaultpic.png';
      $approved = $data['approved'];
      $unread = isset($data['unread']) ? $data['unread'] : 0;
      $credits = isset($data['credits']) ? $data['credits'] : 0;
      return array(
        'email' => $email, 'pass' => $pass, 'firstname' => $firstname,
        'lastname' => $lastname, 'company' => $company, 'title' => $title,
        'phone' => $phone, 'photo' => $photo, 'approved' => $approved,
        'credits' => $credits, 'unread' => $unread
      );
    }

    function validateData($data, &$err) {
      $this->validate($this->isValidName($data['firstname']),
        $err, 'first name is too long');
      $this->validate($this->isValidName($data['lastname']),
        $err, 'last name is too long');
    }

    function index() {
      self::render('recruiter/index');
    }

    function faq() {
      self::render('faq');
    }

    function privacy() {
      self::render('privacy');
    }

    function terms() {
      self::render('terms');
    }

    function register() {
      if (isset($_SESSION['loggedin'])) {
        $this->redirect('home');
        return;
      }
      if (!isset($_POST['register'])) { self::render('recruiter/register'); return; }

      global $params, $MRecruiter;
      // Params to vars
      $data = $params;
      $data['email'] = clean($params['email']);
      $data['pass'] = crypt($params['pass']);
      $data['approved'] = 'pending';
      $data['unread'] = 0;
      extract($data = $this->data($data));

      // Validations
      $this->startValidations();
      $this->validate(filter_var($email, FILTER_VALIDATE_EMAIL),
        $err, 'invalid email');
      $this->validate(!$MRecruiter->exists($email),
        $err, 'email taken');
      $this->validate($params['pass'] == $params['pass2'],
        $err, 'password mismatch');
      $this->validateData($data, $err);

      // Code
      if ($this->isValid()) {
        // Register the user, send a notice to us, and log him in
        $MRecruiter->save($data);
        $approveurl = "http://" . $GLOBALS['domain'] . "/approve.php?p=$pass";
        $msg = "New recruiter registered needs approval of account.
                <br />Registration information:<br />
                Email: $email<br />
                First Name: $firstname<br />
                Last Name: $lastname<br />
                Company: $company<br />
                Title: $title<br /><br />
                To approve: <a href=\"$approveurl\">$approveurl</a>";
        sendgmail(array('qingyang.chen@gmail.com', 'tony.jiang@yale.edu', 'yuanling.yuan@yale.edu', 'shirley.guo@yale.edu', 'alisa.melekhina@gmail.com', 'michelle.chan@yale.edu'), 'info@sublite.net', 'New Recruiter Requires Approval', $msg);
        $_POST['login'] = true; $this->login();
        return;
      }

      $this->error($err);
      self::render('recruiter/register', $data);
    }

    function approve() {
      if (!isset($_GET['p'])) { $this->redirect('index'); return; }

      global $params, $MRecruiter;
      // Params to vars
      $p = $_GET['p'];

      // Validations
      $this->startValidations();
      $this->validate(($entry = $MRecruiter->getByPass($p)) != NULL,
        $err, 'invalid');
      $this->validate($entry['approved'] == 'pending',
        $err, 'already approved');

      if ($this->isValid()) {
        $entry['approved'] = 'approved';
        $MRecruiter->save($entry);
        extract($entry);

        $msg = "Hi $firstname!
                <br /><br />
                Thank you for registering on SubLite! Your account has been approved.
                <br /><br />
                Log in at <a href=\"http://www.sublite.net/employers/login\">www.sublite.net/employers</a> to create a company profile and job listing!
                <br /><br />
                As always, please do not hesitate to contact us if you have any questions or suggestions.
                <br /><br />
                Best,<br />
                The SubLite Team";
        sendgmail(array($email), array("info@sublite.net",
          "SubLite, LLC."), 'SubLite Employers Account Approved!', $msg);

        echo 'Approved and automatic notification email sent!';
        return;
      }

      echo $err;
    }

    function login() {
      if (!isset($_POST['login'])) { self::render('recruiter/login'); return; }

      global $params, $MRecruiter;
      // Params to vars
      global $email;
      $email = strtolower(clean($params['email']));
      $pass = $params['pass'];
      $data = array('email' => $email);

      // Validations
      $this->startValidations();
      $this->validate(filter_var($email, FILTER_VALIDATE_EMAIL),
        $err, 'invalid email');
      $this->validate(
        ($entry = $MRecruiter->get($email)) != NULL and
        $MRecruiter->login($email, $pass),
        $err, 'invalid credentials<br><small><a href="' . $GLOBALS['dirpre'] . '../../login">Are you trying to log in as a student?</a></small>');
      $this->validate($entry['approved'] == 'approved',
        $err, 'account is pending approval. please allow 1-2 business days for us to verify your account. we will contact you when we approve your account. thank you!');

      if ($this->isValid()) {
        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $email;
        $_SESSION['pass'] = $pass;
        $_SESSION['_id'] = $entry['_id'];

        if (MongoId::isValid($entry['company'])) {
          $_SESSION['company'] = $entry['company'];
          $this->redirect('home');
        } else
          $this->redirect('addcompany');

        return;
      }

      $this->error($err);
      self::render('recruiter/login', $data);
    }

    function edit() {
      $this->requireLogin();

      global $params, $MRecruiter;
      if (!isset($_POST['edit'])) {
        self::render('recruiter/editprofile',
          $this->data($MRecruiter->me())); return;
      }

      // Params to vars
      $me = $MRecruiter->me();
      $id = $params['_id'] = $me['_id'];
      $params['email'] = $me['email'];
      $params['pass'] = $me['pass'];
      $params['company'] = $me['company'];
      $params['approved'] = $me['approved'];
      extract($data = $this->data($params));

      // Validations
      $this->startValidations();
      $this->validateData($data, $err);

      if ($this->isValid()) {
        $data['_id'] = new MongoId($id);
        $id = $MRecruiter->save($data);
        $this->success('profile saved');
        self::render('recruiter/editprofile', $data);
        return;
      }

      $this->error($err);
      self::render('recruiter/editprofile', $data);
    }


    function dataChangePass($data) {
      $pass = $data['pass'];
      $pass2 = $data['pass2'];
      return array(
        'pass' => $pass, 'pass2' => $pass2
      );
    }
    function changePass() {
      global $params, $MRecruiter;

      // Validations
      $this->startValidations();
      $this->validate(
          isset($_GET['id']) && isset($_GET['code']) &&
          !is_null($entry = $MRecruiter->getByID($id = new MongoId($_GET['id']))) &&
          $entry['pass'] == $_GET['code'],
        $err, 'permission denied');

      if ($this->isValid()) {
        if (!isset($_POST['change'])) { self::render('changepass'); return; }

        extract($data = $this->dataChangePass($params));

        $this->validate($pass == $pass2, $err, 'password mismatch');

        if ($this->isValid()) {
          $entry['pass'] = crypt($pass);
          $MRecruiter->save($entry);

          $params['email'] = $entry['email'];
          $_POST['login'] = true; $this->login();
          return;
        }

        $this->error($err);
        self::render('changepass', $data);
        return;
      }

      $this->error($err);
      self::render('notice');
    }

    function dataForgotPass($data) {
      $email = strtolower($data['email']);

      return array(
        'email' => $email
      );
    }
    function forgotPass() {
      global $params, $MRecruiter;

      if (!isset($_POST['forgot'])) { self::render('forgotpass'); return; }

      extract($data = $this->dataForgotPass($params));

      // Validations
      $this->startValidations();
      $this->validate(($entry = $MRecruiter->get($email)) != NULL,
        $err, 'no account found');
      $this->validate($entry['approved'] == 'approved',
        $err, 'account pending approval');

      if ($this->isValid()) {
        $id = $entry['_id'];
        $firstname = $entry['firstname'];
        $pass = $entry['pass'];
        $link = "http://sublite.net/employers/changepass.php?id=$id&code=$pass";

        $msg = "Hi $firstname!
                <br /><br />
                Below please find the link to reset your password. Thanks for using SubLite!
                <br /><br />
                Change your password here: <a href=\"$link\">$link</a>
                <br /><br />
                If you did not request this password reset, please contact us at <a href=\"mailto:info@sublite.net\">info@sublite.net</a>.
                <br /><br />
                Best,<br />
                The SubLite Team";
        sendgmail($email, array("info@sublite.net",
          "SubLite, LLC."), 'SubLite Recruiter Account Password Reset', $msg);

        $this->success('A link to reset your password has been sent to your email. If you do not receive it in the next hour, check your spam folder or whitelist info@sublite.net. <a href="mailto: info@sublite.net">Contact us</a> if you have any further questions.');
        self::render('forgotpass');
        return;
      }

      $this->error($err);
      self::render('forgotpass', $data);
    }

    function view() {
      // global $CJob; $CJob->requireLogin();

      global $params, $MRecruiter, $MCompany, $MJob;

      // Validations
      $this->startValidations();
      $this->validate(isset($_GET['id']) and
        ($entry = $MRecruiter->getByID($id = new MongoId($_GET['id']))) != NULL,
        $err, 'unknown recruiter');

      // Code
      if ($this->isValid()) {
        $data = $this->data($entry);

        $this->validate(($company = $MCompany->get($data['company'])) != NULL,
          $err, 'recruiter has not set up company profile');

        if ($this->isValid()) {
          $data['company'] = $company['name'];

          $jobs = $MJob->getByRecruiter($id);
          $data['jobtitles'] = array(); $data['joblocations'] = array();
          foreach ($jobs as $job) {
            array_push($data['jobtitles'], [$job['title'], $job['_id']->{'$id'}]);
            array_push($data['joblocations'], $job['location']);
          }

          $data['isme'] = isset($_SESSION['_id']) ? idcmp($id, $_SESSION['_id']) : false;
          $data['recruiterid'] = $id;

          if ($data['photo'] == 'assets/gfx/defaultpic.png')
            $data['photo'] = $GLOBALS['dirpreFromRoute'] . $data['photo'];

          self::displayMetatags('recruiter');
          self::render('recruiter/profile', $data);
          return;
        }
      }

      $this->error($err);
      self::render('notice');
    }

    function loggedIn() {
      return isset($_SESSION['loggedin']);
    }
    function requireLogin() {
      if (self::loggedIn()) {
        global $MRecruiter;
        // Params to vars
        $email = $_SESSION['email'];
        $pass = $_SESSION['pass'];
        $skippass = isset($_SESSION['skippass']);

        // Validations
        self::startValidations();
        self::validate(filter_var($email, FILTER_VALIDATE_EMAIL),
          $err, 'invalid email');
        self::validate(($entry = $MRecruiter->get($email)) != NULL,
          $err, 'unknown email');
        if (!$skippass) {
          self::validate(hash_equals($entry['pass'], crypt($pass, $entry['pass'])),
            $err, 'invalid password');
        }

        if (!self::isValid()) {
          self::logout();
        }
      } else {
        self::logout();
      }
    }
    function logout() {
      session_unset();
      self::redirect('index');
    }
  }

  GLOBALvarSet('CRecruiter', new RecruiterController());
?>
