<style>
  .tagline {
    color: #ffd800;
    font-size: 4em;
    text-transform: uppercase;
    text-shadow: 2px 2px #035d75;
    line-height: 1em;
    margin-bottom: -0.2em;
    font-family: 'BebasNeue', sans-serif;
    font-weight: bold;
  }
  .search {
    height: 100%;
    display: table;
    background: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/sublet/searchmap.png') no-repeat center center;
    background-size: cover;
  }
  .search .content {
    display: table-cell;
    vertical-align: middle;
  }
  .startdate, .enddate {
    width: 49%;
    display: inline-block;
  }
  .startdate {
    float: left;
  }
  .enddate {
    float: right;
  }
</style>

<panel class="search">
  <div class="content">
    <div class="tagline">Select Location &amp; Date</div>

    <form method="post" style="width: 300px;">
      <div class="form-slider"><label for="location">Address </label><input type="text" id="location" name="location" value="<?php vecho('location'); ?>" /></div>

      <div class="form-slider startdate"><label for="startdate">From: </label><input type="text" id="startdate" name="startdate" value="<?php vecho('startdate'); ?>" /></div>
      <div class="form-slider enddate"><label for="enddate">To: </label><input type="text" id="enddate" name="enddate" value="<?php vecho('enddate'); ?>" /></div>

      <input type="submit" name="search" value="Search" />
    </form>
  </div>
</panel>