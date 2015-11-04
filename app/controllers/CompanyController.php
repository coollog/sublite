<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  class CompanyController extends Controller {
    function format($token) {
      return $token;
    }

    function data($data) {
      $name = $this->format(clean($data['name']));
      if (isset($data['industry'])) {
        $industry = $data['industry'];
        if (is_array($industry)) {
          $industry = implode(', ', $industry);
          $industry = $this->format(clean($industry));
        }
      } else {
        $industry = "";
      }
      $size = $this->format(clean($data['size']));
      $desc = $this->format(clean($data['desc']));
      $founded = $this->format(clean($data['founded']));
      $location = $this->format(clean($data['location']));
      $corevalues = $this->format(clean($data['corevalues']));
      $bannerphoto = "";
      if(isset($data['bannerphoto'])) {
        $bannerphoto = $this->format(clean($data['bannerphoto']));
      }
      $logophoto = "";
      if(isset($data['logophoto'])) {
        $logophoto = $this->format(clean($data['logophoto']));
      }
      $photos = array();
      if (isset($data['photos'])) {
        foreach ($data['photos'] as $photo)
          $photos[] = $this->format(clean($photo));
      }
      $funfacts = $this->format(clean($data['funfacts']));
      $society = $this->format(clean($data['society']));
      $socialevent = $this->format(clean($data['socialevent']));
      $colorscheme = $this->format(clean($data['colorscheme']));
      $media = $this->format(clean($data['media']));
      $employees = $this->format(clean($data['employees']));
      $perks = $this->format(clean($data['perks']));
      $forfun = $this->format(clean($data['forfun']));
      $dessert = $this->format(clean($data['dessert']));
      $talent = $this->format(clean($data['talent']));
      $dresscode = $this->format(clean($data['dresscode']));
      $freequestion1 = $this->format(clean($data['freequestion1']));
      $freeanswer1 = $this->format(clean($data['freeanswer1']));
      $freequestion2 = $this->format(clean($data['freequestion2']));
      $freeanswer2 = $this->format(clean($data['freeanswer2']));
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
        $err, 'must answer at least 6 cultural questions');
    }

    function add() {
      function formData($data) {
        return array_merge($data, array(
          'headline' => 'Create',
          'submitname' => 'add', 'submitvalue' => 'Add Company'));
      }

      global $CRecruiter; $CRecruiter->requireLogin();

      global $params, $MCompany, $MRecruiter;

      $me = $MRecruiter->me();
      if (!isset($_POST['add'])) {
        $this->render('companies/form', formData(array(
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
        RecruiterModel::addCreditsForNewCompanyProfile();

        $this->redirect('home');
        return;
      }

      $this->error($err);
      $this->render('companies/form', formData($data));
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
          $this->render('companies/form', formData(array_merge($this->data($entry), array('_id' => $id->{'$id'})))); return;
        }

        $params['name'] = $entry['name'];
        extract($data = $this->data($params));
        // Validations
        $this->validateData($data, $err);

        if ($this->isValid()) {
          $data['_id'] = new MongoId($id);
          $MCompany->save($data);
          $this->success('company saved');
          $this->render('companies/form', formData(array_merge($data, array('_id' => $id->{'$id'}))));
          return;
        }

        $this->error($err);
        $this->render('companies/form', formData(array_merge($this->data($data), array('_id' => $id->{'$id'})))); return;
      }

      $this->error($err);
      $this->render('notice');
    }

    function view() {
      // global $CJob; $CJob->requireLogin();
      global $MCompany;
      global $MRecruiter;
      // Validations
      $this->startValidations();
      $this->validate(isset($_GET['id']) and
        ($entry = $MCompany->get($id = $_GET['id'])) != NULL,
        $err, 'unknown company');

      // Code
      if ($this->isValid()) {
        $data = $entry;
        $me = $MRecruiter->me();

        $data['isme'] = !is_null($me) ? idcmp($id, $me['company']) : false;

        $this->render('companies/viewcompany', $data);
        return;
      }

      $this->error($err);
      $this->render('notice');
    }

    function exists() {
      global $MRecruiter;
      $me = $MRecruiter->me();
      return MongoId::isValid($me['company']);
    }
  }

  GLOBALvarSet('CCompany', new CompanyController());
?>