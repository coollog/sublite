<style>
  .tagline {
    color: #ffd800;
    font-size: 4em;
    text-transform: uppercase;
    text-shadow: 2px 2px #035d75;
    line-height: 1em;
    margin-bottom: .2em;
    font-family: 'BebasNeue', sans-serif;
    font-weight: bold;
  }
  .whereto input[type=button] {
    width: 200px;
    margin: 10px;
    padding: 20px 40px;
  }
  .whereto .content {
    padding-top: 30vh;
  }
  .whereto {
    height: 100%;
    display: table;
    background: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/whereto.jpg') no-repeat center center;
    background-size: cover;
  }
</style>

<panel class="whereto">
  <div class="content">
    <div class="tagline">Find Your Perfect Summer</div>

    <a href="/jobs/search.php">
      <input type="button" name="internships" value="Internships" />
    </a>
    <a href="/housing/search.php">
      <input type="button" name="housing" value="Housing" />
    </a>
    <a href="">
      <input type="button" name="social" value="Social (soon)" style="background: #999;" />
    </a>
  </div>
</panel>
