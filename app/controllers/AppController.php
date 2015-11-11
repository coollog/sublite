<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');
  require_once($GLOBALS['dirpre'].'controllers/modules/stats/Leaderboard.php');

  interface AppControllerInterface {
    public static function leaderboard();
  }

  class AppController extends Controller implements AppControllerInterface {
    public static function leaderboard() {
      $schools = array("Bentley University", "Eastern Michigan University");
      $counts = [7, 30, 90, 180];
      foreach ($counts as $count) {
        $schoolCount[$count] = Leaderboard::getSchoolCountDaysBefore($schools, $count);
      }
      $schoolCount[0] = Leaderboard::getSchoolCountSince($schools, 0);
      self::render('stats/leaderboard', [
        'schools' => $schools,
        'counts' => $schoolCount
      ]);
    }

    function faq() {
      $this->render('faq');
    }

    function privacy() {
      $this->render('privacy');
    }

    function terms() {
      $this->render('terms');
    }

    function team() {
      $this->render('team');
    }

    function data($data) {
      $name = clean($data['name']);
      $email = clean($data['email']);
      $feedback = clean($data['feedback']);
      $data = array(
        'name' => $name, 'email' => $email, 'feedback' => $feedback
      );
      return $data;
    }

    function feedback() {
      global $params;

      if (!isset($_POST['send'])) {
        $this->render('feedback');
        return;
      }

      extract($data = $this->data($params));

      $this->startValidations();
      $this->validate(filter_var($email, FILTER_VALIDATE_EMAIL),
        $err, 'invalid email');

      if ($this->isValid()) {
        $message = "
          <h1>Feedback Report</h1><br />
          <b>Name:</b> $name<br />
          <b>Email:</b> $email<br />
          <b>Feedback:</b><br /><br />$feedback
        ";
        sendgmail(array('tony.jiang@yale.edu', 'qingyang.chen@gmail.com'), "info@sublite.net", 'Feedback/Bug', $message);

        $this->success('Thank you for submitting your feedback report and helping SubLite improve. We will process the report as soon as possible!');
        $this->render('feedback', $data);
        return;
      }

      $this->error($err);
      $this->render('feedback', $data);
    }
  }

  GLOBALvarSet('CApp', new AppController());
?>