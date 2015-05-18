<?php
  require_once($GLOBALS['dirpre'].'models/Model.php');

  class SocialModel extends Model {
    // The essentials
    function __construct() {
      $m = new MongoClient($GLOBALS['dburistudent']);
      $db = $m->$GLOBALS['dbnamestudent'];
      $this->collection = $db->hubs;
    }
    function save($data) {
      $this->collection->save($data);
      return $data['_id']->{'$id'};
    }

    // Accessors
    function get($id) {
      return $this->collection->findOne(array('_id' => new MongoId($id)));
    }
    function processPost($post) {
      global $MStudent;
      $student = $MStudent->getById($post['from']);
      $post['pic'] = isset($student['photo']) ? $student['photo'] :
        $GLOBALS['dirpre'].'assets/gfx/defaultpic.png';
      $post['name'] = $student['name'];
      $post['date'] = timeAgo($post['date']);
      $post['id'] = $post['id']->{'$id'};
      if ($post['parent'] != '')
        $post['parent'] = $post['parent']->{'$id'};

      return $post;
    }
    function getPosts($hub, $parent, $sortCriterion) {
      $ret = array();
      $thishub = $this->get($hub);
      $posts = $thishub['posts'];
      if ($parent == '') {
        foreach ($posts as $post) {
          if ($post['parent'] == '' and $post['deleted'] == false) {
            $post['children'] = $this->getPosts($hub, $post['id'], $sortCriterion);
            $ret[] = $this->processPost($post);
          }
        }
      } else {
        $cur = $this->getPost($hub, $parent);
        foreach ($cur['children'] as $post) {
          $ret[] = $this->getPost($hub, $post);
        }
      }
      if ($sortCriterion == 'recent') {
        $ret = array_reverse($ret);
      }
      else {
        //TODO sort by popular and uncomment below
        $ret = array_reverse($ret);
      }
      foreach ($ret as $key => $post) {
        $ret[$key]['replies'] = $this->getPosts($hub, $post['id'], $sortCriterion);
      }
      return $ret;
    }
    function getPost($hub, $postid) {
      $thishub = $this->get($hub);
      $posts = $thishub['posts'];
      foreach ($posts as $post) {
        if ($post['id'] == $postid && $post['deleted'] == false) {
          return $this->processPost($post);
        }
      }
      return '-1';
    }
    function getPostIndex($hub, $post) {
      $entry = $this->get($hub);
      if(!$this->validArray($entry['posts'])) return -1;
      foreach ($entry['posts'] as $key => $tmp) {
        if ($tmp['id'] == $post && $tmp['deleted'] == false) return $key;
      }
      return -1;
    }
    function getPostAuthor($hub, $post) {
      $index = $this->getPostIndex($hub, $post);
      $entry = $this->get($hub);
      return $entry['posts'][$index]['from'];
    }
    function getEvents($hub) {
      $thishub = $this->get($hub);
      return $thishub['events'];
    }
    function getEventIndex($hub, $event) {
      $entry = $this->get($hub);
      if(!$this->validArray($entry['events'])) return -1;
      foreach ($entry['events'] as $key => $tmp) {
        if ($tmp['id'] == $event) return $key;
      }
      return -1;
    }
    function getEventComment($hub, $event, $comment) {
      $index = $this->getEventIndex($hub, $event);
      $thishub = $this->get($hub);
      $comments = $thishub['events'][$index]['comments'];
      foreach ($comments as $tmp) {
        if ($tmp['id'] == $comment) return $tmp;
      }
      return '-1';
    }

    function getEventCreator($hub, $event) {
      $index = $this->getEventIndex($hub, $event);
      $entry = $this->get($hub);
      return $entry['events'][$index]['creator'];
    }
    function getEventAttendees($hub, $event) {
      $index = $this->getEventIndex($hub, $event);
      $entry = $this->get($hub);
      return $entry['events'][$index]['going'];
    }
    function getEventDescription($hub, $event) {
      $index = $this->getEventIndex($hub, $event);
      $entry = $this->get($hub);
      return $entry['events'][$index]['description'];
    }
    function getEventCommentIndex($hub, $event, $comment) {
      $entry = $this->get($hub);
      $index = $this->getEventIndex($hub, $event);
      if(!$this->validArray($entry['events'][$index]['comments'])) return -1;
      foreach ($entry['events'][$index]['comments'] as $key => $tmp) {
        if ($tmp['id'] == $comment) return $key;
      }
      return -1;
    }
    function getEventComments($hub, $event) {
      $index = $this->getEventIndex($hub, $event);
      $entry = $this->get($hub);
      return $entry['events'][$index]['comments'];
    }
    function getMembers($hub) {
      $thishub = $this->get($hub);
      return $thishub['members'];
    }
    function isGoing($hub, $event, $student) {
      $entry = $this->get($hub);
      $index = $this->getEventIndex($hub, $event);
      if(is_array($entry['events'][$index]['going']) && count($entry['events'][$index]['going']) > 0) {
        foreach ($entry['events'][$index]['going'] as $key => & $sub_array) {
          if($sub_array['id'] == $student) return true;
        }
      }
      return false;
    }
    function isMember($hub, $student) {
      $entry = $this->get($hub);
      foreach ($entry['members'] as $key => & $sub_array) {
        if($sub_array['id'] == $student) return true;
      }
      return false;
    }

    // Modifiers
    function joinHub($hub, $student) {
      $entry = $this->get($hub);
      $entry['members'][] = array('time' => time(), 'id' => $student);
      $this->save($entry, false);
      return $entry['members'];
    }
    function newPost($id, $hub, $content, $parentid) {
      $entry = $this->get($hub);
      $curId = new MongoId();
      if ($parentid != '') $parentid = new MongoId($parentid);
      $ret = array(
        'id' => $curId,
        'parent' => $parentid,
        'children' => array(),
        'from' => $id,
        'date' => time(),
        'content' => $content,
        'likes' => array(),
        'deleted' => false
      );
      $entry['posts'][] = $ret;
      if($parentid != '') {
        $parentindex = $this->getPostIndex($hub, $parentid);
        $entry['posts'][$parentindex]['children'][] = $curId;
      }
      $this->save($entry, false);

      return $this->getPost($hub, $curId);
    }
    function toggleLikePost($hub, $post, $id) {
      $entry = $this->get($hub);
      $index = $this->getPostIndex($hub, $post);
      //Checks if post is already liked
      if($this->validArray($entry['posts'][$index]['likes'])) {
        foreach ($entry['posts'][$index]['likes'] as $key => $value) {
          if ($value['id'] == $id) {
            unset($entry['posts'][$index]['likes'][$key]);
            $this->save($entry, false);
            return "post $post unliked";
          }
        }
      }
      $entry['posts'][$index]['likes'][] = array('time' => time(), 'id' => $id);
      $this->save($entry, false);
      return "post $post liked";
    }
    function deletePost($hub, $post) {
      $entry = $this->get($hub);
      $index = $this->getPostIndex($hub, $post);
      $ret = $entry['posts'][$index];
      // delete from parent's children list
      if ($ret['parent'] != '') {
        $parentindex = $this->getPostIndex($hub, $ret['parent']);
        if($parentindex != -1) {
          foreach ($entry['posts'][$parentindex]['children'] as $key => $val) {
            if($val == $post) {
              unset($entry['posts'][$parentindex]['children'][$key]);
              break;
            }
          }
        }
      }
      // delete the actual post
      $entry['posts'][$index]['deleted'] = true;
      $this->save($entry, false);

      // delete all children
      if (is_array($ret['children']) && count($ret['children']) > 0) {
        foreach ($ret['children'] as $child) {
          $this->deletePost($hub, $child->{'$id'});
        }
      }
      return $ret;
    }
    function createEvent($id, $hub, $title, $start, $end, $location, $address, $geocode, $description) {
      $entry = $this->get($hub);
      $ret = array(
        'id' => new MongoId(),
        'creator' => $id,
        'title' => $title,
        'starttime' => $start,
        'endtime' => $end,
        'location' => $location,
        'address' => $address,
        'geocode' => $geocode,
        'going' => array($id),
        'comments' => array(),
        'description' => $description
      );
      $entry['events'][] = $ret;
      $this->save($entry, false);
      return $ret;
    }
    function deleteEvent($hub, $event) {
      $entry = $this->get($hub);
      $index = $this->getEventIndex($hub, $event);
      $ret = $entry['events'][$index];
      unset($entry['events'][$index]);
      $this->save($entry, false);
      return $ret;
    }
    function joinEvent($id, $hub, $event) {
      $entry = $this->get($hub);
      $index = $this->getEventIndex($hub, $event);
      $ret = array('date' => time(), 'id' => $id);
      $entry['events'][$index]['going'][] = $ret;
      $this->save($entry, false);
      return "event $id joined";
    }
    function leaveEvent($id, $hub, $event) {
      $entry = $this->get($hub);
      $index = $this->getEventIndex($hub, $event);
      foreach ($entry['events'][$index]['going'] as $key => $value) {
        if ($value['id'] == $id) {
          unset($entry['events'][$index]['going'][$key]);
          $this->save($entry, false);
          return "event $id left";
        }
      }
    }
    function newEventComment($id, $hub, $event, $content) {
      $entry = $this->get($hub);
      $index = $this->getEventIndex($hub, $event);
      $ret = array(
        'id' => new MongoId(),
        'from' => $id,
        'date' => time(),
        'content' => $content
      );
      $entry['events'][$index]['comments'][] = $ret;
      $this->save($entry, false);
      return $ret;
    }
    function deleteEventComment($hub, $event, $comment) {
      $entry = $this->get($hub);
      $index = $this->getEventIndex($hub, $event);
      $index2 = $this->getEventCommentIndex($hub, $event, $comment);
      $ret = $entry['events'][$index]['comments'][$index2];
      unset($entry['events'][$index]['comments'][$index2]);
      $this->save($entry, false);
      return $ret;
    }

    //Misc
    function validArray($ar) {
      return is_array($ar) && count($ar) > 0;
    }
  }

  $MSocial = new SocialModel();
?>