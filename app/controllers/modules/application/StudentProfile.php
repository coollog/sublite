<?php
  interface StudentProfileInterface {
    // $data is an associative array containing a subset of these keys:
    //
  }

  class StudentProfile {

    //**********************
    // non-static functions
    //**********************

    public function __construct(MongoId $studentId, array $data) {
      $this->studentId = $studentId;
      $schema = [
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
                'name' => true
                'dates' => [
                  '$optional' => [
                    'start' => null,
                    'end' => null
                  ]
                ],
              ],
              '$optional' => [
                'summary' => '',
                'link' => null
              ]
            ]
          ]
        ]
      ];
      $this->setDataFromSchema($this->data, $data, $schema);
    }

    public function getData() {
      return $this->data;
    }

    public function getStudentId() {
      return $this->studentId;
    }

    /**
     * Sets $myData's fields based on $schema, and using data in $inputData.
     * Schema format:
     */
    private function setDataFromSchema(array &$myData,
                                       array $inputData,
                                       array $schema) {
      if (isset($schema['$arrayOf'])) {
        $subSchema = $schema['$arrayOf'];
        foreach ($inputData as $item) {
          $myItem = [];
          $this->setDataFromSchema($myItem, $item, $subSchema);
          $myData[] = $myItem;
        }
        return;
      }

      if (isset($schema['$optional'])) {
        $optional = $schema['$optional'];
        foreach ($optional as $fieldName => $default) {
          $myData[$fieldName] =
            isset($inputData[$fieldName]) ? $inputData[$fieldName] : $default;
        }
      }
      if (isset($schema['$required'])) {
        $required = $schema['$required'];
        foreach ($required as $fieldName => $subSchema) {
          if (is_array($subSchema)) {
            $myField = [];
            $this->setDataFromSchema($myField, $inputData[$fieldName], $subSchema);
            $myData[$fieldName] = $myField;
          } else {
            $myData[$fieldName] = $inputData[$fieldName];
          }
        }
      }
    }

    private $studentId;
    private $data = [];
  }
?>