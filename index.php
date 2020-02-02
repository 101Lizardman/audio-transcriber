
<?php
	define('DATA_DIR', 'testData/');
?>
<html>
<head>
	<link rel="stylesheet" href="style.css">
	<h1> Audio Transcriber </h1>
</head>
<body>
	<div id="sync" style="border: 1px solid; border-radius: 5px; padding: 1rem;">
	<h2>Sychronous transcription</h2>
		<form id="sync_selector" method='post' action='transcribe_sync.php'>
			<h3>Input</h3>
			<div>Synchronous transcription requires:<br>
				- Must be sampled at 16KHz<br>
				- Must be a FLAC file<br>
				- Must be less than 60 seconds long<br>
			</div>
			<h3>Local storage:</h3>
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
			<button type="submit">Sych Transcribe</button>
		</form>
		<div id="uploadFile">
		<h3>Upload a file to local storage</h3>
			<form action="upload.php" method="post" enctype="multipart/form-data">
				<input type="file" name="fileToUpload">
				<input type="submit" value="Upload" name="submit">
			</form>
		</div>
	</div>
	<div id="async" style="border: 1px solid; border-radius: 5px; padding: 1rem;">
	<h2>Asynchronous transcription </h2>
		<form id="async_selector" method='post' action='transcribe_async.php'>
			<h3>Input</h3>
			<div>Asyncronous transcription requires:<br>
				- Must be sampled at 16KHz<br>
				- Must be a FLAC file<br>
				- Must be stored in a GCloud bucket and publicly accessible<br>
				- Must be less than 8 hours<br><br>
			</div>
			GCloud storage URI: <input type="text" name="storageUri" />
			<input type="submit" name="gCloudURI" value="Async transcribe">
		</form>
	</div>
	<div id="async" style="border: 1px solid; border-radius: 5px; padding: 1rem;">
	<h2>Streaming transcription ### in progress ###</h2>
		<form id="streaming_selector" method='post' action='streaming_recognize.php'>
			<h3>Input</h3>
			<div>Streaming transcription requires:<br>
				- ???<br><br>
			</div>
			<input type="submit" name="streamingInput" value="Begin streaming">
		</form>
	</div>
	
</body>
<footer style="color: navy, font-size: 10px">
	<p>Shane Vincent - Swinburne Institute of Technology</p>
	<p>s100671273</p>
	<p>NPS10002</p>
</footer>
</html>
