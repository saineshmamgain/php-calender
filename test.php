<?php
	/*
	 * Created by: Sainesh Mamgain
	 * @Date: 2016-03-14
	 */
	require 'calender.php';
?>
<!doctype html>
<html lang = "en">
<head>

	<meta charset = "UTF-8">
	<title>Calendar</title>

	<link rel = "stylesheet" href = "http://fonts.googleapis.com/css?family=Lato:300,400,700">
	<link rel = "stylesheet" href = "css/style.css">
</head>
<body>
<?php
	$d = new Calender(isset($_GET['month'])?$_GET['month']:null, isset($_GET['year'])?$_GET['year']:null);
	$d->markEventsByDay([1,2]);
	$d->markEventsAfterDays(7);
	$d->dataAttribute=true;
	$d->render();
?>
	</body>
</html>