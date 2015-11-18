<?php
  interface CompanyModelInterface {
    public function __construct();

    public static function save(array $data);
    public static function getByName($name);
    public static function getName(MongoId $companyId);
    public static function getIndustry(MongoId $companyId);
    public static function findIds(array $query);
  }

  class CompanyModel extends Model implements CompanyModelInterface {
    const DB_TYPE = parent::DB_INTERNSHIPS;

    public function __construct() {
      parent::__construct(self::DB_TYPE, 'companies');

      // Create necessary indices.
      mongo_ok(self::$collection->createIndex(['name' => 1]));
      mongo_ok(self::$collection->createIndex(['industry' => 1]));
    }

    public static function save(array $data) {
      self::$collection->save($data);
      return $data['_id']->{'$id'};
    }

    public static function getByName($name) {
      $query = (new DBQuery(self::$collection))->toQuery('name', $name);
      return $query->findOne();
    }
    public static function getName(MongoId $companyId) {
      $company = self::getById($companyId, ['name' => 1]);
      return $company['name'];
    }
    public static function getIndustry(MongoId $companyId) {
      $company = self::getById($companyId, ['industry' => 1]);
      return $company['industry'];
    }
    public static function findIds(array $query) {
      $query = (new DBQuery(self::$collection))->setQuery($query)->projectId();
      return $query->run();
    }

    protected static $collection;
  }

  new CompanyModel();
?>