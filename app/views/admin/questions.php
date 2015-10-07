<?php
  function questionBlock(array $data) {
    extract($data);
?>
    <question qid="<?php echo $_id; ?>">
      <text>
        <?php echo $text; ?>
      </text>
      <button class="togglemore" qid="<?php echo $_id; ?>">
        Toggle More
      </button>
      <moredetails>
        <id class="attr">
          <?php echo $_id; ?>
        </id>
        <recruiter class="attr">
          <?php echo $recruiter; ?>
        </recruiter>
        <uses class="attr">
          <?php echo count($uses); ?>
          <pre>
            <?php
              foreach ($uses as $applicationId) {
                echo "$applicationId<br />";
              }
            ?>
          </pre>
        </uses>
        <vanilla class="attr">
          <?php echo $vanilla; ?>
        </vanilla>
        <?php
          if (count($uses) == 0) {
        ?>
            <form method="post"
                  onsubmit="return confirm('Are you sure?');">
              <input type="hidden" name="_id" value="<?php echo $_id; ?>" />
              <div class="form-slider"><label for="text">Text</label><input type="text" id="text" name="text" value="<?php echo $text; ?>" required /></div>
              <input type="submit" name="editQuestion" value="Edit Question" />
              <input type="submit" name="deleteQuestion" value="Delete Question" />
            </form>
        <?php
          }
        ?>
      </moredetails>
    </question>
<?php
  }
?>

<style>
  question {
    display: block;
    text-align: left;
    padding: 1em;
    border: 1px solid #000;
    margin: 0.25em auto;
  }
  question .attr {
    display: block;
  }
  question id::before {
    content: "_id: ";
  }
  question text::before {
    content: "text: ";
  }
  question recruiter::before {
    content: "recruiter: ";
  }
  question uses::before {
    content: "uses: ";
  }
  question vanilla::before {
    content: "vanilla: ";
  }

  moredetails {
    display: block;
  }

  .togglemore {
    float: right;
  }

  form {
    text-align: center;
  }
</style>

<script>
  $(function() {
    $('moredetails').hide();

    $('.togglemore').click(function() {
      var id = $(this).attr('qid');
      $('question[qid=' + id + '] moredetails').slideToggle();
    });
  });
</script>

<panel>
  <div class="content">
    <?php vnotice(); ?>
  </div>
</panel>
<panel class="form">
  <div class="content">
    <headline>Create a Vanilla Question</headline>
    <form method="post">
      <div class="form-slider"><label for="text">Text</label><input type="text" id="text" name="text" value="<?php View::echof('createVanillaText'); ?>" required /></div>
      <input type="submit" name="createVanilla" value="Create" />
    </form>
  </div>
</panel>
<panel>
  <div class="content">
    <headline>Vanilla Questions</headline>
    <?php
      $vanilla = View::get('vanilla');
      foreach ($vanilla as $data) {
        questionBlock($data);
      }
    ?>
  </div>
</panel>
<panel>
  <div class="content">
    <headline>Custom Questions</headline>
    <?php
      $custom = View::get('custom');
      foreach ($custom as $data) {
        questionBlock($data);
      }
    ?>
  </div>
</panel>