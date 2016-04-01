<?php
    require_once($GLOBALS['dirpre'].'controllers/Controller.php');

    interface StudentControllerAJAXInterface {
      /**
       * Calls made when viewing a job/sublet
       */
      public static function createBookmark();
      public static function deleteBookmark();
      public static function getBookmarkedItems();
    }

    class StudentControllerAJAX
      extends StudentController
      implements StudentControllerAJAXInterface {

      // AJAX controller for students

      // Takes POST data from job/sublet page and adds it to student's bookmarks
      // $_POST will contain: id, type, title
      public static function createBookmark() {
        self::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] != 'POST' || empty($_POST)) {
            // invalid ajax request, fail
            return self::ajaxError();
        }

        $studentId = $_SESSION['_id'];
        $itemId = new MongoId($_POST['id']);
        $type = $_POST['type'];
        $title = $_POST['title'];
        StudentModel::createBookmark($studentId, $type, $itemId, $title);
        return self::ajaxSuccess();

      }

      public static function deleteBookmark() {
        self::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] != 'POST' || empty($_POST)) {
          // invalid ajax request, fail
          return self::ajaxError();
        }

        $studentId = $_SESSION['id'];
        $itemId = new MongoId($_POST['id']);
        $type = $_POST['type'];
        StudentModel::deleteBookmark($studentId, $type, $itemId);
        return self::ajaxSuccess();
      }

      public static function getBookmarkedItems() {
        self::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] != 'POST' || empty($_POST)) {
          // invalid ajax request fail
          return self::ajaxError();
        }

        $studentId = $_SESSION['id'];
        $type = $_POST['type'];
        $bookmarkedItems = StudentModel::getBookmarkedItems($studentId, $type);
        echo toJSON($bookmarkedItems);
      }

    }
?>
