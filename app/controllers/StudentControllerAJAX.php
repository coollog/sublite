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

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            // invalid ajax request, redirect
            return self::ajaxError();
        }
        if (empty($_POST)) {
            return self::ajaxError();
        }

        $studentId = $_SESSION['_id'];
        $itemId = new MongoId($_POST['id']);
        $type = $_POST['type'];
        $title = $_POST['title'];


        StudentModel::bookmarkItem($studentId, $type, $itemId, $title);

        return self::ajaxSuccess();

      }

    }
?>
