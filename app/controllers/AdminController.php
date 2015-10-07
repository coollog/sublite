<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  interface AdminControllerInterface {

  }

  class AdminController extends Controller implements AdminControllerInterface {
    public static function manageQuestions() {
      self::ensureAdmin();

      // Question::

      self::render('admin/questions', array(
        '_id' => new MongoId(),
        'text' => 'asfdkaljkewjglkewjg',
        'recruiter' => null,
        'uses' => array(),
        'vanilla' => true
      ));
    }

    private static function ensureAdmin() {
      if (!checkAdmin()) {
        die('permission denied');
      }
    }
  }
?>