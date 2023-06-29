<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Simple Notes</title>
	<style type="text/css">
	body, input, button {
		font: 14pt Calibri, Verdana, Arial
	}

	table {
		border-collapse: collapse;
	}

	table td {
		padding: 8px;
		background-color: #eee;
	}
	table tr:nth-of-type(2n) td {
		padding: 8px;
		background-color: #ddd;
	}

	td.fullwidth {
		width: 100%;
	}
	
	input {
		width: 100%;
	}
	</style>
</head>

<body>
<?php $this->content(); ?>
</body>
</html>
