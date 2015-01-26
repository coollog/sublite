<?php	
	class DB {
		public static $mongo;
		protected static $db;

		public function __construct() {
			DB::$mongo = new MongoClient($GLOBALS['mongouri']);
			$dbname = 'sublite';
			DB::$db = DB::$mongo->$dbname;
		}
	}
		class Email extends DB {
			public $find;
			public $cEmails;
		
			public function __construct() {
				// Emails collection
				$collectionName = 'emails';
				$this->cEmails = DB::$db->$collectionName;
			}
			// DB retrieve emails
			public function find() {
				$this->find = $this->cEmails->find(array(), array("email" => 1));
				return $this->find;
			}
			// Batch add
			public function addBatch($emails) {
				//$this->cEmails->batchInsert($emails);
				foreach ($emails as $e) {
					$this->cEmails->update($e, $e, array("upsert" => true));
				}
			}
			// Batch remove
			public function removeBatch($emails) {
				//$this->cEmails->batchInsert($emails);
				foreach ($emails as $e) {
					$this->cEmails->remove($e);
				}
			}
			// Emails as array
			public function getArr() {
				if (!isset($this->find)) $this->find();
				$emails = array();
				foreach($this->find as $emaildoc) {
					$emails[] = $emaildoc['email'];
				}
				return $emails;
			}
			// Email in whitelist?
			public function validate($email) {
				if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
					return in_array($email, $this->getArr());
				}
				return false;
			}
			// Get one email doc
			public function getDoc($email) {
				return $this->cEmails->findOne(array('email' => $email));
			}
			// Email has account?
			public function hasAccount($email) {
				$doc = $this->getDoc($email);
				if (isset($doc['pass'])) {
					return true;
				}
				return false;
			}
			// Get profile
			public function getProfile($id) {
				$doc = $this->cEmails->findOne(array("_id" => new MongoId($id)));
				if (!isset($doc['pass'])) return false; // No account
				return $doc;
			}
		}
		class Listing extends DB {
			public $cListings;
			
			public function __construct() {
				// Listings collection
				$collectionName = 'listings';
				$this->cListings = DB::$db->$collectionName;
			}
			
			// Add a listing
			public function add($listing) {
				$this->cListings->insert($listing);
				return $listing['_id'];
			}
			// Remove a listing
			public function remove($id) {
				$this->cListings->remove(array("_id" => new MongoId($id)));
			}
			// Retrieve a listing
			public function get($id) {
				$l = $this->cListings->findOne(array('_id' => new MongoId($id)));
				if (!isset($l['city'])) $l['city'] = '';
				if (!isset($l['state'])) $l['state'] = '';
				return $l;
			}
			// Publish/Unpublish listing
			public function publish($id, $publish) {
				$doc = $this->get($id);
				$doc['publish'] = $publish;
				$this->save($doc);
			}
			// Edit listing
			public function save($doc) {
				$this->cListings->save($doc);
			}
			// Count
			public function length() {
				return $this->cListings->count();
			}
			
			// Search
			public function search($query) {
				return $this->cListings->find($query);
			}
			
			// Commenting
			public function addComment($id, $email, $comment) {
				$doc = $this->get($id);
				$co = array(
					'email' => $email,
					'time' => time(),
					'comment' => $comment
				);
				array_push($doc['comments'], $co);
				$this->save($doc);
			}
		}
		class Messages extends DB {
			public $cMessages;
			
			public function __construct() {
				// Messages collection
				$collectionName = 'messages';
				$this->cMessages = DB::$db->$collectionName;
			}
			
			// Add a message
			public function add($message) {
				$this->cMessages->insert($message);
				return $message['_id'];
			}
			// Remove a message
			public function remove($id) {
				$this->cMessages->remove(array("_id" => new MongoId($id)));
			}
			// Retrieve a message
			public function get($id) {
				$m = $this->cMessages->findOne(array('_id' => new MongoId($id)));
				return $m;
			}
			// Edit message
			public function save($message) {
				$this->cMessages->save($message);
			}
			// Count
			public function length() {
				return $this->cMessages->count();
			}
			// Search
			public function search($query) {
				return $this->cMessages->find($query);
			}
			// Replying
			public function reply($id, $reply) {
				$doc = $this->get($id);
				array_push($doc['replies'], $reply);
				$this->save($doc);
			}
		}
	
	$DB = new DB();
	
	$L = new Listing();
	$E = new Email();
	$M = new Messages();
?>