<?php
include "classAlerts.php";
$Alerts = new Alerts;

if ( isset($_GET['action']) AND $_GET['action'] == "addNewEntryAPI" )
{
	if ( 
		(isset($_GET['newAlertType']) AND $_GET['newAlertType'] != "") AND
		(isset($_GET['newAlertDisplayTime']) AND $_GET['newAlertDisplayTime'] != "") AND
		(isset($_GET['newAlertSeverity']) AND $_GET['newAlertSeverity'] != "") AND
		(isset($_GET['newAlertText']) AND $_GET['newAlertText'] != "")
		)
		{
			$Alerts->addNewEntryAPI($_GET['newAlertType'],$_GET['newAlertDisplayTime'],$_GET['newAlertSeverity'],$_GET['newAlertText']);
		}
	else
	{
		echo "Failure";
	}
}

?>
