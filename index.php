
<?php
	define('DATA_DIR', 'testData/');
?>
<html>
<head>
	<link rel="stylesheet" href="style.css">
	<h1> Audio Transcriber </h1>
</head>
<body>
	<div id="inputFile" style="border: 1px solid; border-radius: 5px; padding: 1rem;">
		<form id="selector" method='post' action='transcribe_async.php'>
			<h2>Input audio</h2>
			<div>The file must meet the following requirements:<br>
				- Must be sampled at 16KHz<br>
				- Must be a FLAC file<br>
				- Must be less than ??? MB
			</div>
			<h3>Select a file:</h3>
			<select name="inputFile">
				<?php
					$dir = DATA_DIR.'*';
					foreach(glob($dir) as $file)
					{
						$fileName = basename($file);
						echo '<option value='.DATA_DIR.$fileName.'>'.$fileName.'</option>';
					}
				?>
			</select>
			<button type="submit">Transcribe</button>
		</form>
	</div>
	<div id="uploadFile" style="border: 1px solid; border-radius: 5px; padding: 1rem;">
		<h3>Upload another file</h3>
		<form action="upload.php" method="post" enctype="multipart/form-data">
			<input type="file" name="fileToUpload" id="fileToUpload">
			<input type="submit" value="Upload" name="submit">
		</form>
	</div>
</body>
<footer style="color: navy, font-size: 10px">
	<p>Shane Vincent - Swinburne Institute of Technology</p>
	<p>s100671273</p>
	<p>NPS10002</p>
</footer>
</html>
