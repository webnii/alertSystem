<?php

include "db.php";

$cid = mysql_connect($configDatabaseHost, $configDatabaseUserName, $configDatabasePassword) or die ('I cannot connect to the database because: ' . mysql_error());
mysql_select_db($configDatabaseName);

class Alerts
{
	function sanitizeInput ($input)
	{
		$this->input = $input;
		if (is_array($this->input))
		{
			foreach($this->input as $var=>$val)
			{
				$output[$var] = sanitize($val);
			}
		}
		else
		{
			if (get_magic_quotes_gpc())
			{
				$this->input = stripslashes($this->input);
			}

			$output = mysql_real_escape_string($this->input);
		}
		return $output;
	}

	function addNewEntry ($newAlertType,$newAlertDisplayTime,$newAlertSeverity,$newAlertText)
	{
		$this->newAlertType = $this->sanitizeInput($newAlertType);
		$this->newAlertDisplayTime = $this->sanitizeInput($newAlertDisplayTime);
		$this->newAlertSeverity = $this->sanitizeInput($newAlertSeverity);
		$this->newAlertText = $this->sanitizeInput($newAlertText);
		
		$this->newAlertTime = date("Y-m-d H:i:s");
		
		if ($this->newAlertDisplayTime == "now")
		{
			$this->newAlertDisplayTime = date("Y-m-d H:i:s");
		}
		
		// Insert the entry
		mysql_query("INSERT INTO alertAlerts(alertType,alertTime,alertDisplayTime,alertSeverity,alertText)VALUES('$this->newAlertType', '$this->newAlertTime', '$this->newAlertDisplayTime', '$this->newAlertSeverity', '$this->newAlertText')");		
		echo "<div style=\"width: 95%; margin: auto; margin-top: 25px;\">";
		echo "<div class=\"alert alert-success\">Success! The new alert has been added!</div>";
		echo "</div>";
		
		echo "<meta http-equiv=\"refresh\" content=\"5;URL='index.php'\" />";
	}

	function addNewEntryAPI ($newAlertType,$newAlertDisplayTime,$newAlertSeverity,$newAlertText)
	{
		$this->newAlertType = $this->sanitizeInput($newAlertType);
		$this->newAlertDisplayTime = $this->sanitizeInput($newAlertDisplayTime);
		$this->newAlertSeverity = $this->sanitizeInput($newAlertSeverity);
		$this->newAlertText = $this->sanitizeInput($newAlertText);
		
		$this->newAlertTime = date("Y-m-d H:i:s");
		
		if ($this->newAlertDisplayTime == "now")
		{
			$this->newAlertDisplayTime = date("Y-m-d H:i:s");
		}
		
		// Insert the entry
		mysql_query("INSERT INTO alertAlerts(alertType,alertTime,alertDisplayTime,alertSeverity,alertText)VALUES('$this->newAlertType', '$this->newAlertTime', '$this->newAlertDisplayTime', '$this->newAlertSeverity', '$this->newAlertText')");		
		echo "Success";
	}	
	
	function clearAlert($clearAlertNum)
	{
		$this->clearAlertNum = $clearAlertNum;
		mysql_query("UPDATE alertAlerts SET alertCleared = 1 WHERE alertNum = '$this->clearAlertNum' ");	
		mysql_query("UPDATE alertAlerts SET alertClearedTime = NOW() WHERE alertNum = '$this->clearAlertNum' ");	
	}
	
	function unClearAlert($unClearAlertNum)
	{
		$this->unClearAlertNum = $unClearAlertNum;
		mysql_query("UPDATE alertAlerts SET alertCleared = 0 WHERE alertNum = '$this->unClearAlertNum' ");	
		mysql_query("UPDATE alertAlerts SET alertClearedTime = NULL WHERE alertNum = '$this->unClearAlertNum' ");	
	}	
	
