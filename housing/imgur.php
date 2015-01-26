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
				include('SimpleImage.php');
				$image = new SimpleImage();
				$image->load($_FILES['upload']['tmp_name']);
				if ($image->getHeight() > 1000)
					$image->resizeToHeight(1000);
				$image->save($_FILES['upload']['tmp_name']);
				
				function getMIME($fname) {
					$finfo = finfo_open(FILEINFO_MIME_TYPE);
					$mime = finfo_file($finfo, $fname);
					finfo_close($finfo);
					return $mime;
				}
				$filetype = explode('/', getMIME($_FILES['upload']['tmp_name']));
				if ($filetype[0] !== 'image') {
					$msg = 'Invalid image type!';
				} else {
					$image = file_get_contents($_FILES['upload']['tmp_name']);
					
					$url = 'http://api.imgur.com/2/upload.json';
					$data = array('image' => base64_encode($image), 'key' => $client_id);

					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
					curl_setopt($ch, CURLOPT_POST, TRUE);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Client-ID $client_id"));
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

					$reply = curl_exec($ch);
					curl_close($ch);

					$reply = json_decode($reply);
					//$reply->data->link;
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
			window.parent.$('iframe.imgur')
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
		if (isset($reply->data->link)) {
			echo 'up("' . $reply->data->link . '");';
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
	require_once('htmlfooter.php'); 
    require_once('footer.php');
?>