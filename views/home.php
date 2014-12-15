<panel>
  <div class="content">
    <?php
      $id = vget('_id');
      $company = vget('company');
      echo vlinkto('<input type="button" value="View Profile" />', 'recruiter', array('id' => $id), true);
      echo vlinkto('<input type="button" value="View Company Profile" />', 'company', array('id' => $company), true);
    ?>
  </div>

