<?php
  // Move jobs past deadline to jobsold.

  function pastDeadline($dateText) {
    return strtotime($dateText) < time();
  }

  class JobOldModel extends Model {
    const DB_TYPE = parent::DB_INTERNSHIPS;
    public function __construct() {
      parent::__construct(self::DB_TYPE, 'jobsold');
    }
    public static function save($data) {
      self::$collection->save($data);
    }
  }
  new JobOldModel();

  $jobs = JobModel::getAll();

  foreach ($jobs as $job) {
    if (pastDeadline($job['deadline'])) {
      $jobId = $job['_id'];
      JobOldModel::save($job);
      JobModel::deleteById($jobId);
    }
  }
?>