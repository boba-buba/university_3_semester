<?php
$name = '';
$luck = '7';
$inteligence = '7';

header("Location: ")

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Place to start ?
  $_GET[$name]

}
?>

<!doctype html>
<title>Form</title>

<form action="<?= __FILE__ ?>" method="POST">
  <div>
    <label>Name:
      <input type="text" name="name" required value="<?= htmlspecialchars($name) ?>" />
    </label>
  </div>
  <div>
    <label>Luck:
      <input type="number" name="luck" min="0" max="10" value="<?= htmlspecialchars($luck) ?>" />
    </label>
  </div>
  <div>
    <label>Inteligence:
      <input type="number" name="inteligence" min="0" max="10" value="<?= htmlspecialchars($inteligence) ?>" />
    </label>
  </div>
  <div>
    Sum of luck nad inteligence must be smaller then 14.
  </div>
  <input type="submit" value="Create character">
</form>