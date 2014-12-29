<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="jquery.slidinglabels.min.js"></script>
<?php require_once($dirpre.'maincss.php'); ?>

<script>
$(function() { 
  $('form').slidinglabels({ 
    /* these are all optional */ 
    className : 'form-slider', // the class you're wrapping the label & input with -> default = slider 
    topPosition : '1em', // how far down you want each label to start 
    leftPosition : '0.5em', // how far left you want each label to start 
    axis : 'x', // can take 'x' or 'y' for slide direction 
    speed : 'fast' // can take 'fast', 'slow', or a numeric value 
  }); 
});
</script>


<?php require_once($dirpre.'navbar.php'); ?>
<panel class="form">
  <div class="content">
    <headline>Create Job Listing</headline>
    <form method="post">
      <div class="form-slider"><label for="title">Job Title:</label><input type="text" id="title" name="title" /></div>
      <div class="form-slider"><label for="duration">Duration (weeks):</label><input type="number" id="duration" name="duration" /></div>
      <div class="form-slider"><label for="salary">Compensation / Stipend ($US):</label><input type="number" id="salary" name="salary" /></div>
      <right>
        <input type="radio" name="salarytype" value="month" /> / month
        <input type="radio" name="salarytype" value="day" /> / day
        <input type="radio" name="salarytype" value="hour" /> / hour
        <input type="radio" name="salarytype" value="total" /> total payment
      </right>
      <div class="form-slider"><label for="deadline">Deadline for Application (mm/dd/yyyy):</label><input type="text" id="deadline" name="deadline" /></div>
      <div class="form-slider"><label for="desc">Job Description:</label><textarea id="desc"a</me="desc"></textarea></div>
      <div class="form-slider"><label for="Requirements">Requirements:</label><input type="text" id="Requirements" name="Requirements" /></div>
      <div class="form-slider"><label for="link">Listing URL:</label><input type="text" id="link" name="link" /></div>
      <div class="form-slider"><label for="location">Job Location:</label><input type="text" id="location" name="location" /></div>
      <right><input type="submit" name="add" value="Add Job" /></right>
    </form>
  </div>
</panel>
<panel class="form">
  <div class="content">
    <headline>Register as a Recruiter!</headline>
    <form method="post">
      <div class="form-slider"><label for="name">Name</label><input type="text" id="name" name="name" /></div>
      <div class="form-slider"><label for="title">Job Title</label><input type="text" id="title" name="title" /></div>
      <div class="form-slider"><label for="company">Company</label><input type="text" id="company" name="company" /></div>
      <div class="form-slider"><label for="email">Email</label><input type="text" id="email" name="email" /></div>
      <div class="form-slider"><label for="password">Password</label><input type="text" id="password" name="password" /></div>
      <div class="form-slider"><label for="confirm">Confirm Password</label><input type="text" id="confirm" name="confirm" /></div>
      <input type="submit" name="register" value="Register" />
    </form>
  </div>
</panel>
<?php require_once($dirpre.'footer.php'); ?>