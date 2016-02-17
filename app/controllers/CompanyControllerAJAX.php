<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  interface CompanyControllerAJAXInterface {
    public static function viewAll();
  }

  class CompanyControllerAJAX
    extends CompanyController
    implements CompanyControllerAJAXInterface {

    public static function viewAll() {
      $start = intval($params['start']);
      $count = intval($params['count']);

      $companies = CompanyModel::getSubset($start, $count);

      echo toJSON($companies);
    }
  }
?>