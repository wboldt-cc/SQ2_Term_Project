<!---
File: "EMS_EmployeeReports.php"
Project: EMS-PPS Term Project
Programmers: 
First Version: April 21, 2015
Description: This page allows the user to generate the following reports on a 
             company basis:
				-Seniority Report
				-Weekly Hours Worked Report 
				-Payroll Report
			    -Active Employees Report
				-Inactive Employees Report
--->
<html>

	<head>

		<style>
			body
			{
				background-color:#585858;
			}
		</style>

		<link rel="stylesheet" href="styles.css" type="text/css">

		<title>EMS-PPS</title>

		<?php
			// Start the session
			session_start();			
			
			$serverName = $_SESSION['serverName'];
			$userName = $_SESSION['userName'];
			$password = $_SESSION['password'];
			$databaseName = $_SESSION['databaseName'];	
			$userType = $_SESSION['userType'];
			
			$link = "";		

			$typeOfReport = "";
			$companyName = "";	
			$generatedReport = "";			
			
			date_default_timezone_set('America/Toronto');
		?>
		
	</head>


	<body>

		<div class="header">
			<br/>
			<h1>EMS-PPS</h1>
			<br/>
		</div>		
		
		<div class="menu">
			</br> <b>Operation Modes:</b> </br></br>
			<a href= "EMS_HomePage.php" >Home</a><br></br>
			<a href= "EMS_EmployeeSearch.php" >Search For Employees</a><br></br>
			<a href= "EMS_EmployeeMaintenance.php">Manage Employees</a><br></br>
			Employee Reports</br></br>
			<!-- if user is an administrator -->
			<a href= "EMS_SystemAdmin.php">System Administration</a><br></br>

		</div>

		<div class="margin">
		</div>

		<div class="content"> </br>
			<h2>Employee Reports</h2>
			<?php
			/* the following variables are used to have the report type the user selected stay selected when they submit the form */
			$sReportSelected = "";
			$whwReportSelected = "";
			$pReportSelected = "";
			$aeReportSelected = "";
			$ieReportSelected = "";
									
			if(!empty($_POST['reportToGenerateDropDown']) && !empty($_POST['companyName']))// check if the user has chosen a company and a report type
			{
				$typeOfReport = $_POST['reportToGenerateDropDown'];
				$companyName = $_POST['companyName'];
				$generatedReport = "";
				$returnedString = "";
				
				$link = mysqli_connect($serverName, $userName, $password, $databaseName);// connect to the database

				if(!$link)
				{
					//if the database connection failed send error message
					 echo "<br>Error: Could not connect to the database.";
				}
				else// we have a connection
				{
					switch($typeOfReport)// find out which report the user would like to generate
					{
					case "sReport":
						/* make the tables required to generate the report */
						$returnedString = generate_sTable($link);
						
						if($returnedString != "")// check if there was an error making the table necessary to generate the report
						{
							echo "$returnedString";
						}
						else// no error
						{
							if(!$link)
							{
								 echo "<br>Error: Could not connect to the database.";
							}
							else
							{
								$generatedReport = generate_sReport($link, $companyName);
								$generatedReport .= "Date Generated: " . date('Y-m-d');
							}
							
						}
						$sReportSelected = "selected";
						break;
					case "whwReport":
						/* make the tables required to generate the report */
						turnOffSafeUpdates($link);
						$returnedString = generate_ftPayrollTable($link);
						$returnedString .= generate_ptPayrollTable($link);
						$returnedString .= generate_snPayrollTable($link);
						turnOnSafeUpdates($link);						
						
						if($returnedString != "")// check if there was an error making the table necessary to generate the report
						{
							echo "$returnedString";
						}
						else// no error
						{
							if(!$link)
							{
								 echo "<br>Error: Could not connect to the database.";
							}
							else
							{								
								$generatedReport .= generate_whwReport($link, $companyName);
								$generatedReport .= "For Week Ending: " . date('Y-m-d');
							}
							
						}
												
						$whwReportSelected = "selected";
						break;
					case "pReport":						
						if($userType == "administrator")// make sure the user is an administrator because only they have access to the report
						{		
							/* make the tables required to generate the report */
							turnOffSafeUpdates($link);
							$returnedString = generate_ftPayrollTable($link);
							$returnedString .= generate_ptPayrollTable($link);
							$returnedString .= generate_snPayrollTable($link);						
							$returnedString .= generate_ctPayrollTable($link);
							turnOnSafeUpdates($link);
							
							if($returnedString != "")// check if there was an error making the tables necessary to generate the report
							{
								echo "$returnedString";
							}
							else// no error
							{					
								$generatedReport .= generate_pReport($link, $companyName, $userType);							
								$generatedReport .= "For Week Ending: " . date('Y-m-d');
							}
							
							$pReportSelected = "selected";
						}																			
						break;
					case "aeReport":				
						if($userType == "administrator")// make sure the user is an administrator because only they have access to the report
						{
							/* make the tables required to generate the report */
							turnOffSafeUpdates($link);						
							$returnedString = generate_ftPayrollTable($link);
							$returnedString .= generate_ptPayrollTable($link);
							$returnedString .= generate_snPayrollTable($link);
							
							$returnedString = generate_ftActiveTable($link);
							$returnedString .= generate_ptActiveTable($link);
							$returnedString .= generate_snActiveTable($link);							
							turnOnSafeUpdates($link);
						
							$returnedString .= generate_ctPayrollTable($link);
							$returnedString .= generate_ctActiveTable($link);														
							
							if($returnedString != "")// check if there was an error making the table necessary to generate the report
							{
								echo "$returnedString";
							}
							else// no error
							{
								$generatedReport .= generate_aeReport($link, $companyName, $userType);
								$generatedReport .= "Date Generated: " . date('Y-m-d');
								$aeReportSelected = "selected";
							}
						}
						
						break;
					case "ieReport":				
						if($userType == "administrator")// make sure the user is an administrator because only they have access to the report
						{
							$generatedReport .= generate_ieReport($link, $companyName, $userType);
							$generatedReport .= "Date Generated: " . date('Y-m-d');
							$ieReportSelected = "selected";
						}
						
						break;
					}
					
					$generatedReport .= "<br> &nbsp&nbspRun by: $userName<br>";
					
				}
				
				$link->close();
			}	
			else// user either hasn't chosen a report type or a company name (or both)
			{
				if(empty($_POST['companyName']))
				{
					$generatedReport .= "Please enter a name for the company to generate the reports for.<br>";
				}
				else// company name has been entered
				{
					$companyName = $_POST['companyName'];
				}
				
				if(empty($_POST['reportToGenerateDropDown']))// check if the user has selected the type of report
				{
					$generatedReport .= "Please select a report type from the drop down menu.";
				}
				else// user entered a type of report
				{
					// find which type of report the user selected and make it selected
					$typeOfReport = $_POST['reportToGenerateDropDown'];
					switch($typeOfReport)
					{
					case "sReport":
						$sReportSelected = "selected";
						break;
					case "whwReport":
						$whwReportSelected = "selected";
						break;
					case "pReport":
						$pReportSelected = "selected";
						break;
					case "aeReport":
						$aeReportSelected = "selected";
						break;
					case "ieReport":
						$ieReportSelected = "selected";
						break;
					}
				}
				
			}
			
			echo "<form method='post'>		
					What is the name of the company to display: &nbsp&nbsp&nbsp&nbsp&nbsp";
			
					$link = mysqli_connect($serverName, $userName, $password, $databaseName);// connect to the database
				
					displayCompanyDropDown($link, $companyName);		
					
					$link->close();
					
					echo "<br>Which type of report would you like to display? &nbsp
						<select name='reportToGenerateDropDown'>
										<option value=''></option>
										<option value='sReport' $sReportSelected>Seniority</option>
										<option value='whwReport' $whwReportSelected>Weekly Hours Worked</option>";
										
			if($userType == "administrator")// administrators can generate more reports
			{
				echo "<option value='pReport' $pReportSelected>Payroll</option>
					  <option value='aeReport' $aeReportSelected>Active Employees</option>
					  <option value='ieReport' $ieReportSelected>Inactive Employees</option>";
			}
			
			echo "		</select>
						<input type='submit' value='Generate Report'><br><hr>										
				  </form>";
				  
			
			echo "$generatedReport";
			
			?>

		</div>

		<div class="margin">
		</div>

		<div class="footer">
			Copyright &copy MATTHEWSOFT
		</div>
		
		<?php
		// this is where all of the functions for the page are declared
			
			/*
			 * Function: generate_sTable()
			 * Description: This function recreates the seniority_report table
			 * Parameters: $link - a connection to the database
			 * Return: An empty string or an error message if something went wrong
			 */
			function generate_sTable($link)
			{
				$returnString = "";
								
				$queryString = "DROP TABLE IF EXISTS seniority_report;";
				if(!$link->query($queryString))
				{
					$returnString = "There was a problem dropping the Seniority Table in the Database.";
				}
				else// previous query succeeded
				{
					$queryString = "CREATE TABLE seniority_report
								(
									emp_name varchar(100),
									emp_sin int,
									emp_type varchar(50),
									hire_date date,
									company_name varchar(50),
									length_of_service int
								);";
								
					if(!$link->query($queryString))
					{
						$returnString = "There was a problem creating the Seniority Table in the Database.";
					}
					else// previous query succeeded
					{
						$queryString = "INSERT INTO seniority_report
										SELECT concat(p_lastname, ', ', p_firstname), si_number, 'Fulltime', ft_date_of_hire, companyName, (DATEDIFF(CURDATE(), ft_date_of_hire))
										FROM FT_View
										JOIN Company
										ON ft_company_id = companyID
										WHERE current_status = 1;";
								
						if(!$link->query($queryString))
						{
							$returnString = "There was a problem inserting into the Seniority Table.";
						}
						else// previous query succeeded
						{
							$queryString = 	"INSERT INTO seniority_report
											SELECT concat(p_lastname, ', ', p_firstname), si_number, 'Parttime', pt_date_of_hire, companyName, (DATEDIFF(CURDATE(), pt_date_of_hire))
											FROM PT_View
											JOIN Company
											ON pt_company_id = companyID
											WHERE current_status = 1;";	
												
							if(!$link->query($queryString))
							{
								$returnString = "There was a problem inserting into the Seniority Table.";
							}
							else// previous query succeeded
							{
								$queryString = "INSERT INTO seniority_report
												SELECT concat(p_firstname, p_lastname), si_number, 'Seasonal', CONCAT(season_year, season_start_date), companyName, (DATEDIFF(CURDATE(), CONCAT(season_year, season_start_date)))
												FROM SN_View
												JOIN Seasons
												ON season = season_start_date
												JOIN Company
												ON sn_company_id = companyID;";		

								if(!$link->query($queryString))
								{
									$returnString = "There was a problem inserting into the Seniority Table.";
								}	
								else// previous query succeeded
								{
									$queryString = "INSERT INTO seniority_report
													SELECT p_lastname, si_number, 'Contract', contract_start_date, companyName, (DATEDIFF(CURDATE(), contract_start_date))
													FROM CT_View
													JOIN Company
													ON ct_company_id = companyID
													WHERE current_status = 1;";		

									if(!$link->query($queryString))
									{
										$returnString = "There was a problem inserting into the Seniority Table.";
									}
								}								
							}
						}
					}				

				}															
				
				return $returnString;
			}
			
			/*
			 * Function: generate_sReport()
			 * Description: This function generates the Seniority Report and returns it as a string
			 * Parameters: $link - a connection to the database
			               $companyName - the name of the company to generate the report for
			 * Return: The Report as a string or an error message
			 */
			function generate_sReport($link, $companyName)
			{
				$returnString = "<b>Seniority Report</b> ($companyName)<br><br>";
								
				$queryString = "SELECT * FROM seniority_report WHERE company_name=\"$companyName\" ORDER BY length_of_service DESC;";
				
				if($result = $link->query($queryString))
				{
					/* add the headings for each column */
					$returnString .= "<table border='1'>
									  <tr>
										<th>Employee Name</th>
										<th>SIN</th>
										<th>Type</th>
										<th>Date Of Hire</th>
										<th>Length of Service</th>
									  </tr>";
					
					while($row = $result->fetch_assoc())
					{					
						$serviceLengthDays = $row["length_of_service"];// the length of service is stored in days
						$serviceLength = 0;// used to hold the length of service in the correct units
						$timeUnits = "days";
						
						if($serviceLengthDays > 31)
						{
							if($serviceLengthDays > 365)
							{	
								$timeUnits = "years";
								while($serviceLengthDays > 365)
								{
									$serviceLength++;
									$serviceLengthDays = $serviceLengthDays - 365;
								}
							}
							else// measured in months
							{
								$timeUnits = "months";
								while($serviceLengthDays > 31)
								{
									$serviceLength++;
									$serviceLengthDays = $serviceLengthDays - 31;
								}
							}
							
						}
						else// measured in days
						{
							$serviceLength = $serviceLengthDays;
						}
						
						$returnString .= "<tr>
											<td>" . $row["emp_name"] . "</td>
											<td>" . $row["emp_sin"] . "</td>
											<td>" . $row["emp_type"] . "</td>
											<td>" . $row["hire_date"] . "</td>
											<td>" . $serviceLength . " $timeUnits</td>
										  </tr>";
													
					}		

					$returnString .= "</table>";
					
					$result->free();
				}
				else// query failed
				{
					$returnString = "Could not generate the report. Sorry for the inconvenience";
				}
				
				return $returnString;
			}
			
			/*
			 * Function: generate_whwReport()
			 * Description: This function generates the Weekly Hours Worked Report and returns it as a string
			 * Parameters: $link - a connection to the database
			               $companyName - the name of the company to generate the report for
			 * Return: The Report as a string or an error message
			 */
			function generate_whwReport($link, $companyName)
			{
				$returnString = "<b>Weekly Hours Worked</b> ($companyName)<br><br>";
				$queryString = "";
				$tableName = "";
								
				for($i = 1; $i <= 3; $i++)
				{				
					switch($i)
					{
					case 1:
						$queryString = "SELECT * FROM FT_hours WHERE company_id=\"$companyName\" ORDER BY worked_hours, full_name DESC;";
						$tableName = "FullTime";
						break;
					case 2:
						$queryString = "SELECT * FROM PT_hours WHERE company_id=\"$companyName\" ORDER BY worked_hours, full_name DESC;";
						$tableName = "PartTime";
						break;
					case 3:
						$queryString = "SELECT * FROM SN_hours WHERE company_id=\"$companyName\" ORDER BY worked_hours, full_name DESC;";
						$tableName = "Seasonal";
						break;
					}
					
					if($result = $link->query($queryString))
					{
						/* add the headings for each column */
						$returnString .= "<table border='1'>
									  <tr>
										<th colspan='3'>$tableName</th>
									  </tr>
									  <tr>
										<th>Employee Name</th>
										<th>SIN</th>
										<th>Hours</th>
									  </tr>";
						
						while($row = $result->fetch_assoc())
						{
							$returnString .= "<tr>
												<td>" . $row["full_name"] . "</td>
												<td>" . $row["si_num"] . "</td>
												<td>" . $row["worked_hours"] . "</td>
											  </tr>";
														
						}	
						
						$returnString .= "</table><br><hr>";				
						
						$result->free();
					}
					else// query failed
					{
						$returnString .= "<br>FAILED while trying to generate the $tableName Weekly Hours Worked report. Sorry for the inconvenience";
					}
													
				}
				
				return $returnString;
			}

			/*
			 * Function: generate_ftPayrollTable()
			 * Description: This function recreates the FT_Payroll table
			 * Parameters: $link - a connection to the database
			 * Return: An empty string or an error message if something went wrong
			 */
			function generate_ftPayrollTable($link)
			{
				$returnString = "";
								
				$queryString = "DROP TABLE IF EXISTS FT_Payroll;";
				if(!$link->query($queryString))
				{
					$returnString = "There was a problem dropping the FT_Payroll Table in the Database.";
				}
				else// previous query succeeded
				{
					$queryString = "CREATE TABLE FT_Payroll
									(
										full_name varchar(50),
										company_id varchar(50),
										si_num int,
										worked_hours float,
										hours_mon float,
										hours_tues float,
										hours_wed float,
										hours_thurs float,
										hours_fri float,
										hours_sat float,
										hours_sun float,
										weekly_pay float,
										pay_date date,
										notes varchar(100)
									);";
								
					if(!$link->query($queryString))
					{
						$returnString = "There was a problem creating the FT_payroll Table in the Database.";
					}
					else// previous query succeeded
					{
						$queryString = "INSERT INTO FT_payroll (full_name, company_id, si_num, hours_mon, hours_tues, hours_wed, hours_thurs, hours_fri, hours_sat, hours_sun, weekly_pay, pay_date)
										SELECT CONCAT(p_lastname, ', ', p_firstname), companyName, si_number, mon_hours, tues_hours, wed_hours, thurs_hours, fri_hours, sat_hours, sun_hours, salary, pay_period_start_date
										FROM FT_View
										JOIN time_cards
										ON (ft_employee_id = tc_employee_id) AND (ft_company_id = tc_company_id)
										JOIN Company
										ON ft_company_id = companyID;";
								
						if(!$link->query($queryString))
						{
							$returnString = "There was a problem inserting into the FT_payroll Table.";
						}
						else// previous query succeeded
						{
							$queryString = 	"UPDATE FT_payroll
											SET weekly_pay = weekly_pay / 52,
												worked_hours = hours_mon + hours_tues + hours_wed + hours_thurs + hours_fri + hours_sat + hours_sun,
												notes = CASE
												WHEN worked_hours < 37.5 THEN 'Not full work week'
												Else ''
											END;";	
												
							if(!$link->query($queryString))
							{
								$returnString = "There was a problem running the update on the FT_payroll Table.";
							}
						}
					}				

				}															
				
				return $returnString;
			}
			
			/*
			 * Function: generate_ptPayrollTable()
			 * Description: This function recreates the PT_Payroll table
			 * Parameters: $link - a connection to the database
			 * Return: An empty string or an error message if something went wrong
			 */
			function generate_ptPayrollTable($link)
			{
				$returnString = "";
								
				$queryString = "DROP TABLE IF EXISTS PT_Payroll;";
				if(!$link->query($queryString))
				{
					$returnString = "There was a problem dropping the PT_Payroll Table in the Database.";
				}
				else// previous query succeeded
				{
					$queryString = "CREATE TABLE PT_Payroll
									(
										full_name varchar(50),
										company_id varchar(50),
										si_num int,
										worked_hours float,
										hours_mon float,
										hours_tues float,
										hours_wed float,
										hours_thurs float,
										hours_fri float,
										hours_sat float,
										hours_sun float,
										weekly_pay float,
										pay_date date,
										notes varchar(100)
									);";
								
					if(!$link->query($queryString))
					{
						$returnString = "There was a problem creating the PT_payroll Table in the Database.";
					}
					else// previous query succeeded
					{
						$queryString = "INSERT INTO PT_payroll (full_name, company_id, si_num, hours_mon, hours_tues, hours_wed, hours_thurs, hours_fri, hours_sat, hours_sun, weekly_pay, pay_date)
										SELECT CONCAT(p_lastname, ', ', p_firstname), companyName, si_number, mon_hours, tues_hours, wed_hours, thurs_hours, fri_hours, sat_hours, sun_hours, hourlyRate, pay_period_start_date
										FROM PT_View
										JOIN time_cards
										ON (pt_employee_id = tc_employee_id) AND (pt_company_id = tc_company_id) AND (current_status = 1)
										JOIN Company
										ON pt_company_id = companyID;";
								
						if(!$link->query($queryString))
						{
							$returnString = "There was a problem inserting into the PT_payroll Table.";
						}
						else// previous query succeeded
						{
							$queryString = 	"UPDATE PT_Payroll
											SET worked_hours = hours_mon + hours_tues + hours_wed + hours_thurs + hours_fri + hours_sat + hours_sun,
												weekly_pay = (weekly_pay * worked_hours),
												notes = CASE
												WHEN worked_hours > 40 THEN (worked_hours - 40)
												ELSE ''
												END;";	
												
							if(!$link->query($queryString))
							{
								$returnString = "There was a problem running the update on the PT_Payroll Table.";
							}
						}
					}				

				}															
				
				return $returnString;
			}

			
			/*
			 * Function: generate_snPayrollTable()
			 * Description: This function recreates the SN_Payroll table
			 * Parameters: $link - a connection to the database
			 * Return: An empty string or an error message if something went wrong
			 */
			function generate_snPayrollTable($link)
			{
				$returnString = "";
								
				$queryString = "DROP TABLE IF EXISTS SN_Payroll;";
				if(!$link->query($queryString))
				{
					$returnString = "There was a problem dropping the SN_Payroll Table in the Database.";
				}
				else// previous query succeeded
				{
					$queryString = "CREATE TABLE SN_Payroll
									(
										full_name varchar(50),
										company_id varchar(50),
										si_num int,
										worked_hours float,
										hours_mon float,
										hours_tues float,
										hours_wed float,
										hours_thurs float,
										hours_fri float,
										hours_sat float,
										hours_sun float,
										pieces_mon float,
										pieces_tues float,
										pieces_wed float,
										pieces_thurs float,
										pieces_fri float,
										pieces_sat float,
										pieces_sun float,
										weekly_pieces float,
										weekly_pay float,
										pay_date date,
										notes varchar(100)
									);";
								
					if(!$link->query($queryString))
					{
						$returnString = "There was a problem creating the SN_Payroll Table in the Database.";
					}
					else// previous query succeeded
					{
						$queryString = "INSERT INTO SN_payroll (full_name, company_id, si_num, hours_mon, hours_tues, hours_wed, hours_thurs, hours_fri, hours_sat, hours_sun, pieces_mon, pieces_tues, pieces_wed, pieces_thurs, pieces_fri, pieces_sat, pieces_sun, weekly_pay, pay_date)
										SELECT CONCAT(p_lastname, ', ', p_firstname), companyName, si_number, mon_hours, tues_hours, wed_hours, thurs_hours, fri_hours, sat_hours, sun_hours, mon_pieces, tues_pieces, wed_pieces, thurs_pieces, fri_pieces, sat_pieces, sun_pieces, piece_pay, pay_period_start_date
										FROM SN_View
										JOIN time_cards
										ON (sn_employee_id = tc_employee_id) AND (sn_company_id = tc_company_id) AND (current_status = 1)
										JOIN Company
										ON sn_company_id = companyID;";
								
						if(!$link->query($queryString))
						{
							$returnString = "There was a problem inserting into the SN_payroll Table.";
						}
						else// previous query succeeded
						{
							$queryString = 	"UPDATE SN_Payroll
											SET weekly_pieces = (pieces_mon + pieces_tues + pieces_wed + pieces_thurs + pieces_fri + pieces_sat + pieces_sun),
												worked_hours = (hours_mon + hours_tues + hours_wed + hours_thurs + hours_fri + hours_sat + hours_sun),
												weekly_pay = weekly_pay * weekly_pieces,
												weekly_pay = CASE
												WHEN worked_hours > 40 THEN (weekly_pay + 150)
												END,
												notes = CASE
												WHEN weekly_pieces = (SELECT max_pieces FROM((SELECT MAX(weekly_pieces) AS max_pieces FROM SN_Payroll) AS a)) THEN 'Most productive'
												END;";	
												
							if(!$link->query($queryString))
							{
								$returnString = "There was a problem running the update on the SN_Payroll Table.";
							}
						}
					}				

				}															
				
				return $returnString;
			}

			
			/*
			 * Function: generate_ctPayrollTable()
			 * Description: This function recreates the CT_Payroll table
			 * Parameters: $link - a connection to the database
			 * Return: An empty string or an error message if something went wrong
			 */
			function generate_ctPayrollTable($link)
			{
				$returnString = "";
								
				$queryString = "DROP TABLE IF EXISTS CT_Payroll;";
				if(!$link->query($queryString))
				{
					$returnString = "There was a problem dropping the CT_Payroll Table in the Database.";
				}
				else// previous query succeeded
				{
					$queryString = "CREATE TABLE CT_Payroll
									(
										full_name varchar(50),
										company_id varchar(50),
										si_num int,
										contract_start date,
										contract_end date,
										worked_hours varchar(10),
										weekly_pay float,
										pay_date date,
										notes varchar(100)
									);";
								
					if(!$link->query($queryString))
					{
						$returnString = "There was a problem creating the CT_payroll Table in the Database.";
					}
					else// previous query succeeded
					{
						$queryString = "INSERT INTO CT_payroll (full_name, company_id, si_num, contract_start, contract_end, worked_hours, weekly_pay, pay_date)
										SELECT p_lastname, companyName, si_number, contract_Start_date, contract_stop_date, '--', fixedContractAmount , pay_period_start_date
										FROM CT_View
										JOIN time_cards
										ON (ct_employee_id = tc_employee_id) AND (ct_company_id = tc_company_id) AND (current_status = 1)
										JOIN Company
										ON ct_company_id = companyID;";
								
						if(!$link->query($queryString))
						{
							$returnString = "There was a problem inserting into the CT_payroll Table.";
						}
						else// previous query succeeded
						{
							$queryString = 	"UPDATE CT_Payroll
											SET weekly_pay = (weekly_pay * 7 / DATEDIFF(contract_end, contract_start)),
												notes = DATEDIFF(contract_end, CURDATE()) + ' days remaining';";	
												
							if(!$link->query($queryString))
							{
								$returnString = "There was a problem running the update on the CT_Payroll Table.";
							}
						}
					}				

				}															
				
				return $returnString;
			}

			
			/*
			 * Function: generate_pReport()
			 * Description: This function generates the Payroll Report and returns it as a string
			 * Parameters: $link - a connection to the database
			               $companyName - the name of the company to generate the report for
			               $userType - the type of he user
			 * Return: The Report as a string or an error message
			 */
			function generate_pReport($link, $companyName, $userType)
			{
				$returnString = "<b>Payroll Report</b> ($companyName)<br><br>";
				$tableName = "";
				$queryString = "";
				
				for($i = 1; $i <= 4; $i++)// want to display a table for each employee type
				{
					if(($i == 4) && ($userType != 'administrator'))
					{
						break;// don't want to include the contract employee report
					}
					
					switch($i)
					{
					case 1:
						$queryString = "SELECT * FROM FT_Payroll WHERE company_id=\"$companyName\";";
						$tableName = "FullTime";
						break;
					case 2:
						$queryString = "SELECT * FROM PT_Payroll WHERE company_id=\"$companyName\";";
						$tableName = "PartTime";
						break;
					case 3:
						$queryString = "SELECT * FROM SN_Payroll WHERE company_id=\"$companyName\";";
						$tableName = "Seasonal";
						break;
					case 4:
						$queryString = "SELECT * FROM CT_Payroll WHERE company_id=\"$companyName\";";
						$tableName = "Contract";
						break;
					}
					
					if($result = $link->query($queryString))
					{
						/* add the headings for each column */
						$returnString .= "<table border='1'>
										  <tr>
											<th colspan='4'>$tableName</th>
										  </tr>
										  <tr>
											<th>Employee Name</th>
											<th>Hours</th>
											<th>Gross</th>
											<th>Notes</th>
										  </tr>";
						
						while($row = $result->fetch_assoc())
						{
							$returnString .= "<tr>
												<td>" . $row["full_name"] . "</td>
												<td>" . $row["worked_hours"] . "</td>
												<td>" . $row["weekly_pay"] . "</td>
												<td>" . $row["notes"] . "</td>
											  </tr>";
														
						}	
						
						$returnString .= "</table><br><hr>";				
						
						$result->free();
					}
					else// query failed
					{
						$returnString .= "<br>FAILED while trying to generate the $tableName Payroll report. Sorry for the inconvenience";
					}									
				
				}
					
				return $returnString;
			}
			
			/*
			 * Function: generate_ftActiveTable()
			 * Description: This function recreates the FT_active table
			 * Parameters: $link - a connection to the database
			 * Return: An empty string or an error message if something went wrong
			 */
			function generate_ftActiveTable($link)
			{
				$returnString = "";
								
				$queryString = "DROP TABLE IF EXISTS FT_active;";
				if(!$link->query($queryString))
				{
					$returnString = "There was a problem dropping the FT_active Table in the Database.";
				}
				else// previous query succeeded
				{
					$queryString = "CREATE TABLE FT_active
									(
										f_name varchar(100),
										doh date,
										av_hours float,
										company_name varchar(50)
									);";
								
					if(!$link->query($queryString))
					{
						$returnString = "There was a problem creating the FT_active Table in the Database.";
					}
					else// previous query succeeded
					{
						$queryString = "INSERT INTO FT_active
										SELECT CONCAT(p_lastname, ', ', p_firstname) AS fname, ft_date_of_hire, AVG(worked_hours), company_id
										FROM FT_View
										JOIN FT_Payroll
										ON (FT_view.si_number = FT_Payroll.si_num) AND (FT_View.ft_company_id = (SELECT companyID FROM Company WHERE company_id = companyName))
										GROUP BY fname, ft_date_of_hire, company_id;";
								
						if(!$link->query($queryString))
						{
							$returnString = "There was a problem inserting into the FT_active Table.";
						}
						
					}				

				}															
				
				return $returnString;
			}	
			
			/*
			 * Function: generate_ptActiveTable()
			 * Description: This function recreates the PT_active table
			 * Parameters: $link - a connection to the database
			 * Return: An empty string or an error message if something went wrong
			 */
			function generate_ptActiveTable($link)
			{
				$returnString = "";
								
				$queryString = "DROP TABLE IF EXISTS PT_active;";
				if(!$link->query($queryString))
				{
					$returnString = "There was a problem dropping the PT_active Table in the Database.";
				}
				else// previous query succeeded
				{
					$queryString = "CREATE TABLE PT_active
									(
										f_name varchar(100),
										doh date,
										av_hours float,
										company_name varchar(50)
									);";
								
					if(!$link->query($queryString))
					{
						$returnString = "There was a problem creating the PT_active Table in the Database.";
					}
					else// previous query succeeded
					{
						$queryString = "INSERT INTO PT_active
										SELECT CONCAT(p_lastname, ', ', p_firstname) AS fname, pt_date_of_hire, AVG(worked_hours), company_id
										FROM PT_View
										JOIN PT_Payroll
										ON (PT_view.si_number = PT_Payroll.si_num) AND (PT_View.Pt_company_id = (SELECT companyID FROM Company WHERE company_id = companyName))
										GROUP BY fname, pt_date_of_hire, company_id;";
								
						if(!$link->query($queryString))
						{
							$returnString = "There was a problem inserting into the PT_active Table.";
						}
						
					}				

				}															
				
				return $returnString;
			}
			
			/*
			 * Function: generate_ctActiveTable()
			 * Description: This function recreates the CT_active table
			 * Parameters: $link - a connection to the database
			 * Return: An empty string or an error message if something went wrong
			 */
			function generate_ctActiveTable($link)
			{
				$returnString = "";
								
				$queryString = "DROP TABLE IF EXISTS CT_active;";
				if(!$link->query($queryString))
				{
					$returnString = "There was a problem dropping the CT_active Table in the Database.";
				}
				else// previous query succeeded
				{
					$queryString = "CREATE TABLE CT_active
									(
										f_name varchar(100),
										doh date,
										av_hours float,
										company_name varchar(50)
									);";
								
					if(!$link->query($queryString))
					{
						$returnString = "There was a problem creating the CT_active Table in the Database.";
					}
					else// previous query succeeded
					{
						$queryString = "INSERT INTO CT_active
										SELECT p_lastname AS fname, contract_start_date, '--', company_id
										FROM CT_View
										JOIN CT_Payroll
										ON (CT_view.si_number = CT_Payroll.si_num) AND (CT_View.ct_company_id = (SELECT companyID FROM Company WHERE company_id = companyName));";
								
						if(!$link->query($queryString))
						{
							$returnString = "There was a problem inserting into the CT_active Table.";
						}
						
					}				

				}															
				
				return $returnString;
			}
						
			/*
			 * Function: generate_snActiveTable()
			 * Description: This function recreates the SN_active table
			 * Parameters: $link - a connection to the database
			 * Return: An empty string or an error message if something went wrong
			 */
			function generate_snActiveTable($link)
			{
				$returnString = "";
								
				$queryString = "DROP TABLE IF EXISTS SN_active;";
				if(!$link->query($queryString))
				{
					$returnString = "There was a problem dropping the SN_active Table in the Database.";
				}
				else// previous query succeeded
				{
					$queryString = "CREATE TABLE SN_active
									(
										f_name varchar(100),
										doh date,
										av_hours float,
										company_name varchar(50)
									);";
								
					if(!$link->query($queryString))
					{
						$returnString = "There was a problem creating the SN_active Table in the Database.";
					}
					else// previous query succeeded
					{
						$queryString = "INSERT INTO SN_active
										SELECT CONCAT(p_lastname, ', ', p_firstname) AS fname, CONCAT(season_year, season_start_date) as season_date, AVG(worked_hours), company_id
										FROM SN_View
										JOIN SN_Payroll
										ON (SN_view.si_number = SN_Payroll.si_num) AND (SN_View.sn_company_id = (SELECT companyID FROM Company WHERE company_id = companyName))
										Join Seasons
										ON season = season_type
										GROUP BY fname, season_date, company_id;";
								
						if(!$link->query($queryString))
						{
							$returnString = "There was a problem inserting into the CT_active Table.";
						}
						
					}				

				}															
				
				return $returnString;
			}
			
			/*
			 * Function: generate_aeReport()
			 * Description: This function generates the Active Employment Report and returns it as a string
			 * Parameters: $link - a connection to the database
			               $companyName - the name of the company to generate the report for
			               $userType - the type of he user
			 * Return: The Report as a string or an error message
			 */
			function generate_aeReport($link, $companyName, $userType)
			{
				$returnString = "<b>Active Employment Report</b> ($companyName)<br><br>";
				$queryString = "";
				$tableName = "";
				
				for($i = 1; $i <= 4; $i++)// want to display a table for each employee type
				{
					if(($i == 4) && ($userType != 'administrator'))
					{
						break;// don't want to include the contract employee report
					}
					
					switch($i)
					{
					case 1:
						$queryString = "SELECT * FROM FT_active WHERE company_name=\"$companyName\";";
						$tableName = "FullTime";
						break;
					case 2:
						$queryString = "SELECT * FROM PT_active WHERE company_name=\"$companyName\";";
						$tableName = "PartTime";
						break;
					case 3:
						$queryString = "SELECT * FROM SN_active WHERE company_name=\"$companyName\";";
						$tableName = "Seasonal";
						break;
					case 4:
						$queryString = "SELECT * FROM CT_active WHERE company_name=\"$companyName\";";
						$tableName = "Contract";
						break;
					}
					
					if($result = $link->query($queryString))
					{
						/* add the headings for each column */
						$returnString .= "<table border='1'>
									  <tr>
										<th colspan='3'>FullTime</th>
									  </tr>
									  <tr>
										<th>Employee Name</th>
										<th>Date Of Hire</th>
										<th>Avg. Hours</th>
									  </tr>";
						
						while($row = $result->fetch_assoc())
						{
							$returnString .= "<tr>
												<td>" . $row["f_name"] . "</td>
												<td>" . $row["doh"] . "</td>
												<td>" . $row["av_hours"] . "</td>
											  </tr>";
														
						}	
						
						$returnString .= "</table><br><hr>";				
						
						$result->free();
					}
					else// query failed
					{
						$returnString .= "<br>FAILED while trying to generate the $tableName Active Employment report. Sorry for the inconvenience";
					}
					
				}
				
				return $returnString;
			}
			
			/*
			 * Function: generate_ieReport()
			 * Description: This function generates the Inactive Employment Report and returns it as a string
			 * Parameters: $link - a connection to the database
			               $companyName - the name of the company to generate the report for
			               $userType - the type of he user
			 * Return: The Report as a string or an error message
			 */
			function generate_ieReport($link, $companyName, $userType)
			{
				$returnString = "<b>Inactive Employment Report</b> ($companyName)<br><br>";
				$queryString = "";
				$tableName = "";
				
				for($i = 1; $i <= 4; $i++)// want to display a table for each employee type
				{
					if(($i == 4) && ($userType != 'administrator'))
					{
						break;// don't want to include the contract employee report
					}
					
					switch($i)
					{
					case 1:
						$queryString = "SELECT * FROM FT_View WHERE company_id=\"$companyName\";";
						$tableName = "FullTime";
						break;
					case 2:
						$queryString = "SELECT * FROM PT_Payroll WHERE company_id=\"$companyName\";";
						$tableName = "PartTime";
						break;
					case 3:
						$queryString = "SELECT * FROM SN_Payroll WHERE company_id=\"$companyName\";";
						$tableName = "Seasonal";
						break;
					case 4:
						$queryString = "SELECT * FROM CT_Payroll WHERE company_id=\"$companyName\";";
						$tableName = "Contract";
						break;
					}
					
					if($result = $link->query($queryString))
					{
						/* add the headings for each column */
						$returnString .= "<table border='1'>
									  <tr>
										<th>Employee Name</th>
										<th>Hired</th>
										<th>Terminated</th>
										<th>Type</th>
										<th>Reason For Leaving</th>
									  </tr>";
						
						while($row = $result->fetch_assoc())
						{
							$returnString .= "<tr>
											<td>" . $row[""] . "</td>
											<td>" . $row[""] . "</td>
											<td>" . $row[""] . "</td>
											<td>" . $row[""] . "</td>
											<td>" . $row[""] . "</td>
											<td>" . $row[""] . "</td>
										  </tr>";
														
						}	
						
						$returnString .= "</table><br><hr>";				
						
						$result->free();
					}
					else// query failed
					{
						$returnString .= "<br>FAILED while trying to generate the $tableName Inactive Employment report. Sorry for the inconvenience";
					}
					
				}
				
				return $returnString;
			}
			
			
			/* this function turns OFF safe update mode in the database */
			function turnOffSafeUpdates($link)
			{	
				$queryString = "SET SQL_SAFE_UPDATES=0;";
				$link->query($queryString);
			}
			
			/* this function turns ON safe update mode in the database */
			function turnOnSafeUpdates($link)
			{	
				$queryString = "SET SQL_SAFE_UPDATES=1;";
				$link->query($queryString);
			}
			
			/* 
			 * Function: displayCompanyDropDown()
			 * Description: This function creates a drop down menu with all of the companies currently in the database
			 * Parameters: $link - a connection to the database
						   $companyName - the last company name that was selected
			 * Return: An empty string or an error message if something went wrong
			 */
			function displayCompanyDropDown($link, $companyName)
			{
			$stringToEcho = "";
			$queryString = "SELECT companyName FROM Company";
			
				if($result = $link->query($queryString))
				{
					/* add the headings for each column */
					$stringToEcho .= "<select name='companyName'>
										<option value=''></option>";
					
					while($row = $result->fetch_assoc())
					{
						$stringToEcho .= "<option value=\"" . $row["companyName"] . "\"";
						
						if($companyName == $row["companyName"])// check if the company we just found is the last one the user selected
						{
							$stringToEcho .= " selected ";// make this company the selected on in the drop down
						}
						$stringToEcho .= ">" . $row["companyName"] . "</option>";
					}	
					
					$stringToEcho .= "</select>";				
					
					$result->free();
				}
				else// query failed
				{
					$stringToEcho .= "No companies currently exist.";
				}
				
				echo "$stringToEcho";
			}
			
			
		?>	

	</body>


</html>