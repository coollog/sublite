<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');
  require_once($GLOBALS['dirpre'].'controllers/modules/application/Question.php');

  interface AdminControllerInterface {

  }

  interface AdminControllerQuestionsInterface {
    public static function manage();
  }

  class AdminController extends Controller implements AdminControllerInterface {
    protected static function ensureAdmin() {
      if (!checkAdmin()) {
        die('permission denied');
      }
    }
  }

  class AdminControllerQuestions extends AdminController
                                implements AdminControllerQuestionsInterface {
    public static function manage() {
      self::ensureAdmin();

      // Handle creation of vanilla questions.
      $createVanillaData = self::createVanilla();

      // Handle deletion of questions.
      self::delete();

      // Handle editing of questions.
      self::edit();

      // Load all questions.
      $questions = Question::getAll();
      $typeMap = mapDataArrayByField(
        $questions,
        function (Question $each) { return $each->getVanilla(); },
        [
          true => 'vanilla',
          false => 'custom'
        ]
      );

      $viewData = [
        'vanilla' => [],
        'custom' => []
      ];
      foreach ($typeMap['vanilla'] as $vanilla) {
        $viewData['vanilla'][] = $vanilla->getData();
      }
      foreach ($typeMap['custom'] as $custom) {
        $viewData['custom'][] = $custom->getData();
      }

      self::render('admin/questions', array_merge(
        $viewData,
        $createVanillaData
      ));
    }

    private static function createVanilla() {
      if (!isset($_POST['createVanilla'])) { return []; }

      global $params;
      // Params to vars
      $params['vanilla'] = true;
      extract($data = self::dataCreate($params));

      // Create the question.
      Question::createVanilla($text);

      self::success("vanilla question created: \"$text\"");
      return [];
    }
    private static function dataCreate(array $data) {
      $text = clean($data['text']);
      $vanilla = clean($data['vanilla']);
      return [
        'text' => $text,
        'vanilla' => $vanilla
      ];
    }

    private static function delete() {
      if (!isset($_POST['deleteQuestion'])) { return false; }

      global $params;
      extract($data = self::dataDelete($params));

      return Question::delete($_id) == 1;
    }
    private static function dataDelete(array $data) {
      $_id = clean($data['_id']);
      return [
        '_id' => new MongoId($_id)
      ];
    }

    private static function edit() {
      if (!isset($_POST['editQuestion'])) { return; }

      global $params;
      extract($data = self::dataEdit($params));

      if (Question::edit($_id, $text)) {
        self::success("question edited to: \"$text\"");
      } else {
        self::error("question with text \"$text\" already exists");
      }
    }
    private static function dataEdit(array $data) {
      $_id = clean($data['_id']);
      $text = clean($data['text']);
      return [
        '_id' => new MongoId($_id),
        'text' => $text
      ];
    }
  }
?>