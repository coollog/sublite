<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  interface CompanyControllerAJAXInterface {
    public static function viewAll();
  }

  class CompanyControllerAJAX extends CompanyController
                              implements CompanyControllerAJAXInterface {
    public static function viewAll() {
      global $params;

      $start = intval($params['start']);
      $count = intval($params['count']);
      if (isset($params['search'])) {
        $search = clean($params['search']);
        $companies = CompanyModel::search($search, $start, $count);
      } else {
        $companies = CompanyModel::getSubset($start, $count);
      }

      echo toJSON($companies);
    }
  }
?>