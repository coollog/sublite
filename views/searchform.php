<style>
  panel.main {
    background: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/how.jpg') no-repeat center center;
    background-size: cover;
    display: table;
    height: 150px;
  }
  panel.main .banner {
    padding: 30px 0;
  }
  panel.main .banner .tagline {
    color: #ffd800;
    font-size: 4em;
    text-transform: uppercase;
    text-shadow: 2px 2px #035d75;
    line-height: 1em;
    margin-bottom: 0.2em;
    font-family: 'BebasNeue', sans-serif;
    font-weight: bold;
  }
  panel.main .button {
    font-size: 1.5em;
    color: #035d75;
    text-transform: uppercase;
  }
  panel.main .button:hover {
    color: #fff;
  }
  panel.search {
    background: #efecdb;
    padding: 20px 0;
  }
</style>
<script>
  $(function() {
    function scrollTo(q) {
      $('html, body').finish().animate({
        scrollTop: $(q).offset().top
      }, 200);
    }

    $('.searchScroll').click(function() {
      scrollTo('.search');
    });
  });
</script>

<panel class="main">
  <div class="cell">
    <div class="banner">
      <div class="content">
        <div class="tagline">Job Search, Reorganized</div>
        <input type="button" class="button searchScroll" value="Search for Jobs" />
      </div>
    </div>
  </div>
</panel>
<panel class="search">
  <div class="content">
    <form method="post">
      <input type="hidden" id="recruiter" name="recruiter" value="<?php vecho('recruiter'); ?>" />
      <div class="form-slider"><label for="company">Company:</label><input type="text" id="company" name="company" value="<?php vecho('company'); ?>" /></div>
      <div class="form-slider"><label for="title">Job Title:</label><input type="text" id="title" name="title" value="<?php vecho('title'); ?>" /></div>
      <div class="form-slider"><label for="industry">Industry:</label>
        <!-- <input type="text" id="industry" name="industry" value="<?php vecho('industry'); ?>" /> -->
        <select id="industry" name="industry" required>
          <?php vecho('size', '<option selected="selected">{var}</option>'); ?>
          <option>Architecture/Design/Urban Planning</option>
          <option>Communications/Marketing/Advertising/PR</option>
          <option>Computer Science/Technology</option>
          <option>Consulting</option>
          <option>Consumer Goods/Retail</option>
          <option>Education</option>
          <option>Entertainment/Professional Sports</option>
          <option>Environment</option>
          <option>Financial Services</option>
          <option>Fine or Performing Arts</option>
          <option>Healthcare</option>
          <option>Law/Legal Services</option>
          <option>Medical/Pharmaceutical</option>
          <option>Public Policy/Politics</option>
          <option>Publishing/Media/Journalism</option>
          <option>Research</option>
        </select>
      </div>
      <?php vnotice(); ?>
      <input type="submit" name="search" value="Search" />
    </form>
  </div>
</panel>