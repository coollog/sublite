<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  interface S3ControllerAJAXInterface {
    public static function uploadResume();
    public static function uploadImage();
  }

  class S3ControllerAJAX extends Controller
                         implements S3ControllerAJAXInterface {
    public static function uploadResume() {
      function respond($err, $resume = null, $fname = null) {
        $data = [
          'error' => $err,
          'resume' => $resume,
          'fname' => $fname
        ];
        echo toJSON($data);
      }

      if (!isset($_FILES['file'])) {
        return respond('no file selected');
      }

      switch ($_FILES['file']['error']) {
        case UPLOAD_ERR_OK:
          break;
        case UPLOAD_ERR_FORM_SIZE:
          return respond('max file size exceeded');
        case UPLOAD_ERR_NO_FILE:
          return respond('no file selected');
        case UPLOAD_ERR_INI_SIZE:
          return respond('file too large');
      }

      if ($_FILES['file']['size'] > 10*1024*1024) {
        return respond('max file size exceeded');
      }

      $filename = $_FILES['file']["name"];
      $ext = pathinfo($filename, PATHINFO_EXTENSION);
      $allowed = [ "doc", "docx", "rtf", "pdf" ];

      if (!preg_grep("/$ext/i", $allowed)) return respond('bad file extension');

      $fname = $_FILES['file']['tmp_name'];
      $actualFilename = time() . '.' . $ext;
      $link = self::uploadToS3($fname, $actualFilename);
      if (!$link) return respond('upload failed');

      return respond(null, $link, $filename);
    }

    public static function uploadImage() {
      function respond($err, $url = null) {
        $data = [
          'error' => $err,
          'url' => $url
        ];
        echo toJSON($data);
      }

      // Perform validations.
      if (!isset($_FILES['file'])) return respond('no file selected');
      switch ($_FILES['file']['error']) {
        case UPLOAD_ERR_OK: break;
        case UPLOAD_ERR_FORM_SIZE: return respond('max file size exceeded');
        case UPLOAD_ERR_NO_FILE: return respond('no file selected');
        case UPLOAD_ERR_INI_SIZE: return respond('file too large');
        default: return respond('file failed to upload');
      }
      if ($_FILES['file']['size'] > 10*1024*1024)
        return respond('max file size exceeded');
      $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
      $allowed = [ 'jpg', 'jpeg', 'png', 'gif' ];
      if (!preg_grep("/$ext/i", $allowed)) return respond('bad file extension');

      // Resize image.
      $fname = $_FILES['file']['tmp_name'];
      $imageWidth = self::resizeImage($fname);
      if (!$imageWidth) return respond('invalid image type');
      if (isset($_GET['name']) &&
          $_GET['name'] == 'bannerphoto' &&
          $imageWidth < 1000) {
        return respond('Please upload a banner image at least 1000px wide.');
      }

      // Rename image name.
      $actualImageName = time() . ".$ext";

      // Upload to S3.
      $link = self::uploadToS3($fname, $actualImageName);
      if (!$link) respond('upload failed');

      return respond(null, $link);
    }

    private static function resizeImage($fname) {
      require_once($GLOBALS['dirpre'].'includes/S3/SimpleImage.php');

      $image = new SimpleImage();

      if (!$image->load($fname)) return false;

      if ($image->getHeight() < $image->getWidth()) {
        if ($image->getHeight() > 1000) $image->resizeToHeight(1000);
      } else if ($image->getWidth() > 1000) $image->resizeToWidth(1000);

      $image->save($fname);

      return $image->getWidth();
    }

    private static function uploadToS3($localFilename, $remoteFilename) {
      require_once($GLOBALS['dirpre'].'includes/S3/s3_config.php');
      $res = $s3->putObjectFile($localFilename,
                                $bucket,
                                $remoteFilename,
                                S3::ACL_PUBLIC_READ);
      if (!$res) return false;

      return "http://$bucket.s3.amazonaws.com/$remoteFilename";
    }
  }
?>