	function displayAlertTable($displayTableType)
	{
		$this->displayTableType = $displayTableType;
		
		if ($this->displayTableType == "currentAlerts")
		{
			$this->queryForTable = "SELECT * FROM alertAlerts WHERE alertCleared = '0' AND NOW() >= alertDisplayTime ORDER BY alertSeverity DESC, alertDisplayTime ASC";
			echo "<div style=\"width: 95%; margin: auto; margin-top: 5px; text-align: center;\"><h4>Current Alerts</h2></div>";
		}
		else if ($this->displayTableType == "futureAlerts")
		{
			$this->queryForTable = "SELECT * FROM alertAlerts WHERE alertCleared = '0' AND NOW() < alertDisplayTime ORDER BY alertDisplayTime ASC";
			echo "<div style=\"width: 95%; margin: auto; margin-top: 5px; text-align: center;\"><h4>Future Alerts</h2></div>";
		}
		else if ($this->displayTableType == "recentlyClearedAlerts")
		{
			$this->queryForTable = "SELECT * FROM alertAlerts WHERE alertCleared = '1' ORDER BY alertClearedTime DESC LIMIT 25";
			echo "<div style=\"width: 95%; margin: auto; margin-top: 5px; text-align: center;\"><h4>Recently Cleared Alerts</h2></div>";
		}
		
		echo "<table class=\"table table-hover\" style=\"width: 95%; margin: auto; margin-top: 5px;\">";
		echo "<thead>";
		echo "<tr>";
		echo "<th style=\"width: 75px; text-align: left;\">Severity</th>";
		echo "<th style=\"width: 200px; text-align: left;\">Time</th>";
		echo "<th style=\"text-align: left;\">Alert Description</th>";
		echo "<th>&nbsp;</th>";		
		echo "</tr>";
		echo "</thead>";
		
		echo "<tbody>";
		
		$this->resultForTable = mysql_query($this->queryForTable);
		// start the list (my team)
		while ( $this->row = mysql_fetch_array($this->resultForTable) )
		{
			if ( $this->row['alertSeverity'] == 1 )
			{
				echo "<tr class=\"info\">";
				echo "<td style=\"width: 75px; text-align: left;\">INFO</td>";
			}
			else if ( $this->row['alertSeverity'] == 2 )
			{
				echo "<tr class=\"warning\">";
				echo "<td style=\"width: 75px; text-align: left;\">WARN</td>";
			}
			else if ( $this->row['alertSeverity'] == 3 )
			{
				echo "<tr class=\"danger\">";
				echo "<td style=\"width: 75px; text-align: left;\">CRIT</td>";
			}
			
			echo "<td style=\"width: 200px; text-align: left;\">". $this->row['alertDisplayTime'] ."</td>";
			
			echo "<td style=\"text-align: left;\">". $this->row['alertText'];
			if ($this->row['alertClearedTime'] != "")
			{
				echo "<br />Cleared: ".$this->row['alertClearedTime']."";
			}
			echo "</td>";
			
			if ($this->displayTableType != "recentlyClearedAlerts")
			{
				echo "<td style=\"text-align: right;\"><a class=\"btn btn-primary btn-xs\" href=\"index.php?action=clearAlert&clearAlertNum=". $this->row['alertNum'] ."\">Clear</a></td>";
			}
			else
			{
				echo "<td style=\"text-align: right;\"><a class=\"btn btn-primary btn-xs\" href=\"index.php?action=unClearAlert&unClearAlertNum=". $this->row['alertNum'] ."\">Un-Clear</a></td>";
			}
			
			echo "</tr>";
		}
		
		echo "</tbody>";
		echo "</table>";
	}
	
	function showAddEntryModal()
	{
		echo "<div class=\"modal fade\" id=\"popAlertAdd\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"popAlertAddLabel\" aria-hidden=\"true\">";
			echo "<div class=\"modal-dialog\">";
				echo "<div class=\"modal-content\">";
					echo "<div class=\"modal-header\">";
						echo "<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>";
						echo "<h4 class=\"modal-title\" id=\"popAlertAddLabel\">Add a New Alert</h4>";
					echo "</div>";
					
					echo "<div class=\"modal-body\">";
					// BODY HERE
					echo "<div class=\"bs-example\">
					<form style=\"width: 95%; margin: auto;\" method=\"GET\" action=\"index.php?\">
						<input type=\"hidden\" name=\"action\" value=\"addNewEntry\" />
						<input type=\"hidden\" name=\"newAlertType\" value=\"manual\" />
						<div class=\"row\" style=\"padding: 20px;\">
							<div class=\"col-sm-10\">
								Alert Severity
								<div class=\"input-group\">
									<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-star\"></span></span>
									<select class=\"form-control\" name=\"newAlertSeverity\" required>
										<option value=\"1\">Informational</option>
										<option value=\"2\">Warning</option>
										<option value=\"3\">Critical</option>
									</select>
								</div>
							</div>							
						</div>
						<div class=\"row\" style=\"padding: 20px;\">
							<div class=\"col-sm-10\">
								Display Alert @ Time
								<div class=\"input-group\">
									<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
									<input type=\"text\" class=\"form-control\" name=\"newAlertDisplayTime\" value=\"".date("Y-m-d H:i:s")."\" placeholder=\"".date("Y-m-d H:i:s")."\" required>
								</div>
							</div>							
						</div>		
						<div class=\"row\" style=\"padding: 20px;\">
							<div class=\"col-sm-10\">
								Alert Description
								<div class=\"input-group\">
									<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-pencil\"></span></span>
									<input type=\"text\" class=\"form-control\" name=\"newAlertText\" placeholder=\"Alert description...\" required>
								</div>
							</div>							
						</div>							
						<div class=\"row\" style=\"padding: 20px;\">
							<div class=\"col-xs-4\">
								<button class=\"btn btn-success btn-block\" type=\"submit\">Add Alert</button>
							</div>		
						</div>
					</form>
					</div>";					
					
					// BODY END
					echo "</div>";
					
					echo "<div class=\"modal-footer\">";
						echo "&nbsp;";
					echo "</div>";
				echo "</div>";
			echo "</div>";
			echo "</div>";

		
		echo "<button class=\"btn  btn-xs btn-success\" data-toggle=\"modal\" data-target=\"#popAlertAdd\">New Alert</button>";
	}
	
}

?>
