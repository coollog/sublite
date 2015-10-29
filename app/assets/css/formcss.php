<style>
  form {
    max-width: 600px;
    margin: 10px auto;
  }
  .success, .error {
    text-align: center;
    display: block;
    margin: 20px 0;
    padding: 20px;
    text-transform: uppercase;
  }
  .success {
    border: 2px solid #00B233;
    color: #00B233;
  }
  .error {
    border: 2px solid #FF1919;
    color: #FF1919;
  }
  label {
    opacity: 0.7;
    cursor: pointer;
    transition: 0.2s all ease-in-out;
  }
  label.fortextarea {
    max-width: 200px;
    text-align: right;
  }
  input[type=time],
  input[type=number],
  input[type=text],
  input[type=password],
  input[type=email],
  textarea,
  select,
  .mimictextarea {
    transition: all 0.30s ease-in-out;
    outline: none;
    padding: 0.75em 0px 0.5em 0.5em;
    border: 0;
    width: 100%;
    height: 2.5em;
    box-sizing: border-box;
    margin: 0.5em 0;
    background: #fbf9ec;
    line-height: 1.5em;
    font-family: 'Open Sans', sans-serif;
    font-size: 14px;
  }
  textarea,
  .mimictextarea {
    height: 10em;
  }
  input[type=checkbox],
  input[type=radio] {
    display: inline-block;
    width: 1em;
    height: 1em;
    background: #fff;
    border: 0;
    margin-right: 0.1em;
  }
  input[type=submit],
  input[type=button] {
    padding: 0 40px;
    height: 2em;
    background: #ffd800;
    box-sizing: border-box;
    border: 0;
    transition: all 0.10s ease-in-out;
    cursor: pointer;
    text-transform: uppercase;
    font-size: 1.2em;
    font-weight: 700;
    outline: none;
  }
  input[type=button].smallbutton {
    padding: 0 1em;
    font-size: 1em;
    font-weight: normal;
    height: 2.5em;
  }
  input[type=submit].reverse,
  input[type=button].reverse {
    background: #035d75;
    color: #fff;
  }
  input[type=submit]:enabled:hover,
  input[type=button]:enabled:hover {
    background: #035d75;
    color: #fff;
  }
  input[type=submit].reverse:enabled:hover,
  input[type=button].reverse:enabled:hover {
    background: #fff;
    color: #000;
  }
  input[type=submit] {
    margin-top: 20px;
  }
  .checkboxes {
    margin-left: 1em;
    padding-top: 1em;
  }

  .img {
    text-align: left;
  }
  .img img {
    max-height: 15em;
    margin: 0.5em;
    transition: all 0.3s ease-in-out;
    cursor: pointer;
  }
  .img img:hover {
    opacity: 0.5;
  }
  .img .remove {
    float: right;
    margin-top: 100px;
  }
  .iframe {
    border: 1px solid #999;
    text-align: center;
  }
  iframe {
    border: 0;
    margin: 1em;
    width: 90%;
    display: inline-block;
    box-sizing: border-box;
  }

  /* JQUERY UI */
  .ui-datepicker {
    z-index: 999 !important;
  }
  .ui-slider-range {
    background: #ffd800;
  }
  .slider {
    display: inline-block;
    width: 80%;
  }
  .sliderafter {
    display: inline-block;
    padding-left: 10px;
    max-width: 19%;
    white-space: nowrap;
    box-sizing: border-box;
  }
  .sliderlabel {
    margin-bottom: 5px;
  }
  .form-half1 {
    width: 49%;
    display: inline-block;
  }
  .form-half2 {
    width: 50%;
    display: inline-block;
  }
</style>