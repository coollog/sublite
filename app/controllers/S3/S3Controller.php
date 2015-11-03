<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  interface S3ControllerInterface {
    public static function resume();
  }

  class S3Controller extends Controller implements S3ControllerInterface {
    public static function resume() {
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
      }

      if ($_FILES['file']['size'] > 10*1024*1024) {
        return respond('max file size exceeded');
      }

      $filename = $_FILES['file']["name"];
      $ext = pathinfo($filename, PATHINFO_EXTENSION);
      $allowed = array("doc", "docx", "rtf", "pdf");

      if (!in_array($ext, $allowed)) {
        return respond('bad file extension');
      }

      require_once($GLOBALS['dirpre'].'includes/S3/s3_config.php');

      $fname = $_FILES['file']["tmp_name"];
      $actualFilename = time() . '.' . $ext;
      $res = $s3->putObjectFile($fname,
                                $bucket,
                                $actualFilename,
                                S3::ACL_PUBLIC_READ);
      if (!$res) {
        return respond('upload failed');
      }

      $link = 'http://'.$bucket.'.s3.amazonaws.com/'.$actualFilename;
      return respond(null, $link, $filename);
    }

    function upload() {
      if (!isset($_FILES['upload'])) { $this->directrender('S3/S3'); return; }

      global $params;
      // Params to vars
      $client_id = '1b5cc674ae2f335';

      // Validations
      $this->startValidations();
      $this->validate($_FILES['upload']['error'] === 0, $err, 'upload error');
      $this->validate($_FILES['upload']['size'] <= 10*1024*1024,
      	$err, 'size too large');

      // Code
      if ($this->isValid()) {
      	$fname = $_FILES['upload']['tmp_name'];

				require_once($GLOBALS['dirpre'].'includes/S3/SimpleImage.php');
				$image = new SimpleImage();
				$this->validate($image->load($fname), $err, 'invalid image type');

        if ($this->isValid()) {
  				if ($image->getHeight() > 1000)
  					$image->resizeToHeight(1000);
  				$image->save($fname);

  				function getMIME($fname) {
  					$finfo = finfo_open(FILEINFO_MIME_TYPE);
  					$mime = finfo_file($finfo, $fname);
  					finfo_close($finfo);
  					return $mime;
  				}

  				$filetype = explode('/', getMIME($fname));
  				$valid_formats = array("jpg", "png", "gif", "jpeg");

  				$this->validate($filetype[0] === 'image' and
  												in_array($filetype[1], $valid_formats),
  												$err, 'invalid image type');

  				if ($this->isValid()) {
  					require_once($GLOBALS['dirpre'].'includes/S3/s3_config.php');

  					//Rename image name.
  					$actual_image_name = time() . "." . $filetype[1];

  					$this->validate($s3->putObjectFile($fname, $bucket ,
  																						 $actual_image_name,
  																						 S3::ACL_PUBLIC_READ),
  																						 $err, 'upload failed');

  					if ($this->isValid()) {
  						$reply = "https://$bucket.s3.amazonaws.com/$actual_image_name";
              $this->success('image successfully uploaded');
  						$this->directrender('S3/S3', array('reply' => "up(\"$reply\");"));
  						return;
  					}
          }
				}
      }

      $this->error($err);
      $this->directrender('S3/S3');
    }
  }

  $CS3 = new S3Controller();

?>