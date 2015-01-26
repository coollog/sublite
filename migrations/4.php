<?php
  global $MCompany;

  $companies = $MCompany->getAll();

  foreach ($companies as $company) {
    if (!isset($company['photos'])) $company['photos'] = array();
    
    $MCompany->save($company);
  }
?>