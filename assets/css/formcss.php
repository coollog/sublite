<style>
  form {
    max-width: 600px;
    margin: 10px auto;
  }
  .success {
    display: block;
    margin: 20px 0;
    padding: 20px;
    border: 2px solid #00B233;
    color: #00B233;
    text-transform: uppercase;
  }
  .error {
    display: block;
    margin: 20px 0;
    padding: 20px;
    border: 2px solid #FF1919;
    color: #FF1919;
    text-transform: uppercase;
  }
  label {
    opacity: 0.7;
    cursor: pointer;
  }
  label.fortextarea {
    max-width: 200px;
    text-align: right;
  }
  input[type=number], input[type=text], input[type=password], input[type=email], textarea {
    transition: all 0.30s ease-in-out;
    outline: none;
    padding: 0.5em 0px 0.5em 0.5em;
    border: 0;
    width: 100%;
    height: 2.5em;
    box-sizing: border-box;
    margin: 0.5em 0;
    background: #fbf9ec;
    line-height: 2em;
    font-family: 'Open Sans', sans-serif;
  }
  textarea {
    height: 10em;
  }
  input[type=checkbox], input[type=radio] {
    display: inline-block;
    width: 1em;
    height: 1em;
    background: #fff;
    border: 0;
    margin-right: 0.1em;
  }
  input[type=submit], input[type=button] {
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
  input[type=submit] {
    margin-top: 20px;
  }
  input[type=submit]:hover, input[type=button]:hover {
    background: #035d75;
    color: #fff;
  }
</style>

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