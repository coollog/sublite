<?php
  /**
   * You can define a schema for a module's data using the following class.
   *
   * For a schema array:
   *   $optional defines fields that are optional
   *   $required defines fields that are required
   *   $arrayOf defines that the field has an array of documents of a subschema
   * Fields can either be a type:
   *   $required fields can have types:
   *     'string'
   *     'date'
   *     'arrayOfStrings'
   *     'bool'
   *     'id'
   *   $optional fields can have types:
   *     'nullString' - string with default being null
   *     'emptyString' - string with default value of ''
   *     'nullDate' - MongoDate with default being null
   *     'nullId' - MongoId with default value of null
   *     'notSetId' - MongoId with default being not set
   *     'arrayOfStrings'
   *     'arrayOfIds'
   * or be another schema array to define a subdocument.
   *
   * For example:
   *
   * $schema = [
   *   '$optional' => [
   *     'resume' => 'nullString',
   *     'bio' => 'emptyString',
   *     'interests' => 'arrayOfStrings',
   *     'dates' => [
   *       '$optional' => [
   *         'start' => 'date',
   *         'end' => 'nullDate'
   *       ]
   *     ]
   *   ],
   *   '$required' => [
   *     'education' => [
   *       '$arrayOf' => [
   *         '$required' => [
   *           'dates' => [
   *             '$required' => ['start' => 'date'],
   *             '$optional' => ['end' => 'nullDate']
   *           ],
   *         ]
   *       ]
   *     ]
   *   ]
   * ];
   */

  class Schema {
    /**
     * Sets $myData's fields based on $schema, and using data in $inputData.
     * Schema format:
     */
    protected static function setDataFromSchema(array &$myData,
                                                array $inputData,
                                                array $schema) {
      if (isset($schema['$arrayOf'])) {
        $subSchema = $schema['$arrayOf'];
        foreach ($inputData as $item) {
          $myItem = [];
          self::setDataFromSchema($myItem, $item, $subSchema);
          $myData[] = $myItem;
        }
        return;
      }

      if (isset($schema['$optional'])) {
        $optional = $schema['$optional'];
        foreach ($optional as $fieldName => $subSchema) {
          if (isset($inputData[$fieldName])) {
            if (is_array($subSchema)) {
              $myField = [];
              self::setDataFromSchema($myField,
                                      $inputData[$fieldName],
                                      $subSchema);
              $myData[$fieldName] = $myField;
            } else {
              $myData[$fieldName] =
                self::schemaProcessType($inputData[$fieldName], $subSchema);
            }
          } else {
            $default = self::schemaGetDefault($subSchema);
            if ($default != 'notSet') {
              $myData[$fieldName] = $default;
            }
          }
        }
      }
      if (isset($schema['$required'])) {
        $required = $schema['$required'];
        foreach ($required as $fieldName => $subSchema) {
          if (is_array($subSchema)) {
            $myField = [];
            if (isset($inputData[$fieldName])) {
              self::setDataFromSchema($myField,
                                      $inputData[$fieldName],
                                      $subSchema);
            }
            $myData[$fieldName] = $myField;
          } else {
            $myData[$fieldName] =
              self::schemaProcessType($inputData[$fieldName], $subSchema);
          }
        }
      }
    }

    private static function schemaGetDefault($type) {
      if (is_array($type)) {
        return null;
      }
      switch ($type) {
        case 'nullString':
        case 'nullDate':
        case 'nullId':
          return null; break;
        case 'notSetId':
          return 'notSet'; break;
        case 'arrayOfStrings':
        case 'arrayOfIds':
          return []; break;
        case 'emptyString':
          return ''; break;
        default:
          invariant(false,
                    "Schema::schemaGetDefault() received invalid type '$type'");
      }
    }

    private static function schemaProcessType($val, $type) {
      switch ($type) {
        case 'nullString': case 'emptyString': case 'string':
          return (string)$val;
        case 'nullDate': case 'date':
          return new MongoDate($val);
        case 'nullId': case 'id': case 'notSetId':
          return new MongoId($val);
        case 'bool':
          return boolval($val);
        case 'arrayOfStrings':
          $newVal = [];
          foreach ($val as $item) {
            $newVal[] = (string)$item;
          }
          return $newVal;
        case 'arrayOfIds':
          $newVal = [];
          foreach ($val as $item) {
            $newVal[] = new MongoId($item);
          }
          return $newVal;
        default:
          invariant(
            false, "Schema::schemaProcessType() received invalid type '$type'");
      }
    }
  }
?>