<?php
include "classAlerts.php";
$Alerts = new Alerts;


if ( isset($_GET['action']) AND $_GET['action'] == "addNewEntry" )
{
	if ( 
		(isset($_GET['newAlertType']) AND $_GET['newAlertType'] != "") AND
		(isset($_GET['newAlertDisplayTime']) AND $_GET['newAlertDisplayTime'] != "") AND
		(isset($_GET['newAlertSeverity']) AND $_GET['newAlertSeverity'] != "") AND
		(isset($_GET['newAlertText']) AND $_GET['newAlertText'] != "")
		)
		{
			$Alerts->addNewEntry($_GET['newAlertType'],$_GET['newAlertDisplayTime'],$_GET['newAlertSeverity'],$_GET['newAlertText']);
		}
}

if ( isset($_GET['action']) AND $_GET['action'] == "clearAlert" )
{
	if ( isset($_GET['clearAlertNum']) AND $_GET['clearAlertNum'] > 0 )
		{
			$Alerts->clearAlert($_GET['clearAlertNum']);
			
			@header("Location: index.php");
		}
}

if ( isset($_GET['action']) AND $_GET['action'] == "unClearAlert" )
{
	if ( isset($_GET['unClearAlertNum']) AND $_GET['unClearAlertNum'] > 0 )
		{
			$Alerts->unClearAlert($_GET['unClearAlertNum']);
			
			@header("Location: index.php");
		}
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Alerts</title>
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
	<?php
	
	if ( !isset($_GET['alertTable']) OR (isset($_GET['alertTable']) AND $_GET['alertTable'] == 'currentAlerts') )
	{
		echo "<meta http-equiv=\"refresh\" content=\"90;URL=index.php\">";
	}
	
	?>
</head>

<body>



<?php
echo "<div style=\"width: 95%; margin: auto; margin-top: 5px;\">";
$Alerts->showAddEntryModal();

echo "&nbsp;";
echo "<a class=\"btn btn-xs btn-info\" href=\"index.php?alertTable=currentAlerts\">Current Alerts</a>";

echo "&nbsp;";
echo "<a class=\"btn btn-xs btn-info\" href=\"index.php?alertTable=futureAlerts\">Future Alerts</a>";

echo "&nbsp;";
echo "<a class=\"btn btn-xs btn-info\" href=\"index.php?alertTable=recentlyClearedAlerts\">Recently Cleared</a>";

echo "</div>";

if ( isset($_GET['alertTable']) AND $_GET['alertTable'] == "currentAlerts")
{
	$Alerts->displayAlertTable("currentAlerts");
}
else if ( isset($_GET['alertTable']) AND $_GET['alertTable'] == "futureAlerts")
{
	$Alerts->displayAlertTable("futureAlerts");
}
else if ( isset($_GET['alertTable']) AND $_GET['alertTable'] == "recentlyClearedAlerts")
{
	$Alerts->displayAlertTable("recentlyClearedAlerts");
}
else
{
	$Alerts->displayAlertTable("currentAlerts");
}

echo "<div style=\"width: 95%; margin: auto; margin-top: 25px;\">&nbsp;</div>";

?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="bootstrap.js"></script>

</body>
</html>
