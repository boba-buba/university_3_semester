<?php

require_once('common.php');

if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST' && isset($_POST['images'])) {
	// Rewrite data/tags file ...
	$fp = fopen(DATA_TAGS, 'w');
	if (!$fp) die("Unable to open " . DATA_TAGS . " for writing...");

	// Process records from the POST ...
	foreach ($_POST['images'] as $image) {
		if (preg_match('#[./\s]#', $image) || !file_exists(DATA_DIR . "/$image.jpg")) continue;

		// Prepare tags for current image ...
		$writeTags = [];
		foreach ($tags as $tag)
			if (!empty($_POST['tags'][$image][$tag])) $writeTags[] = $tag;

		if ($writeTags)		// ... and write them to the file.
			fwrite($fp, $image . "\t" . join(',', $writeTags) . "\n");
	}
	fclose($fp);
}


header("Location: index.php");		// Create HTTP 302 redirection.
// ... there is nothing more to do.
