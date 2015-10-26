<?php
  require_once($GLOBALS['dirpre'].'controllers/modules/Schema.php');

  interface StudentProfileInterface {
    public static function createOrUpdate(MongoId $studentId,
                                          array $profileData);
    public static function getProfile(MongoId $studentId);

    public function __construct(MongoId $studentId, array $data);
    public function getData();
    public function getStudentId();
    public function getResume();
    public function getEducation($index);
  }

  class StudentProfile extends Schema implements StudentProfileInterface{
    public static function createOrUpdate(MongoId $studentId,
                                          array $profileData) {
      $profile = new StudentProfile($studentId, $profileData);
      StudentModel::setProfile($studentId, $profile->getData());
    }
    public static function getProfile(MongoId $studentId) {
      $profileData = StudentModel::getProfile($studentId);
      if ($profileData === null) return null;
      return new StudentProfile($studentId, $profileData);
    }

    //**********************
    // non-static functions
    //**********************

    public function __construct(MongoId $studentId, array $data) {
      $this->studentId = $studentId;
      $schema = [
        '$optional' => [
          'resume' => 'nullString',
          'bio' => 'emptyString',
          'interests' => 'arrayOfStrings',
          'skills' => 'arrayOfStrings',
          'phone' => 'nullString',
          'website' => 'nullString',
          'preferredname' => 'nullString',
        ],
        '$required' => [
          'education' => [
            '$arrayOf' => [
              '$required' => [
                'school' => 'string',
                'class' => 'string',
                'degree' => 'string',
                // 'dates.start' => 'date',
                // 'dates.end' => 'nullDate'
                'dates' => [
                  '$required' => ['start' => 'string'],
                  '$optional' => ['end' => 'nullString']
                ]
              ],
              '$optional' => [
                'majors' => 'arrayOfStrings',
                'minors' => 'arrayOfStrings',
                'gpa' => 'nullString',
                'courses' => 'arrayOfStrings'
              ]
            ]
          ],
          'experience' => [
            '$arrayOf' => [
              '$required' => [
                'title' => 'string',
                'company' => 'string',
                // 'dates.start' => 'date',
                // 'dates.end' => 'nullDate'
                'dates' => [
                  '$required' => ['start' => 'string'],
                  '$optional' => ['end' => 'nullString']
                ]
              ],
              '$optional' => [
                'location' => 'nullString',
                'summary' => 'emptyString'
              ]
            ]
          ],
          'extracurriculars' => [
            '$arrayOf' => [
              '$required' => [
                'title' => 'string',
                'organization' => 'string',
                // 'dates.start' => 'date',
                // 'dates.end' => 'nullDate'
                'dates' => [
                  '$required' => ['start' => 'string'],
                  '$optional' => ['end' => 'nullString']
                ]
              ],
              '$optional' => [
                'location' => 'nullString',
                'summary' => 'emptyString'
              ]
            ]
          ],
          'awards' => [
            '$arrayOf' => [
              '$required' => [
                'name' => 'string'
              ],
              '$optional' => [
                'by' => 'nullString',
                'date' => 'nullString',
                'place' => 'nullString',
                'summary' => 'emptyString'
              ]
            ]
          ],
          'projects' => [
            '$arrayOf' => [
              '$required' => [
                'name' => 'string',
              ],
              '$optional' => [
                'summary' => 'emptyString',
                'link' => 'nullString',
                // 'dates.start' => 'date',
                // 'dates.end' => 'nullDate'
                'dates' => [
                  '$required' => ['start' => 'string'],
                  '$optional' => ['end' => 'nullString']
                ]
              ]
            ]
          ]
        ]
      ];
      self::setDataFromSchema($this->data, $data, $schema);
    }

    public function getData() {
      return $this->data;
    }

    public function getStudentId() {
      return $this->studentId;
    }

    public function getResume() {
      return $this->data['resume'];
    }

    public function getEducation($index) {
      if (!isset($this->data['education'][$index])) return null;
      return $this->data['education'][$index];
    }

    private $studentId;
    private $data = [];
  }
?>