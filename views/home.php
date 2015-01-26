<panel>
  <div class="content">
    <?php
      $id = vget('_id');
      $company = vget('company');
      echo vlinkto('<input type="button" value="Edit Profile" />', 'editprofile');
      echo vlinkto('<input type="button" value="Edit Company Profile" />', 'editcompany');
    ?>
  </div>
</panel>