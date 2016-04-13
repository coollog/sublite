<style>
  .uploadimage {
    text-align: left;
    padding: 1em;
  }
  .uploadimage red, .uploadimage green, .uploadimage orange {
    display: block;
    text-transform: uppercase;
  }
  .uploadimage input[type=button] {
    display: inline-block;
    vertical-align: top;
  }
</style>

<script>
  var ImageUploader = {
    alreadySetup: false,
    setup: function () {
      if (ImageUploader.alreadySetup) return;

      $('.uploadimage input[type=button]').click(function () {
        var curUploadImage = $(this).parent();
        var s3name = curUploadImage.attr('name');
        ImageUploader.beforeUpload(curUploadImage);

        // Compile the image data into a FormData.
        var imageInput = curUploadImage.find('input[type=file]')[0];
        var imageData = new FormData();
        imageData.append('file', imageInput.files[0]);
        imageInput.value = '';

        // Send the upload data.
        $.ajax({
          url: curUploadImage.attr('action'),
          type: 'POST',
          processData: false,
          contentType: false,
          dataType: 'json',
          data: imageData
        }).done(function (data) {
            ImageUploader.afterUpload(s3name, data);
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR.responseText);
            curUploadImage.children('red').html('upload error').show();
          });
      });

      ImageUploader.hideResult($('.uploadimage'));
      ImageUploader.alreadySetup = true;
    },
    beforeUpload: function (curUploadImage) {
      // Show "Uploading...".
      curUploadImage.children('input[type=button]').prop('disabled', true);
      ImageUploader.hideResult(curUploadImage);
      curUploadImage.children('orange').show();
    },
    afterUpload: function (s3name, data) {
      var curUploadImage = $('.uploadimage[name='+s3name+']');

      // Do UI changes.
      curUploadImage.find('input[type=button]').prop('disabled', false);
      curUploadImage.children('orange').hide();

      if (data.error != null) {
        curUploadImage.children('red').html(data.error).show();
        return;
      }
      curUploadImage.children('green').show();

      // Call the corresponding addImg function.
      addImg[s3name](data.url);
    },
    hideResult: function (curUploadImage) {
      curUploadImage.children('red, green, orange').hide();
    }
  };
  $(function() {
    ImageUploader.setup();
  });
</script>

<div class="uploadimage" name="<?php View::echof('s3name'); ?>"
     action="<?php echo $GLOBALS['dirpre']; ?>../S3/image">
  <div class="inlinediv">
    Image (&lt; 10MB):
    <input type="file" name="file" accept=".jpg, .jpeg, .png, .gif"  /><br />
    <small>(.JPG/.JPEG, .PNG, .GIF only)</small>
  </div>
  <input type="button" class="smallbutton" value="Upload" />
  <red></red>
  <green>Image Uploaded!</green>
  <orange>Uploading...</orange>
</div>