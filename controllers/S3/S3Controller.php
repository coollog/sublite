<?php
  require_once('controllers/Controller.php');

  class S3Controller extends Controller {
    function upload() {
      global $CRecruiter; $CRecruiter->requireLogin();
      if (!isset($_FILES['upload'])) { $this->render('S3/S3'); return; }
      
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
				
				require_once('includes/S3/SimpleImage.php');
				$image = new SimpleImage();
				$image->load($fname);
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
					require_once('includes/S3/s3_config.php');
					
					//Rename image name. 
					$actual_image_name = time() . "." . $filetype[1];

					$this->validate($s3->putObjectFile($fname, $bucket , 
																						 $actual_image_name, 
																						 S3::ACL_PUBLIC_READ), 
																						 $err, 'upload failed');

					if ($this->isValid()) {
						$reply = "https://$bucket.s3.amazonaws.com/$actual_image_name";
						$this->render('S3/S3', array('reply' => "up(\"$reply\");"));
						return;
					}
				}
      }
      
      // CHANGE THIS TO AN ERROR DISPLAY FUNCTION
      $this->render('S3/S3', array('err', $err));
    }
  }

  $CS3 = new S3Controller();

?>