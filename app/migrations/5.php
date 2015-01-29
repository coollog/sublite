<?php
  global $MCompany;

  $companies = $MCompany->getAll();

  foreach ($companies as $company) {
    if (!is_array($company['industry'])) $company['industry'] = array($company['industry']);
    
    $MCompany->save($company);
  }
?>