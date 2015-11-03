<style>
  #uploadresume red, #uploadresume green {
    display: block;
    text-transform: uppercase;
  }
</style>

<script>
  $(function() {
    function beforeUploadResume() {
      $('#uploadresume input[type=submit]').prop('disabled', true);
      $('#uploadresume red, #uploadresume green').hide();
    }
    function uploadedResume(data) {
      $('#uploadresume input[type=submit]').prop('disabled', false);

      console.log(data);
      data = JSON.parse(data);
      if (data.error !== null) {
        $('#uploadresume red').html(resumeLink);
        return
      }
      $('#uploadresume green').show();

      var resumeFname = data.fname;
      $('#resumefname').html(resumeFname);

      var resumeLink = data.resume;
      updateResumeLink(resumeLink);
    }
    $('#uploadresume').ajaxForm({
      beforeSubmit: beforeUploadResume,
      success: uploadedResume
    });
    $('#uploadresume red, #uploadresume green').hide();
  });
</script>

<form id="uploadresume" method="post" enctype="multipart/form-data"
      action="<?php echo $GLOBALS['dirpre']; ?>../S3/resume.php">
  Select file to upload (&lt; 10MB):
  <input type="file" accept=".doc, .docx, .rtf, .pdf" name="file" />
  <input type="submit" class="smallbutton" value="Upload" style="margin: 0;" />
  <small>(.DOC, .DOCX, .RTF, .PDF only)</small>
  <red></red>
  <green>Resume Uploaded!</green>
</form>