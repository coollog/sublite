<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  interface CompanyControllerInterface {
    // Process and clean up data from user or db.
    public static function data(array $data);
    // View list of companies.
    public static function viewAll();
    // Add a new company.
    public static function add();
    // Edit an existing company.
    public static function edit();
    // Display the company.
    public static function view();
  }

  class CompanyController extends Controller
                          implements CompanyControllerInterface {
    public static function data(array $data) {
      // List of keys in $data to use.
      $dataKeys = [
        'name', 'size', 'desc', 'founded', 'location', 'corevalues', 'funfacts',
        'society', 'socialevent', 'colorscheme', 'media', 'employees', 'perks',
        'forfun', 'dessert', 'talent', 'dresscode', 'freequestion1',
        'freeanswer1', 'freequestion2', 'freeanswer2'
      ];

      // Extract from $data the values to return.
      $retData = [];
      foreach ($dataKeys as $key) {
        $retData[$key] = clean($data[$key]);
      }

      // Perform key-specific actions.
      $industry = "";
      if (isset($data['industry'])) {
        $industry = $data['industry'];
        if (is_array($industry)) {
          $industry = clean(implode(', ', $industry));
        }
      }
      $retData['industry'] = $industry;

      $retData['bannerphoto'] =
        isset($data['bannerphoto']) ? clean($data['bannerphoto']) : "";

      $retData['logophoto'] =
        isset($data['logophoto']) ? clean($data['logophoto']) : "";

      $photos = [];
      if (isset($data['photos'])) {
        foreach ($data['photos'] as $photo) $photos[] = clean($photo);
      }
      $retData['photos'] = $photos;

      return $retData;
    }

    public static function viewAll() {
      self::displayMetatags('companyprofile');
      self::render('companies/list');
    }

    public static function add() {
      RecruiterController::requireLogin();

      function formData($data) {
        $data['industry'] = isset($data['industry']) ? explode(', ', $data['industry']) : array();
        return array_merge($data, [
          'headline' => 'Create',
          'submitname' => 'add', 'submitvalue' => 'Add Company'
        ]);
      }

      global $params;
      $me = RecruiterModel::me();
      $params['name'] = $me['company'];

      if (!isset($_POST['add'])) {
        self::render('companies/form', formData([
          'name' => $params['name']
        ]));
        return;
      }

      extract($data = self::data($params));

      // Validations
      self::startValidations();
      self::validate(!MongoId::isValid($me['company']), $err, 'company exists');
      self::validateData($data, $err);

      // Code
      if (self::isValid()) {
        $id = CompanyModel::save($data);
        $me['company'] = new MongoID($id);
        RecruiterModel::save($me);
        $_SESSION['company'] = $id;

        // Add credit for making company profile.
        $recruiterId = $_SESSION['_id'];
        RecruiterModel::addCreditsForNewCompanyProfile($recruiterId);

        self::redirect('home');
        return;
      }

      self::error($err);
      self::render('companies/form', formData($data));
    }

    public static function edit() {
      RecruiterController::requireLogin();

      global $params;
      // Params to vars
      $me = RecruiterModel::me();
      $id = $params['_id'] = $me['company'];
      if (!MongoId::isValid($me['company'])) self::redirect('addcompany');

      // Validations
      self::startValidations();
      self::validate(($entry = CompanyModel::get($me['company'])) !== NULL,
        $err, 'unknown company');

      // Code
      if (self::isValid()) {
        function formData(array $data) {
          $data['industry'] = explode(', ', $data['industry']);
          return array_merge($data, [
            'headline' => 'Edit',
            'submitname' => 'edit', 'submitvalue' => 'Save Company'
          ]);
        }

        if (!isset($_POST['edit'])) {
          self::render('companies/form', formData(
            array_merge(self::data($entry), [ '_id' => $id->{'$id'} ])
          ));
          return;
        }

        $params['name'] = $entry['name'];
        extract($data = self::data($params));
        // Validations
        self::validateData($data, $err);

        if (self::isValid()) {
          $data['_id'] = new MongoId($id);
          CompanyModel::save($data);
          self::success('company saved');
          self::render('companies/form', formData(
            array_merge($data, [ '_id' => $id->{'$id'} ])
          ));
          return;
        }

        self::error($err);
        self::render('companies/form', formData(
          array_merge(self::data($data), [ '_id' => $id->{'$id'} ])
        ));
        return;
      }

      self::error($err);
      self::render('notice');
    }

    public static function view() {
      // Validations
      self::startValidations();
      self::validate(isset($_GET['id']) and
        ($entry = CompanyModel::getById($id = new MongoId($_GET['id']))) != NULL,
        $err, 'unknown company');

      // Code
      if (self::isValid()) {
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

    private static function validateData(array $data, &$err) {
      $optionalQuestions = [
        'funfacts', 'society', 'socialevent', 'colorscheme',
        'media', 'employees', 'perks', 'forfun', 'dessert',
        'talent', 'dresscode', 'freequestion1', 'freequestion2'
      ];

      $answered = 0;
      foreach ($optionalQuestions as $key) {
        if (strlen($data[$key]) > 0) $answered ++;
      }

      self::validate(strlen($data['industry']) > 0,
        $err, 'must choose at least 1 industry');
      self::validate(strlen($data['bannerphoto']) > 0,
        $err, 'must upload banner image');
      self::validate(strlen($data['logophoto']) > 0,
        $err, 'must upload logo');
      self::validate(count($data['photos']) >= 4,
        $err, 'must upload at least 4 additional photos');
      self::validate($answered >= 6,
        $err, 'must answer at least 6 questions');
    }
  }
?>