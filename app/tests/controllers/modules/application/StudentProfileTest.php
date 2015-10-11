<?php
  require_once($GLOBALS['dirpre'].'tests/TestFixture.php');
  require_once($GLOBALS['dirpre'].'controllers/modules/application/StudentProfile.php');

  class StudentProfileTest extends Test implements TestInterface {
    public static function run() {
      $class = get_called_class();

      TEST($class, "$class.construct", function($class) {
        $data = [
          'education' => [
            [
              'school' => 'Yale University',
              'class' => '2017',
              'degree' => 'BS',
              'dates' => ['start' => 'August 2013']
            ]
          ]
        ];

        $studentId = new MongoId();
        $student = new StudentProfile($studentId, $data);

        EQ($studentId, $student->getStudentId());

        var_dump($student->getData());
      });
    }

    public static function start() {
      // self::$MStudentProfileTest = new StudentProfileModel();
    }

    public static function end() {
      // self::$MStudentProfileTest = null;
    }

    // private static $MStudentProfileTest;
  }
?>

<!-- $schema = [
  '$optional' => [
    'resume' => null,
    'bio' => '',
    'interests' => []
  ],
  '$required' => [
    'education' => [
      '$arrayOf' => [
        '$required' => [
          'school' => true,
          'class' => true,
          'degree' => true,
          'dates' => [
            '$required' => ['start' => true],
            '$optional' => ['end' => null]
          ],
        ],
        '$optional' => [
          'majors' => [],
          'minors' => [],
          'gpa' => '',
          'courses' => []
        ]
      ]
    ]
  ]
]; -->