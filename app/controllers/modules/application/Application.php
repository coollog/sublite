<?php
  interface ApplicationInterface {
    public function getQuestions();
    public function getData();
    public function setQuestions();
  }

  class Application implements ApplicationInterface {
    /**
     * Builds a new set of question-answer pairs by taking $questions and using
     * only questions with id in $questionIds. Questions in $questionIds that
     * are not in $questions have an empty answer.
     */
    protected static function pruneQuestionsByIdSet($questions, $questionIds) {
      $questionSet = array();

      // Create a hash from _id to the _id-answer pair.
      $questionsHash = arrayToHashByKey($questions, '_id');

      // For all the ids in $questionIds, add an _id-answer pair to questionSet.
      foreach ($questionIds as $questionId) {
        if (isset($questionsHash[$questionId]) {
          $questionSet[] = $questionsHash[$questionId];
        } else {
          $questionSet[] = array('_id' => $questionId, 'answer' => '');
        }
      }

      return $questionSet;
    }

    //**********************
    // non-static functions
    //**********************

    public function getData() {
      return $this->data;
    }

    public function getQuestions() {
      return $this->data['questions'];
    }

    public function setQuestions(array $questions) {
      $this->data['questions'] = $questions;
    }
  }
?>