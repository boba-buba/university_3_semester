<?php

/*
 * Common stuff for both index.php (rendering HTML) and action.php (saving form data).
 */ 

define('DATA_DIR', '../shared-data');
define('DATA_TAGS', DATA_DIR . '/' . 'tags');

// Allowed tags
$tags = [ 'animal', 'thing', 'beverage', 'food', 'funny' ];

