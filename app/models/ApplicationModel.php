<?php
  interface ApplicationModelInterface {
    public function __construct();

    /**
     * For $applicationId, sets 'submitted' to true, and 'profile' to
     * '$studentProfile'.
     */
    public static function submitWithStudentProfile(
      MongoId $applicationId, StudentProfile $studentProfile);

    /**
     * Sets 'submitted' for $applicationId to true.
     */
    public static function markAsSubmitted(MongoId $applicationId);

    /**
     * Checks whether $applicationId is marked as submitted.
     */
    public static function checkSubmitted(MongoId $applicationId);

    /**
     * Set the job document's application field to $data.
     */
    public static function setJobApplication(MongoId $jobId, array $data);

    /**
     * Get the job document's application field, or [].
     */
    public static function getJobApplication(MongoId $jobId);

    public static function hasJobApplication(MongoId $jobId);

    /**
     * Retrieves all the saved applications for $jobId.
     */
    public static function getSavedForJob(MongoId $jobId);

    /**
     * Replace the questions field for $applicationId.
     */
    public static function replaceQuestionsField(MongoId $applicationId,
                                                 array $newQuestions);

    /**
     * Unclaimed means 'status' is unclaimed and 'submitted' is true.
     */
    public static function getUnclaimed(MongoId $jobId);
    public static function getClaimed(MongoId $jobId);

    public static function countRecruiterUnclaimed(MongoId $recruiterId);

    /**
     * Gets all submitted applications by $jobId, but project only 'status'.
     */
    public static function getStatusesByJob(MongoId $jobId);

    /**
     * Checks whether the student has submitted or saved an application for the
     * job.
     */
    public static function applicationExists(MongoId $jobId,
                                             MongoId $studentId);

    /**
     * Gets application given a jobId and studentId.
     */
    public static function getApplication(MongoId $jobId, MongoId $studentId);

    /**
     * Gets all applications for a given student.
     */
    public static function getApplicationsByStudent(MongoId $studentId);

    /**
     * Changes the 'status' of the application.
     */
    public static function changeStatus(MongoId $applicationId, $status);

    /**
     * Checks if the application is owned by the recruiter.
     */
    public static function isOwned(MongoId $recruiterId,
                                   MongoId $applicationId);

    /**
     * Changes 'status' of $count applications under $jobId to 'review'.
     */
    public static function claim(MongoId $jobId, $count);

    /**
    * Gets the number of applications for a certain job, in an array, with
    * total and all statuses as keys and the counts as values.
    */
    public static function countByJob(MongoId $jobId);

    public static function deleteByJob(MongoId $jobId);
  }

  class ApplicationModel extends Model implements ApplicationModelInterface {
    const DB_TYPE = parent::DB_INTERNSHIPS;

    public function __construct() {
      parent::__construct(self::DB_TYPE, 'applications');

      // Create necessary indices.
      mongo_ok(self::$collection->createIndex(array('jobid' => 1)));
      mongo_ok(self::$collection->createIndex(array('jobid' => 1,
                                                    'submitted' => 1)));
      mongo_ok(self::$collection->createIndex(array('jobid' => 1,
                                                    'status' => 1)));
      mongo_ok(self::$collection->createIndex(array('jobid' => 1,
                                                    'status' => 1,
                                                    'submitted' => 1)));
      mongo_ok(self::$collection->createIndex(array('jobid' => 1,
                                                    'studentid' => 1)));
      mongo_ok(self::$collection->createIndex(array('studentid' => 1)));
    }

    public static function submitWithStudentProfile(
      MongoId $applicationId, StudentProfile $studentProfile) {
      $update = (new DBUpdateQuery(self::$collection))
        ->queryForId($applicationId)
        ->toUpdate('submitted', true)
        ->toUpdate('profile', $studentProfile->getData());
      $update->run();
    }

    public static function markAsSubmitted(MongoId $applicationId) {
      $update = (new DBUpdateQuery(self::$collection))
        ->queryForId($applicationId)
        ->toUpdate('submitted', true);
      $update->run();
    }

    public static function markAsUnSubmitted(MongoId $applicationId) {
      $update = (new DBUpdateQuery(self::$collection))
        ->queryForId($applicationId)
        ->toUpdate('submitted', false);
      $update->run();
    }

    public static function checkSubmitted(MongoId $applicationId) {
      $query = (new DBQuery(self::$collection))->queryForId($applicationId);
      return $query->findOne()['submitted'];
    }

    public static function setJobApplication(MongoId $jobId, array $data) {
      // Check job exists.
      if (!self::jobExists($jobId)) return false;

      JobModel::setApplicationQuestionIds($jobId, $data);
      return true;
    }

    public static function getJobApplication(MongoId $jobId) {
      // Check job exists.
      if (!self::jobExists($jobId)) return [];

      return JobModel::getApplicationQuestionIds($jobId);
    }

    public static function hasJobApplication(MongoId $jobId) {
      return self::getJobApplication($jobId) !== null;
    }

    public static function getSavedForJob(MongoId $jobId) {
      // Check job exists.
      if (!self::jobExists($jobId)) return null;

      $query = (new DBQuery(self::$collection))
        ->toQuery('jobid', $jobId)->toQuery('submitted', false);
      return $query->run();
    }

    public static function replaceQuestionsField(MongoId $applicationId,
                                                 array $newQuestions) {
      $update = (new DBUpdateQuery(self::$collection))
        ->queryForId($applicationId)
        ->toUpdate('questions', $newQuestions);
      $update->run();
    }

    public static function getUnclaimed(MongoId $jobId) {
      // Check job exists.
      if (!self::jobExists($jobId)) return null;

      return self::queryUnclaimed($jobId);
    }

    public static function getClaimed(MongoId $jobId) {
      // Check job exists.
      if (!self::jobExists($jobId)) return null;

      $query = (new DBQuery(self::$collection))
        ->toQuery('jobid', $jobId)
        ->toNotQuery('status', ApplicationStudent::STATUS_UNCLAIMED);
      return $query->run();
    }

    public static function countRecruiterUnclaimed(MongoId $recruiterId){
      $jobs = JobModel::getByRecruiter($recruiterId, [ '_id' => 1 ]);
      $num = 0;
      foreach ($jobs as $job) {
        $num += count(self::queryUnclaimed($job['_id']));
      }
      return $num;
    }

    public static function getStatusesByJob(MongoId $jobId) {
      $query = (new DBQuery(self::$collection))
        ->toQuery('jobid', $jobId)
        ->toQuery('submitted', true)
        ->projectField('status');
      return $query->run();
    }

    public static function applicationExists(MongoId $jobId,
                                             MongoId $studentId) {
      $query = (new DBQuery(self::$collection))
        ->toQuery('jobid', $jobId)
        ->toQuery('studentid', $studentId)
        ->projectId();
      return $query->findOne() !== null;
    }

    public static function getApplication(MongoId $jobId, MongoId $studentId) {
      $query = (new DBQuery(self::$collection))
        ->toQuery('jobid', $jobId)
        ->toQuery('studentid', $studentId);
      return $query->findOne();
    }

    public static function getApplicationsByStudent(MongoId $studentId) {
      $query = (new DBQuery(self::$collection))
        ->toQuery('studentid', $studentId);
      return $query->run();
    }

    public static function changeStatus(MongoId $applicationId, $status) {
      $update = (new DBUpdateQuery(self::$collection))
        ->queryForId($applicationId)
        ->toUpdate('status', $status);
      $update->run();
    }

    public static function isOwned(MongoId $recruiterId,
                                   MongoId $applicationId) {
      $jobId = self::getJob($applicationId);
      return JobModel::matchJobRecruiter($jobId, $recruiterId);
    }

    public static function claim(MongoId $jobId, $count) {
      $query = (new DBQuery(self::$collection))
        ->toQuery('jobid', $jobId)
        ->toQuery('status', ApplicationStudent::STATUS_UNCLAIMED)
        ->toQuery('submitted', true)
        ->projectId()
        ->limit($count);
      $applications = $query->run();
      foreach ($applications as $application) {
        $applicationId = $application['_id'];
        self::changeStatus($applicationId, ApplicationStudent::STATUS_REVIEW);
      }
    }

    public static function countByJob(MongoId $jobId) {
      $query = (new DBQuery(self::$collection))
        ->toQuery('jobid', $jobId)
        ->projectField('status')
        ->projectField('submitted');
      $applications = $query->run();
      $applicationCountData = [
        "total" => count($applications),
        "submitted" => 0,
        "unclaimed" => 0,
        "review" => 0,
        "rejected" => 0,
        "accepted" => 0,
        "reported" => 0
      ];
      foreach ($applications as $app) {
        // Count each type of application.
        if (!$app['submitted']) continue;

        $applicationCountData['submitted'] ++;

        switch ($app['status']) {
          case ApplicationStudent::STATUS_UNCLAIMED:
            $applicationCountData['unclaimed'] ++;
            break;
          case ApplicationStudent::STATUS_REVIEW:
            $applicationCountData['review'] ++;
            break;
          case ApplicationStudent::STATUS_REJECTED:
            $applicationCountData['rejected'] ++;
            break;
          case ApplicationStudent::STATUS_ACCEPTED:
            $applicationCountData['accepted'] ++;
            break;
          case ApplicationStudent::STATUS_REPORTED:
            $applicationCountData['reported'] ++;
            break;
        }
      }
      return $applicationCountData;
    }

    public static function deleteByJob(MongoId $jobId) {
      (new DBRemoveQuery(self::$collection))->toQuery('jobId', $jobId)->run();
    }

    private static function jobExists(MongoId $id) {
      return JobModel::exists($id);
    }

    private static function getJob(MongoId $applicationId) {
      $query = (new DBQuery(self::$collection))
        ->queryForId($applicationId)
        ->projectField('jobid');
      $application = $query->findOne();
      return $application['jobid'];
    }

    private static function queryUnclaimed(MongoId $jobId) {
      $query = (new DBQuery(self::$collection))
        ->toQuery('jobid', $jobId)
        ->toQuery('status', ApplicationStudent::STATUS_UNCLAIMED)
        ->toQuery('submitted', true);
      return $query->run();
    }

    protected static $collection;
  }

  new ApplicationModel();
?>
