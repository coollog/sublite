<?php
  interface MessageModelInterface {
    public function __construct($test);

    public static function save(array $data);
    public static function add(array $participants);
    public static function reply(MongoId $messageId, $from, $msg);
    public static function findByParticipant($participant);
  }

  class MessageModel extends Model implements MessageModelInterface {
    const DB_TYPE = parent::DB_INTERNSHIPS;

    public function __construct($test=false) {
      parent::__construct(self::DB_TYPE, 'message', $test);

      // Create necessary indices.
    }

    public static function save(array $data) {
      self::$collection->save($data);
      return $data['_id']->{'$id'};
    }

    public static function add(array $participants) {
      $data = array(
        'participants' => $participants, 'replies' => []
      );
      return self::save($data);
    }

    public static function reply(MongoId $messageId, $from, $msg) {
      $message = self::getBymessageId($messageId);
      array_push($message['replies'], [
        'from' => $from, 'msg' => $msg, 'time' => time(), 'read' => false
      ]);
      self::save($message);
      return $message;
    }

    public static function findByParticipant($participant) {
      $query = (new DBQuery(self::$collection))
        ->toQuery('participants', $participant)
        ->toQuery('replies', ['$not' => ['$size' => 0]]);
      return $query->run();
    }

    protected static $collection;
  }

  new MessageModel();
?>