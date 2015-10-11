<?php
  /**
   * You can define a schema for a module's data using the following class.
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
   *   $optional fields can have types:
   *     'nullString' - string with default being not set
   *     'emptyString' - string with default value of ''
   *     'nullDate' - date with default being not set
   *     'arrayOfStrings'
   * or be another schema array to define a subdocument.
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
        foreach ($optional as $fieldName => $subScheme) {
          if (isset($inputData[$fieldName])) {
            if (is_array($subScheme)) {
              $myField = [];
              self::setDataFromSchema($myField,
                                      $inputData[$fieldName],
                                      $subScheme);
              $myData[$fieldName] = $myField;
            } else {
              $myData[$fieldName] =
                self::schemaProcessType($inputData[$fieldName]);
            }
          } else {
            $default = self::schemaGetDefault($subScheme);
            if (!is_null($default)) {
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
        case 'nullString': case 'nullDate': return null; break;
        case 'arrayOfStrings': return []; break;
        case 'emptyString': return ''; break;
        default:
          invariant(false,
                    "Schema::schemaGetDefault() received invalid type '$type'");
      }
    }

    private static function schemaProcessType($val, $type) {
      switch ($type) {
        case 'nullString': case 'string':
          return (string)$val;
        case 'nullDate': case 'date':
          return new MongoDate($val);
        case 'arrayOfStrings':
          $newVal = [];
          foreach ($val as $item) {
            $newVal[] = (string)$item;
          }
          return $newVal;
        default:
          invariant(
            false, "Schema::schemaProcessType() received invalid type '$type'");
      }
    }
  }
?>