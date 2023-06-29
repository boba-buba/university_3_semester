<?php
	/**
	 * Fetch the complete result from SQL query and format it into an array or array of arrays.
	 * @param $sqlRes The result of mysqli query.
	 * @param $key The name of the key column. If set the result array is an associative array
	 *		where keys are taken from given column. Otherwise, the array is indexed by numbers.
	 * @param $value Name of the value column. If set, only values of this column are included
	 *		for each result row. Otherwise, entire result row is kept as associative array.
	 * @return Array [ key => value ] or [ key => [ col1, col2, ... ] ] based on given $value.
	 *		The 'key' is either seq. number of value taken from col named $key. 
	 */
	function process_sql_result($sqlRes, $key = null, $value = null)
	{
		$res = [];
		while (($row = mysqli_fetch_assoc($sqlRes)) !== null) {
			// Get the result row being inserted.
			if ($value) {
				if (!array_key_exists($value, $row))
					throw new Exception("Unable to select given value column '$value' from the SQL result.");
				$resRow = $row[$value];
			}
			else
				$resRow = $row;
			
			// Write the result row into the processed result based on the key selection.
			if ($key) {
				if (!array_key_exists($key, $row))
					throw new Exception("Unable to select given key column '$key' from the SQL result.");
				$res[ $row[$key] ] = $resRow; 
			}
			else
				$res[] = $resRow;
		}
		return $res;
	}


	// Initialize database connection ...
	$mysqli = mysqli_connect("localhost", "php", "php", "mff_examples");
	if (!$mysqli) die("Unable to connect to the database.");
	mysqli_set_charset($mysqli, "utf8") || die("Seting MySQL charset failed");
	
	// Get list of all users ...
	$sqlRes = mysqli_query($mysqli, "SELECT id, CONCAT(given_name, ' ', surname) AS name FROM todo_user ORDER BY surname, given_name");
	$users = process_sql_result($sqlRes, 'id', 'name');
/////////////////////////////////////////////////////
	// If a user is selected, load his/her TODO list.
	if (!empty($_GET['user']) && isset($users[ (int)$_GET['user'] ])) {
		$selectedUser = (int)$_GET['user'];

		// Prepare new MySQL statement
		$stmt = mysqli_stmt_init($mysqli);
		if (!mysqli_stmt_prepare($stmt, 'SELECT * FROM todo_list WHERE todo_user_id = ? ORDER BY completed, priority DESC'))
			die("Unable to prepare SELECT FROM todo_list.");

		// Bind selected user as a parameter for WHERE clause.
		if (!mysqli_stmt_bind_param($stmt, "i", $selectedUser))
			die("Unable to bind selected user to todo_list SELECT.");

		// Execute the statement and fetch its results ...
		if (!mysqli_stmt_execute($stmt))
			die("Executing SELECT FROM todo_list failed.");
		$sqlRes = mysqli_stmt_get_result($stmt);
		$todo = process_sql_result($sqlRes, 'id');
		mysqli_stmt_close($stmt);
	}
	else
		$selectedUser = null;	// no user selected
//////////////////////////////////////////////////////////
?>
<!DOCTYPE HTML>
<html>
<head>
<title>PHP Examples - MySQL</title>
<link rel="stylesheet" href="main.css" type="text/css">
</head>

<body>
	<h1>PHP Examples - MySQL</h1>
	<form action="?" method="GET">
		<select name="user" autocomplete="off">
			<option value="0" <?= (!$selectedUser) ? 'selected' : ''; ?>>---</option>
			<?php foreach ($users as $id => $name) { ?>
			<option value="<?= $id; ?>" <?= ($selectedUser == $id) ? 'selected' : ''; ?>><?= htmlspecialchars($name); ?></option>
			<?php } ?>
		</select>
		<button>Select</button>
	</form>

<?php if ($selectedUser !== null) { ?>
	<h2>TODO list of user <?= htmlspecialchars($users[$selectedUser]); ?></h2>
	<?php if ($todo) { ?>
		<ol>
		<?php foreach($todo as $item) { ?>
			<li class="<?= ($item['completed']) ? 'completed' : '' ?>"><?= htmlspecialchars($item['task_description']); ?></li>
		<?php } ?>
		</ol>
	<?php } else { ?>
	<p>Selected user has no TODO items...</p>
	<?php } ?>
<?php } ?>

</body>
</html>
