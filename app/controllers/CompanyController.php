<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  interface CompanyControllerInterface {
    // View list of companies.
    public static function viewAll();
  }

  class CompanyController extends Controller
                          implements CompanyControllerInterface {
    public static function viewAll() {
      self::displayMetatags('companyprofile');
      self::render('companies/list');
    }

    function format($token) {
      return $token;
    }

    function data($data) {
      $name = self::format(clean($data['name']));
      if (isset($data['industry'])) {
        $industry = $data['industry'];
        if (is_array($industry)) {
          $industry = implode(', ', $industry);
          $industry = self::format(clean($industry));
        }
      } else {
        $industry = "";
      }
      $size = self::format(clean($data['size']));
      $desc = self::format(clean($data['desc']));
      $founded = self::format(clean($data['founded']));
      $location = self::format(clean($data['location']));
      $corevalues = self::format(clean($data['corevalues']));
      $bannerphoto = "";
      if(isset($data['bannerphoto'])) {
        $bannerphoto = self::format(clean($data['bannerphoto']));
      }
      $logophoto = "";
      if(isset($data['logophoto'])) {
        $logophoto = self::format(clean($data['logophoto']));
      }
      $photos = array();
      if (isset($data['photos'])) {
        foreach ($data['photos'] as $photo)
          $photos[] = self::format(clean($photo));
      }
      $funfacts = self::format(clean($data['funfacts']));
      $society = self::format(clean($data['society']));
      $socialevent = self::format(clean($data['socialevent']));
      $colorscheme = self::format(clean($data['colorscheme']));
      $media = self::format(clean($data['media']));
      $employees = self::format(clean($data['employees']));
      $perks = self::format(clean($data['perks']));
      $forfun = self::format(clean($data['forfun']));
      $dessert = self::format(clean($data['dessert']));
      $talent = self::format(clean($data['talent']));
      $dresscode = self::format(clean($data['dresscode']));
      $freequestion1 = self::format(clean($data['freequestion1']));
      $freeanswer1 = self::format(clean($data['freeanswer1']));
      $freequestion2 = self::format(clean($data['freequestion2']));
      $freeanswer2 = self::format(clean($data['freeanswer2']));
      return array(
        'name' => $name, 'industry' => $industry, 'size' => $size,
        'desc' => $desc, 'founded' => $founded, 'location' => $location,
        'corevalues' => $corevalues, 'bannerphoto' => $bannerphoto,
        'logophoto' => $logophoto, 'photos' => $photos,
        'funfacts' => $funfacts,
        'society' => $society, 'socialevent' => $socialevent,
        'colorscheme' => $colorscheme, 'media' => $media,
        'employees' => $employees, 'perks' => $perks, 'forfun' => $forfun,
        'dessert' => $dessert, 'talent' => $talent, 'dresscode' => $dresscode,
        'freequestion1' => $freequestion1, 'freeanswer1' => $freeanswer1,
        'freequestion2' => $freequestion2, 'freeanswer2' => $freeanswer2
      );
    }
    function validateData($data, &$err) {
      $answered = 0;
      foreach (array('funfacts', 'society', 'socialevent', 'colorscheme',
                     'media', 'employees', 'perks', 'forfun', 'dessert',
                     'talent', 'dresscode', 'freequestion1', 'freequestion2')
              as $key) {
        if (strlen($data[$key]) > 0) $answered ++;
      }
      $this->validate(strlen($data['industry']) > 0,
        $err, 'must choose at least 1 industry');
      $this->validate(strlen($data['bannerphoto']) > 0,
        $err, 'must upload banner image');
      $this->validate(strlen($data['logophoto']) > 0,
        $err, 'must upload logo');
      $this->validate(count($data['photos']) >= 4,
        $err, 'must upload at least 4 additional photos');
      $this->validate($answered >= 6,
        $err, 'must answer at least 6 questions');
    }

    function add() {
      function formData($data) {
        $data['industry'] = isset($data['industry']) ? explode(', ', $data['industry']) : array();
        return array_merge($data, array(
          'headline' => 'Create',
          'submitname' => 'add', 'submitvalue' => 'Add Company'));
      }

      global $CRecruiter; $CRecruiter->requireLogin();

      global $params, $MCompany, $MRecruiter;

      $me = $MRecruiter->me();
      if (!isset($_POST['add'])) {
        self::render('companies/form', formData(array(
          'name' => $me['company']))); return;
      }
      // Params to vars
      $params['name'] = $me['company'];
      extract($data = $this->data($params));

      // Validations
      $this->startValidations();
      $this->validate(!$this->exists(),
        $err, 'company exists');
      $this->validateData($data, $err);

      // Code
      if ($this->isValid()) {
        $id = $MCompany->save($data);
        $me = $MRecruiter->me();
        $me['company'] = new MongoID($id);
        $MRecruiter->save($me);
        $_SESSION['company'] = $id;

        // Add credit for making company profile.
        $recruiterId = $_SESSION['_id'];
        RecruiterModel::addCreditsForNewCompanyProfile($recruiterId);

        $this->redirect('home');
        return;
      }

      self::error($err);
      self::render('companies/form', formData($data));
    }

    function edit() { // FIX THIS ADD GET INFO LIKE DATA FROM VIEW AND STUFF
      global $CRecruiter; $CRecruiter->requireLogin();

      global $params, $MCompany, $MRecruiter;
      // Params to vars
      $me = $MRecruiter->me();
      $id = $params['_id'] = $me['company'];
      if (!MongoId::isValid($me['company'])) $this->redirect('addcompany');

      // Validations
      $this->startValidations();
      $this->validate(($entry = $MCompany->get($me['company'])) !== NULL,
        $err, 'unknown company');

      // Code
      if ($this->isValid()) {
        function formData($data) {
          $data['industry'] = explode(', ', $data['industry']);
          return array_merge($data, array(
            'headline' => 'Edit',
            'submitname' => 'edit', 'submitvalue' => 'Save Company'));
        }

        if (!isset($_POST['edit'])) {
          self::render('companies/form', formData(array_merge($this->data($entry), array('_id' => $id->{'$id'})))); return;
        }

        $params['name'] = $entry['name'];
        extract($data = $this->data($params));
        // Validations
        $this->validateData($data, $err);

        if ($this->isValid()) {
          $data['_id'] = new MongoId($id);
          $MCompany->save($data);
          $this->success('company saved');
          self::render('companies/form', formData(array_merge($data, array('_id' => $id->{'$id'}))));
          return;
        }

        self::error($err);
        self::render('companies/form', formData(array_merge($this->data($data), array('_id' => $id->{'$id'})))); return;
      }

      self::error($err);
      self::render('notice');
    }

    function view() {
      // Validations
      $this->startValidations();
      $this->validate(isset($_GET['id']) and
        ($entry = CompanyModel::getById($id = new MongoId($_GET['id']))) != NULL,
        $err, 'unknown company');

      // Code
      if ($this->isValid()) {
        $data = $entry;
        $me = RecruiterModel::me();

        $data['isme'] = !is_null($me) ? idcmp($id, $me['company']) : false;

        self::displayMetatags('companyprofile');
        self::render('companies/viewcompany', $data);
        return;
      }

      self::error($err);
      self::render('notice');
    }

    function exists() {
      global $MRecruiter;
      $me = $MRecruiter->me();
      return MongoId::isValid($me['company']);
    }
  }

  GLOBALvarSet('CCompany', new CompanyController());
?>