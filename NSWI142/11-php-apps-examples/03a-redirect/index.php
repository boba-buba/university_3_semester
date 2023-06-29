<?php
	require_once('common.php');

	header('Content-Type: text/html; charset=utf-8');

	// Prepare list of images [ imagename => [ tagname => selectd (true/false) ] ]
	$images = [];
	foreach(glob(DATA_DIR . '/*.jpg') as $image) {
		$image = basename($image, '.jpg');	// bare file name without extension
		$selectedTags = [];					// prepare tags index [ tagname => false ] 
		foreach ($tags as $tag)
			$selectedTags[$tag] = false;
		$images[$image] = $selectedTags;
	}
	
	// Read tags file line by line ...
	foreach (file(DATA_TAGS, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
		// Parse the line ...
		list($image, $selectedTags) = explode("\t", $line, 2);
		if (empty($images[$image])) continue;	// skip missing images
		$selectedTags = ($selectedTags) ? explode(',', $selectedTags) : [];
		foreach ($selectedTags as $tag) {
			$tag = trim($tag);
			$images[$image][$tag] = true;	// mark listed tags as selected
		}
	}
?>
<!DOCTYPE HTML>
<html>
<head>
<title>PHP Examples - POSTing a Form</title>
<link rel="stylesheet" href="main.css" type="text/css">
</head>

<body>
<h1>PHP Examples - POSTing a Form</h1>

<form action="action.php" method="POST">
<table id="images">
	<?php foreach ($images as $image => $selectedTags) {
		$image = htmlspecialchars($image, ENT_QUOTES);
	?>
	<tr>
		<td>
			<img src="<?= DATA_DIR . "/$image"; ?>.jpg" alt="<?= $image; ?>" title="<?= $image; ?>">
			<input type="hidden" name="images[]" value="<?= $image; ?>">
		</td>
		<td>
			<h2><?= $image; ?></h2>
			<?php foreach ($tags as $tag) {
				$tag = htmlspecialchars($tag, ENT_QUOTES);
			?>
			<span class="tag">
				<input type="checkbox" name="tags[<?= $image; ?>][<?= $tag; ?>]" value="1" <?= empty($selectedTags[$tag]) ? '' : 'checked'; ?>>
				<label><?= $tag; ?></label>
			</span>
			<?php } ?>
		</td>
	</tr>
	<?php } ?>
</table>

<div id="buttons"><button type="submit">Save Tags</button></div>
</form>

</body>
</html>
