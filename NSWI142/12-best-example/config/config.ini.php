<?php

return [
	'Container' => [
		'NotesModel',
		'Router',
		'View',
	],
	
	'NotesModel' => [
		'data_file' => __DIR__ . '/../data/notes.txt',
	],
	
	'Router' => [
		'base' => '/vyuka/examples/1-12-best',
		'default' => 'Show',
	],

	'View' => [
		'path' => __DIR__ . '/../templates',
		'layout' => '_layout'
	],
];
