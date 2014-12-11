<panel>
  <div class="content">
    <?php
      $id = vget('_id');
      echo vlinkto('<input type="button" value="View Profile" />', 'recruiter', array('id' => $id), true);
      echo vlinkto('<input type="button" value="Edit Company Profile" />', 'editcompany');
    ?>
  </div>
</panel>