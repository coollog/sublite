<?php
	function countLines($path, $extensions = array('php', 'html', 'css', 'js')) {
		$it = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($path)
		);
		$files = array();
		foreach ($it as $file) {
			if ($file->isDir()) {
				continue;
			}
			$parts = explode('.', $file->getFilename());
			$extension = end($parts);
			if (in_array($extension, $extensions)) {
				$files[$file->getPathname()] = count(file($file->getPathname()));
			}
		}
		return $files;
	}
	$path = $_GET['p'];
	$c = countLines($path);
?>

<pre>
	<?php
		foreach ($c as $f=>$l) {
			echo "$f = $l\r\n";
		}
		echo "\r\n".array_sum($c);
	?>
</pre>