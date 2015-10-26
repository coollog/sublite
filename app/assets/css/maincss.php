<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
<style>
  @font-face {
      font-family: BebasNeue;
      src: url('<?php echo $GLOBALS['dirpre']; ?>/assets/fonts/BebasNeue_Regular.ttf');
      font-weight: normal;
  }
  @font-face {
      font-family: BebasNeue;
      src: url('<?php echo $GLOBALS['dirpre']; ?>/assets/fonts/BebasNeue_Bold.ttf');
      font-weight: bold;
  }
  html, body {
    height: 100%;
    margin: 0;
    font: 400 14px/1.5em 'Open Sans', sans-serif;
    background: #000;
  }
  green {
    color: #00B233;
  }
  red {
    color: #FF1919;
  }
  a {
    color: #035d75;
    text-decoration: none;
  }
  a:active {
    color: #035d75;
  }
  a:hover {
    color: #4da1a9;
    text-decoration: none;
  }
  a:visited {
  }
  .switch a {
    color: #ffD800;
    text-decoration: none;
  }
  .switch a:active {
    color: #035d75;
  }
  .switch a:hover {
    color: #fff;
    text-decoration: none;
  }
  .switch a:visited {
  }
  panel {
    display: block;
    width: 100%;
    text-align: center;
    padding: 50px 0;
    background: #fff;
  }
  .content {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 50px;
  }
  .cell {
    display: table-cell;
    vertical-align: middle;
  }
  .cellbottom {
    display: table-cell;
    vertical-align: bottom;
  }
  headline {
    display: block;
    font-size: 3.5em;
    text-transform: uppercase;
    margin-bottom: 40px;
    color: #035d75;
    font-family: 'BebasNeue', sans-serif;
    font-weight: bold;
  }
  subheadline {
    display: block;
    font-size: 1.3em;
    margin-top: 0.9em;
    color: #035d75;
    font-weight: 700;
  }
  right {
    text-align: right;
    display: block;
  }
  left {
    text-align: left;
    display: block;
  }
  fade {
    opacity: 0.5;
    transition: 0.1s all ease-in-out;
  }
  fade:hover {
    opacity: 1;
  }
  .hide {
    display: none;
  }

  /* MISC CLASSES */
  .capitalize {
    text-transform: capitalize;
  }
  .clear{ clear:both; }
</style>
