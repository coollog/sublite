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
  }
  .whereto .content {
    display: table-cell;
    vertical-align: middle;
  }
  .whereto {
    height: 90vh;
    display: table;
    background: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/whereto.jpg') no-repeat center center;
    background-size: cover;
  }
</style>

<panel class="whereto">
  <div class="content">
    <div class="tagline">Find Your Perfect Summer</div>

    <a href="/jobs">
      <input type="button" name="internships" value="Internships" /> &nbsp;
    </a>
    <a href="/housing">
      <input type="button" name="housing" value="Housing" /> &nbsp;
    </a>
    <a href="">
      <input type="button" name="social" value="Social" style="background: #999;" />
    </a>
  </div>
</panel>
