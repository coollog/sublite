<?php
  $companies = CompanyModel::getAll();

  foreach ($companies as $company) {
    if (!isset($company['photos'])) $company['photos'] = array();

    CompanyModel::save($company);
  }
?>