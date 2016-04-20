<?php
  require_once($GLOBALS['dirpre'].'models/Model.php');

  class MessageModel extends Model {
    const DB_TYPE = parent::DB_INTERNSHIPS;

    function __construct($test=false) {
      parent::__construct(self::DB_TYPE, 'message', $test);
    }

    function save($data) {
      self::$collection->save($data);
      return $data['_id']->{'$id'};
    }

    function add($participants) {
      $data = array(
        'participants' => $participants, 'replies' => array()
      );
      self::$collection->save($data);
      return $data['_id']->{'$id'};
    }

    function reply($id, $from, $msg) {
      $entry = $this->get($id);
      $currentTime = time();
      array_push($entry['replies'], array(
        'from' => $from, 'msg' => $msg, 'time' => $currentTime, 'read' => false
      ));
      foreach ($entry['participants'] as $participant) {
        if ($participant != $from) {
          $this->incrementUnread(new MongoId($participant));
        }
      }

      $entry['time'] = $currentTime;

      self::$collection->save($entry);
      return $entry;
    }

    function findByParticipant($participant) {
      return self::$collection->find(array(
        'participants' => $participant,
        'replies' => [ '$not' => [ '$size' => 0 ] ]
      ))->sort([ 'time' => 1 ]);
    }

    function getLastOf($id) {
      $entry = $this->get($id);
      return array_pop($entry);
    }

    function get($id) {
      return self::$collection->findOne(array('_id' => new MongoId($id)));
    }

    function exists($id) {
      return ($this->get($id) !== NULL);
    }

    function getNumUnread(MongoId $studentOrRecruiterId) {
      if (StudentModel::exists($studentOrRecruiterId)) {
        return StudentModel::getNumUnread($studentOrRecruiterId);
      } else if (RecruiterModel::IDexists($studentOrRecruiterId)) {
        return RecruiterModel::getNumUnread($studentOrRecruiterId);
      } else {
        invariant(false, 'Invalid student or recruiter');
      }
    }

    function incrementUnread(MongoId $studentOrRecruiterId) {
      if (StudentModel::exists($studentOrRecruiterId)) {
        StudentModel::incrementUnread($studentOrRecruiterId);
      } else if (RecruiterModel::IDexists($studentOrRecruiterId)) {
        RecruiterModel::incrementUnread($studentOrRecruiterId);
      } else {
        invariant(false, 'Invalid student or recruiter');
      }
    }

    function decrementUnread(MongoId $studentOrRecruiterId) {
      if (StudentModel::exists($studentOrRecruiterId)) {
        StudentModel::decrementUnread($studentOrRecruiterId);
      } else if (RecruiterModel::IDexists($studentOrRecruiterId)) {
        RecruiterModel::decrementUnread($studentOrRecruiterId);
      } else {
        invariant(false, 'Invalid student or recruiter');
      }
    }

    protected static $collection;
  }

  GLOBALvarSet('MMessage', new MessageModel());
?>