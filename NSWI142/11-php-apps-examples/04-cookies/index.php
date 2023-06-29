<?php
	// Creates a redirect response (sets a HTTP code 302 Redirect).
	function redirect($url) {
		if (!headers_sent()) {
			header("Location: $url");
			die();
		} else
			die('Unable to perform a redirect. The headers have been already sent.');
	}
	
	
	// Performs a self-redirect and adds parameter with error message.
	function showError($error) {
		redirect('?error=' . urlencode($error));
	}


	// Process POSTed Actions
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		// Add new cookie.
		if ($_POST['action'] == 'add') {
			if (empty($_POST['name'])) showError('The cookie has no name!');
			if (empty($_POST['value']))
				setcookie($_POST['name']);	// an empty cookie
			else
				setcookie($_POST['name'], $_POST['value']);
		} else

		// Delete one cookie
		if ($_POST['action'] == 'delete') {
			if (empty($_POST['name'])) showError('Internal Error!');
			setcookie($_POST['name'], '');	// delete cookie
		}

		// Create self-redirect (to avoid re-sending POST data and to reload the $_COOKIE array)
		redirect('?');
	}


	header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE HTML>
<html>
<head>
<title>PHP Examples - Cookies</title>
<link rel="stylesheet" href="main.css" type="text/css">
</head>

<body>
	<h1>PHP Examples - Cookies</h1>
	
	<?php
		// If error is reported, display the message ...
		if (!empty($_GET['error'])) {
	?>
	<div class="error">
	<h2>Error</h2>
	<p><?= htmlspecialchars($_GET['error']); ?></p>
	</div>
	<?php } ?>

	<h2>Cookies</h2>
	<table id="cookies">
		<tr>
			<th>Key</th>
			<th>Value</th>
			<th></th>
		</tr>
	<?php
		// List all cookies sent from the client ...
		foreach($_COOKIE as $name => $value) { ?>
			<tr><td><?= $name; ?></td><td><?= $value; ?></td><td>
				<form action="?" method="post">
					<input type="hidden" name="action" value="delete">
					<input type="hidden" name="name" value="<?= $name; ?>">
					<input type="submit" value="Remove">
				</form>
			</td></tr>
		<?php } ?>
		<tr>
			<td><input form="formAdd" type="text" name="name" maxlength="256"></td>
			<td><input form="formAdd" type="text" name="value" maxlength="256"></td>
			<td><input form="formAdd" type="submit" value="Send"></td>
		</tr>
	</table>

	<form id="formAdd" action="?" method="post">
		<input type="hidden" name="action" value="add">
	</form>

</body>
</html>
	