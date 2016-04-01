<?php
  require_once($GLOBALS['dirpre'].'models/Model.php');

  interface CompanyModelInterface {
    // Return a subset of the companies in our database.
    // $count=0 means no limit.
    public static function getSubset($start, $count);
    public static function search($search);

    /**
     * @return Gets 'name', 'logophoto'.
     */
    public static function getByIdMinimal(MongoId $companyId);
    /**
     * @return Gets '_id'.
     */
    public static function getByIndustry($industry);
  }

  class CompanyModel extends Model implements CompanyModelInterface {
    const DB_TYPE = parent::DB_INTERNSHIPS;

    public static function getSubset($start=0, $count=0) {
      $query = (new DBQuery(self::$collection))
        // ->projectField('')
        ->sortField('_id', -1)->skip($start)->limit($count);
      $companies = $query->run();
      return $companies;
    }

    public static function search($search, $start=0, $count=0) {
      return (new DBQuery(self::$collection))
        ->skip($start)->limit($count)->textSearch($search);
    }

    public static function getByIdMinimal(MongoId $companyId) {
      return self::getById($companyId, [
        'name' => 1,
        'logophoto' => 1
      ]);
    }

    public static function getByIndustry($industry) {
      $query = (new DBQuery(self::$collection))
        ->toQuery('industry', [ '$regex' => keywords2mregex($industry) ])
        ->projectField('_id');
      return $query->run();
    }

    function __construct() {
      parent::__construct(self::DB_TYPE, 'companies');

      // Create necessary indices.
      mongo_ok(self::$collection->createIndex([
        'name' => 'text',
        'industry' => 'text'
      ]));
    }

    function save($data) {
      self::$collection->save($data);
      return $data['_id']->{'$id'};
    }

    function get($id) {
      return self::$collection->findOne(array('_id' => new MongoId($id)));
    }
    function getByName($name) {
      return self::$collection->findOne(array('name' => $name));
    }
    function getName($companyId) {
      $company = self::getById($companyId, ['name' => 1]);
      return $company['name'];
    }
    function getIndustry($id) {
      $entry = $this->get($id);
      return $entry['industry'];
    }
    function find($query) {
      return self::$collection->find($query);
    }

    function delete($id) {

    }

    function exists($id) {
      return ($this->get($id) !== NULL);
    }

    protected static $collection;
  }

  GLOBALvarSet('MCompany', new CompanyModel());
?>