<?php

$addEntryURL = "http://www.yourdomain.com/ALERT_PATH/addNewAlert.php?action=addNewEntryAPI&newAlertType=general&newAlertDisplayTime=now&newAlertSeverity=2&newAlertText=This is a test.";
$addEntryURL = str_replace(' ', '%20', $addEntryURL);

// Use as FOPEN
//fopen($addEntryURL, "r");

// Use as INCLUDE
include $addEntryURL;


?>
