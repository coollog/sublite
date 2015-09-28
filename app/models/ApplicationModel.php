<?php
  interface ApplicationModelInterface {
    public function __construct();
  }

  class ApplicationModel extends Model implements ApplicationModelInterface {
    const DB_TYPE = parent::DB_INTERNSHIPS;

    protected static $collection;

    public function __construct() {
      self::$collection = parent::__construct(self::DB_TYPE, 'jobs');
    }

  }
?>