<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  interface RecruiterControllerInterface {
    public static function buyPlan();
    public static function home();

    public static function index();
    public static function faq();
    public static function privacy();
    public static function terms();

    public static function register();
    public static function approve();
    public static function login();
    public static function edit();
    public static function changePass();
    public static function forgotPass();
    public static function view();
    public static function requireLogin();
    public static function logout();
    public static function loggedIn();
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
          'applicants' => $applicants['submitted'],
          'newApplicants' => $applicants['unclaimed']
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
        'jobs' => $jobListings,
        '_id' => $myId
      ]);
    }

    public static function index() {
      self::render('recruiter/index');
    }

    public static function faq() {
      self::render('faq');
    }

    public static function privacy() {
      self::render('privacy');
    }

    public static function terms() {
      self::render('terms');
    }

    public static function register() {
      if (self::loggedIn()) {
        self::redirect('home');
        return;
      }
      if (!isset($_POST['register'])) {
        self::render('recruiter/register');
        return;
      }

      global $params;
      $params['email'] = clean(trim($params['email']));
      $params['approved'] = 'pending';
      extract($data = self::data($params));

      // Validations
      self::startValidations();
      self::validate(filter_var($email, FILTER_VALIDATE_EMAIL),
        $err, 'invalid email');
      self::validate(!RecruiterModel::exists($email), $err, 'email taken');
      self::validate($params['pass'] == $params['pass2'],
        $err, 'password mismatch');
      self::validateData($data, $err);

      // Code
      if (self::isValid()) {
        // Register the user, send a notice to us, and log him in
        $pass = $data['pass'] =
          password_hash($params['pass'], PASSWORD_DEFAULT);
        RecruiterModel::save($data);

        $approveurl = "http://$GLOBALS[domain]/approve.php?p=$pass";
        $msg = "New recruiter registered needs approval of account.
                <br />Registration information:<br />
                Email: $email<br />
                First Name: $firstname<br />
                Last Name: $lastname<br />
                Company: $company<br />
                Title: $title<br /><br />
                To approve: <a href=\"$approveurl\">$approveurl</a>";
        sendgmail([
          'qingyang.chen@gmail.com', 'tony.jiang@yale.edu', 'yuanling.yuan@yale.edu', 'shirley.guo@yale.edu', 'alisa.melekhina@gmail.com', 'michelle.chan@yale.edu'
        ], 'info@sublite.net', 'New Recruiter Requires Approval', $msg);
        $_POST['login'] = true;
        self::login();
        return;
      }

      self::error($err);
      self::render('register', $data);
    }

    public static function approve() {
      if (!isset($_GET['p'])) {
        self::redirect('index');
        return;
      }

      global $params;
      $p = $_GET['p'];

      // Validations
      self::startValidations();
      self::validate(($entry = RecruiterModel::getByPass($p)) != NULL,
        $err, 'invalid');
      self::validate($entry['approved'] == 'pending', $err, 'already approved');

      if (self::isValid()) {
        $entry['approved'] = 'approved';
        RecruiterModel::save($entry);
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
        sendgmail([ $email ], [ "info@sublite.net", "SubLite, LLC." ],
          'SubLite Employers Account Approved!', $msg);

        echo 'Approved and automatic notification email sent!';
        return;
      }

      echo $err;
    }

    public static function login() {
      if (!isset($_POST['login'])) {
        self::render('recruiter/login');
        return;
      }

      global $params;
      global $email;
      $email = strtolower(clean(trim($params['email'])));
      $pass = $params['pass'];
      $data = [ 'email' => $email ];

      // Validations
      self::startValidations();
      self::validate(filter_var($email, FILTER_VALIDATE_EMAIL),
        $err, 'invalid email');
      self::validate(
        ($entry = RecruiterModel::get($email)) != NULL &&
        RecruiterModel::login($email, $pass),
        $err, 'invalid credentials<br><small><a href="' . $GLOBALS['dirpre'] . '../../login">Are you trying to log in as a student?</a></small>');
      self::validate($entry['approved'] == 'approved',
        $err, 'account is pending approval. please allow 1-2 business days for us to verify your account. we will contact you when we approve your account. thank you!');

      if (self::isValid()) {
        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $email;
        $_SESSION['pass'] = $pass;
        $_SESSION['_id'] = $entry['_id'];

        if (MongoId::isValid($entry['company'])) {
          $_SESSION['company'] = $entry['company'];
          self::redirect('home');
        } else
          self::redirect('addcompany');

        return;
      }

      self::error($err);
      self::render('recruiter/login', $data);
    }

    public static function edit() {
      self::requireLogin();

      global $params;
      if (!isset($_POST['edit'])) {
        self::render('recruiter/editprofile', self::data(RecruiterModel::me()));
        return;
      }

      // Params to vars
      $me = RecruiterModel::me();
      $id = $params['_id'] = $me['_id'];
      $params['email'] = $me['email'];
      $params['pass'] = $me['pass'];
      $params['company'] = $me['company'];
      $params['approved'] = $me['approved'];
      extract($data = self::data($params));

      // Validations
      self::startValidations();
      self::validateData($data, $err);

      if (self::isValid()) {
        $data['_id'] = new MongoId($id);
        $id = RecruiterModel::save($data);
        self::success('profile saved');
        self::render('recruiter/editprofile', $data);
        return;
      }

      self::error($err);
      self::render('recruiter/editprofile', $data);
    }

    public static function changePass() {
      function data(array $data) {
        $pass = $data['pass'];
        $pass2 = $data['pass2'];
        return [ 'pass' => $pass, 'pass2' => $pass2 ];
      }

      global $params;

      // Validations
      self::startValidations();
      self::validate(
          isset($_GET['id']) && isset($_GET['code']) &&
          !is_null($entry = RecruiterModel::getById(
            $id = new MongoId($_GET['id'])
          )) && $entry['pass'] == $_GET['code'],
        $err, 'permission denied');

      if (self::isValid()) {
        if (!isset($_POST['change'])) {
          self::render('changepass');
          return;
        }

        extract($data = data($params));

        self::validate($pass == $pass2, $err, 'password mismatch');

        if (self::isValid()) {
          $entry['pass'] = password_hash($pass, PASSWORD_DEFAULT);
          RecruiterModel::save($entry);

          $params['email'] = $entry['email'];
          $_POST['login'] = true;
          self::login();
          return;
        }

        self::error($err);
        self::render('changepass', $data);
        return;
      }

      self::error($err);
      self::render('notice');
    }

    public static function forgotPass() {
      function data(array $data) {
        $email = strtolower($data['email']);
        return [ 'email' => $email ];
      }

      if (!isset($_POST['forgot'])) {
        self::render('forgotpass');
        return;
      }

      global $params;
      extract($data = self::data($params));

      // Validations
      self::startValidations();
      self::validate(($entry = RecruiterModel::get($email)) != NULL,
        $err, 'no account found');
      self::validate($entry['approved'] == 'approved',
        $err, 'account pending approval');

      if (self::isValid()) {
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
        sendgmail($email, [ "info@sublite.net", "SubLite, LLC." ],
          'SubLite Recruiter Account Password Reset', $msg);

        self::success(
          'A link to reset your password has been sent to your email. If you do
          not receive it in the next hour, check your spam folder or whitelist
          info@sublite.net. <a href="mailto: info@sublite.net">Contact us</a> if
          you have any further questions.'
        );
        self::render('forgotpass');
        return;
      }

      self::error($err);
      self::render('forgotpass', $data);
    }

    public static function view() {
      global $params;

      // Validations
      self::startValidations();
      self::validate(isset($_GET['id']) &&
        ($entry = RecruiterModel::getByID(
          $id = new MongoId($_GET['id'])
        )) != NULL, $err, 'unknown recruiter');

      // Code
      if (self::isValid()) {
        $data = self::data($entry);

        self::validate(($company = CompanyModel::get($data['company'])) != NULL,
          $err, 'recruiter has not set up company profile');

        if (self::isValid()) {
          $data['company'] = $company['name'];

          $jobs = JobModel::getByRecruiter($id);
          $data['jobtitles'] = [];
          $data['joblocations'] = [];
          foreach ($jobs as $job) {
            $data['jobtitles'][] = [
              'title' => $job['title'],
              '_id' => $job['_id']->{'$id'}
            ];
            if (is_array($job['location']))
              $data['joblocations'] =
                array_merge($data['joblocations'], $job['location']);
          }

          $data['isme'] =
            isset($_SESSION['_id']) ? idcmp($id, $_SESSION['_id']) : false;
          $data['recruiterid'] = $id;

          if ($data['photo'] == 'assets/gfx/defaultpic.png')
            $data['photo'] = $GLOBALS['dirpreFromRoute'] . $data['photo'];

          self::displayMetatags('recruiter');
          self::render('recruiter/profile', $data);
          return;
        }
      }

      self::error($err);
      self::render('notice');
    }

    public static function requireLogin() {
      if (!self::loggedIn()) {
        self::logout();
        return;
      }

      // Params to vars
      $email = $_SESSION['email'];
      $pass = $_SESSION['pass'];
      $skippass = isset($_SESSION['skippass']);

      // Validations
      self::startValidations();
      self::validate(filter_var($email, FILTER_VALIDATE_EMAIL),
        $err, 'invalid email');
      self::validate(($entry = RecruiterModel::get($email)) != NULL,
        $err, 'unknown email');
      if (!$skippass) {
        self::validate(RecruiterModel::login($email, $pass),
                       $err, 'invalid password');
      }

      if (!self::isValid()) {
        self::logout();
      }
    }

    public static function logout() {
      session_unset();
      self::redirect('index');
    }

    public static function loggedIn() {
      return isset($_SESSION['loggedin']);
    }

    private static function validateData(array $data, &$err) {
      function isValidName($name) { // Works for first or last name
        if (strlen($name) > 100) return false;
        if (preg_match('`[0-9]`', $name)) return false;
        return true;
      }

      self::validate(isValidName($data['firstname']),
        $err, 'first name is too long');
      self::validate(isValidName($data['lastname']),
        $err, 'last name is too long');
    }

    private static function data(array $data) {
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
      return [
        'email' => $email, 'pass' => $pass, 'firstname' => $firstname,
        'lastname' => $lastname, 'company' => $company, 'title' => $title,
        'phone' => $phone, 'photo' => $photo, 'approved' => $approved,
        'credits' => $credits, 'unread' => $unread
      ];
    }
  }
?>
