<?php
  require_once($GLOBALS['dirpre'].'tests/TestFixture.php');
  require_once($GLOBALS['dirpre'].'controllers/modules/application/Question.php');

  class QuestionTest extends Test implements TestInterface {
    public static function run() {
      $class = get_called_class();

      TEST($class, "$class.parseRawData", function ($class) {
        $data1 = array(
          '_id' => new MongoId(),
          'text' => 'randomtext',
          'recruiter' => new MongoId(),
          'uses' => array(new MongoId(), new MongoId()),
          'vanilla' => true
        );

        $data2 = array(
          '_id' => new MongoId(),
          'text' => 'randomtext2',
          'recruiter' => new MongoId(),
          'uses' => array(new MongoId(), new MongoId()),
          'vanilla' => false
        );

        $dataList = array(
          $data1,
          $data2
        );

        $res = $class::callPrivateMethod('Question', 'parseRawData', $dataList);
        EQ(count($res), 2);

        $q1 = $res[0];
        $q2 = $res[1];
        EQ($q1->getData(), $data1);
        EQ($q2->getData(), $data2);
      });

      TEST($class, "$class.createVanilla", function() {
        $text = 'random text haha';

        $question = Question::createVanilla($text);

        EQ($text, $question->getText(), "Wrong text");
        EQ(null, $question->getRecruiter(), "Recruiter should be null");
        EQ(true, $question->getVanilla(), "Not vanilla");
        NEQ(null, $question->getId(), "_id not null");

        $vanillas = Question::getAllVanilla();
        EQ(count($vanillas), 1, "Wrong vanilla count");

        $question2 = $vanillas[0];
        EQ($question->getData(), $question2->getData(), "Data doesn't match");
      });

      TEST($class, "$class.createCustom", function() {
        $question = Question::createCustom('randomtext', new MongoId());
        $id = $question->getId();

        $vanillas = Question::getAllVanilla();
        EQ(count($vanillas), 0, "Wrong vanilla count");

        $res = Question::getById($id);

        NEQ($res, null);
      });

      TEST($class, "$class.searchCustom", function() {
        $text1 = 'Give an example of where you\'ve been able to use your ' .
                 'leadership skills.';
        $text2 = 'Where do you see yourself in five years?';
        $text3 = 'What are your salary expectations?';
        $text4 = 'What are your strengths and weaknesses?';

        $id = [
          Question::createCustom($text1, new MongoId())->getId(),
          Question::createCustom($text2, new MongoId())->getId(),
          Question::createCustom($text3, new MongoId())->getId(),
          Question::createCustom($text4, new MongoId())->getId()
        ];

        $res = Question::searchCustom('example years salary strength');
        EQ(4, count($res));

        $res = Question::searchCustom('strengths and weaknesses skills');
        EQ($id[3], $res[0]->getId());

        $res = Question::searchCustom('leadership skills weaknesses');
        EQ($id[0], $res[0]->getId());
      });
    }

    public static function start() {
      self::$MQuestionTest = new QuestionModel();
    }

    public static function end() {
      self::$MQuestionTest = null;
    }

    private static $MQuestionTest;
  }
?>