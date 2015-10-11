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
              'dates' => ['start' => strtotime('August 2013')]
            ]
          ],
          'experience' => [
            [
              'title' => 'Intern',
              'company' => 'Random Company',
              'dates' => ['start' => strtotime('March 2017')]
            ]
          ],
          'extracurriculars' => [
            [
              'title' => 'Captain',
              'organization' => 'Random Club',
              'dates' => ['start' => strtotime('April 2012')]
            ]
          ],
          'awards' => [
            [
              'name' => 'random award'
            ]
          ],
          'projects' => [
            [
              'name' => 'Random Project'
            ]
          ]
        ];

        $studentId = new MongoId();
        $student = new StudentProfile($studentId, $data);

        EQ($studentId, $student->getStudentId());

        EQ(null, $student->getEducation(1));
        $education = $student->getEducation(0);
        $educationStart =
          $education['dates']['start']->toDateTime()->getTimeStamp();
        EQ(strtotime('August 2013'), $educationStart);
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
    ],
    'experience' => [
      '$arrayOf' => [
        '$required' => [
          'title' => true,
          'company' => true,
          'dates' => [
            '$required' => ['start' => true],
            '$optional' => ['end' => null]
          ],
        ],
        '$optional' => [
          'location' => null,
          'summary' => ''
        ]
      ]
    ],
    'extracurriculars' => [
      '$arrayOf' => [
        '$required' => [
          'title' => true,
          'organization' => true,
          'dates' => [
            '$required' => ['start' => true],
            '$optional' => ['end' => null]
          ],
        ],
        '$optional' => [
          'location' => null,
          'summary' => ''
        ]
      ]
    ],
    'awards' => [
      '$arrayOf' => [
        '$required' => [
          'name' => true
        ],
        '$optional' => [
          'by' => null,
          'date' => null,
          'place' => null,
          'summary' => ''
        ]
      ]
    ],
    'projects' => [
      '$arrayOf' => [
        '$required' => [
          'name' => true,
        ],
        '$optional' => [
          'summary' => '',
          'link' => null,
          'date' => null,
          'dates' => [
            '$required' => ['start' => true],
            '$optional' => ['end' => null]
          ]
        ]
      ]
    ]
  ]
]; -->