<?php
    require_once($GLOBALS['dirpre'].'controllers/Controller.php');

    interface StudentControllerAJAXInterface {
      /**
       * Calls made when viewing a job/sublet
       */
      public static function bookmark();
    }

    class StudentControllerAJAX
      extends StudentController
      implements StudentControllerAJAXInterface {

      // AJAX controller for students

      // Takes POST data from job/sublet page and adds it to student's bookmarks
      // $_POST will contain: id, type, title
      public static function bookmark() {
        self::requireLogin();

        global $params;

        $studentId = $_SESSION['_id'];
        $itemId = new MongoId($params['id']);
        $type = $params['type'];
        $title = $params['title'];

        StudentModel::bookmarkItem($studentId, $type, $itemId, $title);

        return self::ajaxSuccess();

      }

    }
?>
