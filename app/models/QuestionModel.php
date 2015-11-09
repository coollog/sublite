<?php
  interface QuestionModelInterface {
    public function __construct();
    public static function getAllVanilla();
    public static function getAll();
    public static function getCustomByText($text);
    public static function getByExactText($text);
    public static function exists(MongoId $id);
    public static function editText(MongoId $id, $text);

    /**
     * Adds the 'jobid' to uses.
     */
    public static function addToUses(MongoId $questionId, MongoId $jobId);

    /**
     * Removes the 'jobid' from uses, and deletes if is not vanilla.
     */
    public static function removeFromUses(MongoId $questionId, MongoId $jobId);
  }

  class QuestionModel extends Model implements QuestionModelInterface {
    const DB_TYPE = parent::DB_INTERNSHIPS;

    public function __construct() {
      parent::__construct(self::DB_TYPE, 'questions');

      // Create necessary indices.
      mongo_ok(self::$collection->createIndex(array('text' => 'text')));
    }

    public static function getAllVanilla($justText = false) {
      self::checkReady();

      $query = (new DBQuery(self::$collection))->toQuery('vanilla', true);
      if ($justText) {
        $query->projectField('text');
      }
      return $query->run();
    }

    public static function getAll() {
      self::checkReady();

      $query = (new DBQuery(self::$collection));
      return $query->run();
    }

    public static function getCustomByText($text) {
      self::checkReady();

      return (new DBQuery(self::$collection))
        ->toQuery('vanilla', false)->textSearch($text);
    }

    public static function getByExactText($text) {
      self::checkReady();

      $query = (new DBQuery(self::$collection))->toQuery('text', $text);
      return $query->findOne();
    }

    public static function exists(MongoId $id) {
      self::checkReady();

      $query = (new DBQuery(self::$collection))->queryForId($id)->justId();
      return $query->run();
    }

    public static function editText(MongoId $id, $text) {
      $update = (new DBUpdateQuery(self::$collection))
        ->queryForId($id)->toUpdate('text', $text);
      $update->run();
    }

    public static function addToUses(MongoId $questionId, MongoId $jobId) {
      $update = (new DBUpdateQuery(self::$collection))
        ->queryForId($questionId)->toPush('uses', $jobId);
      $update->run();
    }

    public static function removeFromUses(MongoId $questionId, MongoId $jobId) {
      $update = (new DBUpdateQuery(self::$collection))
        ->queryForId($questionId)->toPull('uses', $jobId);
      $update->run();

      $question = self::getById($questionId, ['uses' => 1, 'vanilla' => 1]);
      if (count($question['uses']) == 0 && !$question['vanilla']) {
        self::deleteById($questionId);
      }
    }

    protected static $collection;
  }

  new QuestionModel();
?>