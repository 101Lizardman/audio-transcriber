

<?php

	$target_dir = 'php-docs-samples/speech/test/data/';
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	
	// There is no QA checks here to ensure the file is the correct type or too long, etc.
	
	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
		echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
	} else {
		echo "Sorry, there was an error uploading your file.";
	}
?>
<meta http-equiv="refresh" content="0;url=../audiotranscriber">