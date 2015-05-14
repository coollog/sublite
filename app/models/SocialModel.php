<?php
  require_once($GLOBALS['dirpre'].'models/Model.php');

  class SocialModel extends Model {
    // The essentials
    function __construct() {
      parent::__construct('hubs');
    }
    function save($data) {
      $this->collection->save($data);
      return $data['_id']->{'$id'};
    }

    // Accessors
    function get($id) {
      return $this->collection->findOne(array('_id' => new MongoId($id)));
    }
    function getPostIndex($hub, $post) {
      $entry = $this->get($hub);
      $length = count($entry['posts']);
      for ($i = 0; $i < $length; $i++) {
        if($entry['posts'][$i]['id'] == $post) return $i;
      }
      return -1;
    }
    function getPostAuthor($hub, $post) {
      $index = $this->getPostIndex($hub, $post);
      $entry = $this->get($hub);
      return $entry['posts'][$index]['from'];
    }
    function getEvents($hub) {
      return $this->get($hub)['events'];
    }
    function getMembers($hub) {
      return $this->get($hub)['members'];
    }
    function isMember($hub, $student) {
      $entry = $this->get($hub);
      foreach ($entry['members'] as $key => & $sub_array) {
        if($sub_array['id'] == $student) return true;
      }
      return false;
    }

    // Modifiers
    function add($hub, $student) {
      $entry = $this->get($hub);
      $entry['members'][] = array('time' => time(), 'id' => $student);
      $this->save($entry, false);
      return $entry['members'];
    }
    function newPost($id, $hub, $content, $parentid) {
      $entry = $this->get($hub);
      $ret = array(
        'id' => new MongoId(),
        'parent' => $parentid,
        'children' => array(),
        'from' => $id,
        'date' => time(),
        'content' => $content,
        'likes' => array()
      );
      $entry['posts'][] = $ret;
      $this->save($entry, false);
      return $ret;
    }
    function likePost($hub, $post, $id) {
      $entry = $this->get($hub);
      $index = $this->getPostIndex($hub, $post);
      $entry['posts'][$index]['likes'][] = array('time' => time(), 'id' => $id);
      $this->save($entry, false);
      return "success"; //any suggestions on what to return?
    }
    function deletePost($hub, $post, $id) {
      $entry = $this->get($hub);
      $index = $this->getPostIndex($hub, $post);
      unset($entry['posts'][$index]);
      $this->save($entry, false);
      return "success"; //any suggestions on what to return?
    }
  }

  $MSocial = new SocialModel();
?>