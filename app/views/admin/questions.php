<style>
  question {
    display: block;
  }
  question .attr {
    display: block;
  }
  question id::before {
    content: "_id: ";
  }
  question text::before {
    content: "_id: ";
  }
  question recruiter::before {
    content: "_id: ";
  }
</style>

<panel>
  <div class="content">
    <question>
      <id class="attr"><?php vecho('_id'); ?></id>
      <text class="attr"><pre><?php vecho('text'); ?></pre></text>
      <recruiter class="recruiter"><?php vecho('recruiter'); ?></recruiter>
      <uses class="uses"><?php vecho('uses'); ?></uses>
      <uses class="vanilla"><?php vecho('vanilla'); ?></uses>
    </question>
  </div>
</panel>