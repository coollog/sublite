<?php
	$requireLogin = true;
	require_once('header.php');	
	require_once('htmlheader.php');
	
	$msg = '';
	if (isset($_FILES['upload'])) {
		$client_id = '1b5cc674ae2f335';
		
		if ($_FILES['upload']['error'] !== 0) {
			$msg = 'Error!';
		} else {
			if ($_FILES['upload']['size'] > 10*1024*1024) {
				$msg = 'File size too large!';
			} else {
				$fname = $_FILES['upload']['tmp_name'];
				
				include('SimpleImage.php');
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
				if ($filetype[0] !== 'image' or !in_array($filetype[1], $valid_formats)) {
					$msg = 'Invalid image type!';
				} else {
					require_once('s3_config.php');
					
					//Rename image name. 
					$actual_image_name = time() . "." . $filetype[1];
					if ($s3->putObjectFile($fname, $bucket , $actual_image_name, S3::ACL_PUBLIC_READ)) {
						$reply = "https://$bucket.s3.amazonaws.com/$actual_image_name";
					} else
						$msg = "Upload failed!";
				}
			}
		}
	}
?>
<style>
	html, body {
		margin: 0;
		height: 100%;
		overflow: hidden;
	}
</style>
<script>
	$(function() {
		function resize() {
			window.parent.$('iframe.S3Upload')
				.width($('.container').width())
				.height($('.container').height());
		}
		$('.container').resize(resize);
		resize();
	});
	function up(url) {
		window.parent.addImg(url);
	}
	<?php
		if (isset($reply)) {
			echo 'up("' . $reply . '");';
		}
	?>
</script>
<div class="container">
	<div class="msg"><?php echo $msg; ?></div>
	<form method="post" enctype="multipart/form-data">
		Image (< 10MB): <input type="file" name="upload" />
		<input type="submit" value="Upload" />
	</form>
</div>
<?php
    require_once('footer.php');
?>