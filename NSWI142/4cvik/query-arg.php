<!DOCTYPE html>

<title>Query-arguments</title>
<body>
<?php
    function getPage() {
        if (isset($_GET['page'])) {
            return $_GET['page'];
        } else {
            return ' ';
        }
    }

$page = getPage();
?>

<div>
    State:<br />

    <?php 
      Page: 1  <a href="?&page=0">Previous</a>
    <a href="?&page=2">Next</a>
  </div>
  
  <hr>
  
  <div>
    URL Query Arguments:<br />
    page :  <br/></div>
</body>
    