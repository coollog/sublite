<?php
  interface ApplicationModelInterface {
    public function __construct();
    public static function insert(array $data);
  }

  class ApplicationModel extends Model implements ApplicationModelInterface {
    const DB_TYPE = parent::DB_INTERNSHIPS;

    public function __construct() {
      static::$collection = parent::__construct(self::DB_TYPE, 'jobs');
    }
  }
?>