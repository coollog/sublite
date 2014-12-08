<panel>
  <div class="content">
    <?php
      $id = vget('_id');
      echo vlinkto('<input type="button" value="View Profile" />', 'recruiter', array('id' => $id), true)
    ?>
  </div>
</panel>