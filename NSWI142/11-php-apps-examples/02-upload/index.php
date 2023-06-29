<?php
	header('Content-Type: text/html; charset=utf-8');

	// Known error messages ...
	$uploadErrorMessages = [
		UPLOAD_ERR_INI_SIZE		=> 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
		UPLOAD_ERR_FORM_SIZE	=> 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
		UPLOAD_ERR_PARTIAL		=> 'The uploaded file was only partially uploaded.',
		UPLOAD_ERR_NO_FILE		=> 'No file was uploaded. Probably no file was selected by the user.',
		UPLOAD_ERR_NO_TMP_DIR	=> 'Missing a temporary folder.',
		UPLOAD_ERR_CANT_WRITE	=> 'Failed to write file to disk.',
		UPLOAD_ERR_EXTENSION	=> 'A PHP extension stopped the file upload.',
	];

	// Redirecting function (retuns HTTP response 302 - HTTP Redirect)
	function redirect($url) {
		if (!headers_sent()) {
			header('Location: ' . $url);
			exit;
		} else
			die('Redirect unsuccessfull. Headers are already sent.');
	}
	
	// Self redirect with an error parameter
	function showError($error) {
		redirect('?error=' . urlencode($error));
	}


	// Handling POST requests ...
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		// Handle a file upload
		if (!empty($_FILES['newfile'])) {
			if ($_FILES['newfile']['error'] != UPLOAD_ERR_OK) {
				$error = $_FILES['newfile']['error'];
				if (!empty($uploadErrorMessages[$error]))
					$error = $uploadErrorMessages[$error];
				else
					$error = "Unknown error #$error.";
				showError("Upload error: $error");
			}
			if (!move_uploaded_file($_FILES['newfile']['tmp_name'], 'upload/' . $_FILES['newfile']['name']))
				showError('Error moving file to upload directory!');
		}
		
		// Delete file in uploaded dir
		if (!empty($_POST['delete'])) {
			unlink('upload/' . $_POST['delete']);
		}

		redirect('?');	// redirect and die
	}
?>
<!DOCTYPE HTML>
<html>
<head>
<title>PHP Examples - Uploading Files</title>
<link rel="stylesheet" href="main.css" type="text/css">
</head>

<body>
<h1>PHP Examples - Uploading Files</h1>

<?php
	// If error is reported, display the message ...
	if (!empty($_GET['error'])) {
?>
<div class="error">
<h2>Error</h2>
<p><?= htmlspecialchars($_GET['error']); ?></p>
</div>
<?php } ?>


<h2>Uploaded Files</h2>
<table id="files">
<tr>
	<th>File</th>
	<th>Size</th>
	<th></th>
</tr>
<?php
	// List uploaded files directory
	$dir = scandir('upload');
	foreach($dir as $file) if (($file != '.') && ($file != '..')) { ?>
		<tr>
			<td><a href="upload/<?= $file; ?>"><?= $file; ?></a></td>
			<td><?= filesize("upload/$file"); ?> B</td>
			<td>
				<form action="?" method="post">
					<input type="hidden" name="delete" value="<?= $file; ?>">
					<input type="submit" value="Delete">
				</form>
			</td>
		</tr>
	<?php } ?>
</table>

<h2>Upload a New File</h2>
<form action="" method="post" enctype="multipart/form-data">
	<input type="file" name="newfile">
	<input type="submit" value="Upload">
</form>

</body>
</html>
