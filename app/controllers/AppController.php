<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');
  require_once($GLOBALS['dirpre'].'controllers/modules/stats/Leaderboard.php');

  interface AppControllerInterface {
    public static function leaderboard();

    public static function faq();
    public static function privacy();
    public static function terms();
    public static function team();

    public static function feedback();
  }

  class AppController extends Controller implements AppControllerInterface {
    public static function leaderboard() {
      $schools = [
        "Columbia College Chicago",
        "Hartford University",
        "New York University",
        "University of California Berkeley",
        "University of Virginia"
      ];
      $ambassadors = [
        "Elana",
        "Stephen",
        "Solomon",
        "Helena, Jiang",
        "David"
      ];
      $countAdjustments = [
        "Columbia College Chicago" => 5,
        "Hartford University" => 1,
        "New York University" => 249,
        "University of California Berkeley" => 93,
        "University of Virginia" => 39
      ];
      $counts = [ 7, 30, 90, 180 ];

      // 0 and last monday
      $lastMonday = strtotime("last Monday", strtotime("+12 hours")) + 43200;
      $times = [ 0, $lastMonday ];
      $currentTime = time();
      foreach ($counts as $count) {
        array_push($times, $currentTime - ($count * 86400));
      }
      $schoolCount = Leaderboard::getSchoolCountSinceTimes($schools, $times);

      // Rename keys
      $schoolCount[1] = $schoolCount[$lastMonday];
      unset($schoolCount[$lastMonday]);
      foreach ($counts as $count) {
        $schoolCount[$count] = $schoolCount[$currentTime - ($count * 86400)];
        unset($schoolCount[$currentTime - ($count * 86400)]);
      }

      // Adjust counts
      foreach ($countAdjustments as $school => $adjustment) {
        $schoolCount[0][$school] =  $schoolCount[0][$school] - $adjustment;
      }

      self::render('stats/leaderboard', [
        'schools' => $schools,
        'counts' => $schoolCount,
        'ambassadors' => $ambassadors
      ]);
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

    public static function team() {
      self::render('team');
    }

    public static function feedback() {
      function data($data) {
        $name = clean($data['name']);
        $email = clean($data['email']);
        $feedback = clean($data['feedback']);
        $data = [ 'name' => $name, 'email' => $email, 'feedback' => $feedback ];
        return $data;
      }

      global $params;

      if (!isset($_POST['send'])) {
        self::render('feedback');
        return;
      }

      extract($data = data($params));

      self::startValidations();
      self::validate(filter_var($email, FILTER_VALIDATE_EMAIL),
        $err, 'invalid email');

      if (self::isValid()) {
        $message = "
          <h1>Feedback Report</h1><br />
          <b>Name:</b> $name<br />
          <b>Email:</b> $email<br />
          <b>Feedback:</b><br /><br />$feedback
        ";
        sendgmail(array('tony.jiang@yale.edu', 'qingyang.chen@gmail.com'), "info@sublite.net", 'Feedback/Bug', $message);

        self::success('Thank you for submitting your feedback report and helping SubLite improve. We will process the report as soon as possible!');
        self::render('feedback', $data);
        return;
      }

      self::error($err);
      self::render('feedback', $data);
    }
  }
?>