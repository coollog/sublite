<?php
  require_once($GLOBALS['dirpre'].'models/Model.php');

  class SocialModel extends Model {
    const DB_TYPE = parent::DB_STUDENTS;

    // The essentials
    function __construct() {
      parent::__construct(self::DB_TYPE, 'hubs');
    }

    function save($data) {
      self::$collection->save($data);
      return $data['_id']->{'$id'};
    }
    function collection() {
      return self::$collection;
    }

    // Processors
    function membersInfo($members, $title='Member') {
      global $MStudent, $S;

      $membersinfo = array();
      foreach ($members as $member) {
        $id = $member['id'];
        $joined = "$title, ".timeAgo($member['date']);
        $student = $MStudent->getById($id);
        $membersinfo[] = array(
          'id' => $id,
          'name' => isset($student['name']) ? $student['name'] : 'Nonexistent',
          'pic' => $student['photo'],
          'school' => $S->nameOf($student['email']),
          'joined' => $joined
        );
      }
      return $membersinfo;
    }

    // Accessors
    function get($id) {
      if (!MongoId::isValid($id)) return null;
      return self::$collection->findOne(array('_id' => new MongoId($id)));
    }
    function getClosestHub($address, $maxMiles) {
      $geocode = Geocode::geocode($address);
      $longitude = $geocode['longitude'];
      $latitude = $geocode['latitude'];
      $maxMeters = miles2meters($maxMiles);

      return self::$collection->findOne(array('geojson' => array(
        '$near' => array(
          '$geometry' => array(
            'type' => 'Point',
            'coordinates' => array($longitude, $latitude)
          ), '$maxDistance' => $maxMeters
        ),
      )));
    }

    function processPost($post) {
      global $MStudent;
      $student = $MStudent->getById($post['from']);
      $post['pic'] = $student['photo'];
      $post['name'] = isset($student['name']) ? $student['name'] : 'Nonexistent';
      $post['date'] = timeAgo($post['date']);
      $post['id'] = $post['id']->{'$id'};
      if ($post['parent'] != '')
        $post['parent'] = $post['parent']->{'$id'};

      $post['liked'] = false;
      foreach ($post['likes'] as $key => & $sub_array) {
        if ($sub_array['id'] == $post['from']) {
          $post['liked'] = true;
          break;
        }
      }

      $post['content'] = nl2br(autolink($post['content']));

      return $post;
    }
    function getHubPosts($hub, $sortCriterion) {
      $thishub = $this->get($hub);
      return $this->getPosts($thishub['posts'], $thishub['posts'], '', $sortCriterion);
    }
    function getPosts($masterposts, $posts, $parent, $sortCriterion) {
      $ret = array();

      foreach ($posts as $post) {
        $parentid = ($parent == '') ? '' : $parent['id'];
        if (!$post['deleted'] and $post['parent'] == $parentid) {
          if (count($post['children']) > 0) {
            $children = array();
            foreach ($post['children'] as $postid) {
              $children[] = $this->getPost($masterposts, $postid);
            }
            $post['children'] = $this->getPosts($masterposts, $children, $post, $sortCriterion);
          }

          $ret[] = $this->processPost($post);
        }
      }

      // NOTE: SORT BY MOST RECENT/MOST POPULAR CHILD POST!!!

      if ($sortCriterion == 'recent') {
        $ret = array_reverse($ret);
      } else {
        //TODO sort by popular and uncomment below
        $ret = array_reverse($ret);
      }
      return $ret;
    }
    function getPost($masterposts, $postid) {
      foreach ($masterposts as $post) {
        if ($post['id'] == $postid && !$post['deleted']) {
          return $post;
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
      for ($i = 0; $i < count($thishub['events']); $i ++)
        $thishub['events'][$i]['id'] = $thishub['events'][$i]['id']->{'$id'};
      return array_reverse($thishub['events']);
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
      $description = nl2br(autolink($entry['events'][$index]['description']));
      return $description;
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
    function getEventComments($hub, $event, $sortCriterion='recent') {
      $index = $this->getEventIndex($hub, $event);
      $thishub = $this->get($hub);
      $comments = $thishub['events'][$index]['comments'];
      return $this->getPosts($comments, $comments, '', $sortCriterion);
    }
    function getMembers($hub) {
      $thishub = $this->get($hub);
      return $thishub['members'];
    }
    function isGoing($hub, $event, $student) {
      $entry = $this->get($hub);
      $index = $this->getEventIndex($hub, $event);
      $going = &$entry['events'][$index]['going'];
      if(is_array($going) && count($going) > 0) {
        foreach ($going as $key=>&$sub_array) {
          if ($sub_array['id'] == $student) return true;
        }
      }
      return false;
    }
    function getHubs($student) {
      // global $MSocial;
      // $hubs = $MSocial->getAll();
      // $ret = array();
      // foreach ($hubs as $hub) {
      //   if (in_array($student, $hub['members'])) {
      //     $ret[] = $hub['name'];
      //   }
      // }
      // return $ret;
      global $MStudent;
      $s = $MStudent->getById($student);
      if (!isset($s['hubs']['myhub'])) return array();

      $myhub = $s['hubs']['myhub'];

      global $MSocial;
      $hubs = $MSocial->getAll();
      foreach ($hubs as $hub) {
        if ($hub['_id']->{'$id'} == $myhub) {
          return array($hub['name']);
        }
      }
      return array();
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
      $entry['members'][] = array('date' => time(), 'id' => new MongoId($student));
      $this->save($entry, false);

      // Gotta update myhub (current hub)
      global $MStudent;
      $s = $MStudent->getById($student);
      $s['hubs']['myhub'] = $hub;
      $MStudent->save($s);

      return $entry['members'];
    }
    function newPost($id, $hub, $content, $parentid, $event=null) {
      $thishub = $this->get($hub);
      // $posts = $thishub['posts'];
      if (MongoId::isValid($event)) {
        $event = new MongoId($event);
        $index = $this->getEventIndex($hub, $event);
        $posts = &$thishub['events'][$index]['comments'];
        $parentindex = $this->getEventCommentIndex($hub, $event, $parentid);
      } else {
        $posts = &$thishub['posts'];
        $parentindex = $this->getPostIndex($hub, $parentid);
      }

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
      $posts[] = $ret;

      // Set links
      if($parentid != '') {
        $posts[$parentindex]['children'][] = $curId;
      }

      $this->save($thishub, false);

      return $this->processPost($ret);
    }
    function toggleLikePost($hub, $postid, $id, $event=null) {
      $entry = $this->get($hub);

      if (MongoId::isValid($event)) {
        $event = new MongoId($event);
        $index = $this->getEventIndex($hub, $event);
        $postindex = $this->getEventCommentIndex($hub, $event, $postid);
        $post = &$entry['events'][$index]['comments'][$postindex];
      } else {
        $index = $this->getPostIndex($hub, $postid);
        $post = &$entry['posts'][$index];
      }

      //Checks if post is already liked
      if($this->validArray($post['likes'])) {
        foreach ($post['likes'] as $key => $value) {
          if ($value['id'] == $id) {
            unset($post['likes'][$key]);
            $post['likes'] = array_values($post['likes']);
            $this->save($entry, false);
            return "unliked";
          }
        }
      }
      $post['likes'][] = array('date' => time(), 'id' => $id);

      $this->save($entry, false);
      return "liked";
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

    function processEvent($event) {
      $event['id'] = $event['id']->{'$id'};
      return $event;
    }
    function createEvent($id, $hub, $title, $start, $end, $location, $address, $geocode, $description, $banner) {
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
        'going' => array(
          array('id'=>$id, 'date'=>time())
        ),
        'comments' => array(),
        'description' => $description,
        'banner' => $banner
      );
      $entry['events'][] = $ret;
      $this->save($entry, false);
      return $this->processEvent($ret);
    }
    function editEvent($id, $hub, $eventid, $title, $start, $end, $location, $address, $geocode, $description, $banner) {
      $entry = $this->get($hub);
      $index = $this->getEventIndex($hub, $eventid);
      $ret = array_merge(
        $entry['events'][$index],
        array(
          'creator' => $id,
          'title' => $title,
          'starttime' => $start,
          'endtime' => $end,
          'location' => $location,
          'address' => $address,
          'geocode' => $geocode,
          'going' => array(
            array('id'=>$id, 'date'=>time())
          ),
          'comments' => array(),
          'description' => $description,
          'banner' => $banner
        )
      );
      $entry['events'][$index] = $ret;
      $this->save($entry, false);
      return $this->processEvent($ret);
    }
    function isEventOwner($id, $hub, $eventid) {
      $entry = $this->get($hub);
      $index = $this->getEventIndex($hub, $eventid);
      return $entry['events'][$index]['creator'] == $id;
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
      $going = &$entry['events'][$index]['going'];
      foreach ($going as $key=>&$sub_array) {
        if ($sub_array['id'] == $id) {
          unset($going[$key]);
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

    protected static $collection;
  }

  GLOBALvarSet('MSocial', new SocialModel());
?>