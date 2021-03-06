<?php
/// File: EMS_EmployeeMaintenance.php
/// Project: EMS-PSS Term Project
/// Programmers: Matthew Thiessen, Willi Boldt, Ping Chang Ueng, and Tylor McLaughlin
/// First Version: April.21/2015
/// Description: This page contains the fields required to edit or enter an
///				 employee which will then be committed to the employee database
?>
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
			include 'validate.php';
			// Start the session
			session_start();
			$serverName = $_SESSION['serverName'];
			$userName = $_SESSION['userName'];
			$password = $_SESSION['password'];
			$databaseName = $_SESSION['databaseName'];	
			$userType = $_SESSION['userType'];
			$SIN = "";
			$company = "";
			$employeeType = "";
			$firstName = "";
			$lastName = "";
			$dateOfBirth = "";
			$employedWithCompany = "";
			$dateOfHire = "";
			$dateOfTermination = "null";
			$reasonForTermination = "";
			$salary = "";
			$hourlyRate = "";
			$piecePay = "";
			$status = "";
			$season = "";
			$year = "";					
			$contractCompanyName = "";
			$businessNumber = "";
			$dateOfIncorportation = "";
			$contractStartDate = "";
			$contractEndDate = "";
			$contractAmount = "";
			$insertQuery = "";
			$queryResult = "";
			$queryType = "insert";
			$saveButtonValue = "Save";
			
			//saves user input prior to the post
			if(isset($_POST['employeeTypeDropdown']))
			{
				$employeeType = $_POST['employeeTypeDropdown'];//gets the employee type
				$company = $_POST['company'];//company the employee works at
				
				//fulltime employee, fills out the fulltime
				//employee feilds
				if($employeeType == "ftEmployee")
				{
					$firstName = $_POST['firstName'];
					$dateOfBirth = $_POST['dob'];
					$SIN = $_POST['sinNumber'];
					$dateOfHire = $_POST['doh'];
					$dateOfTermination = $_POST['dot'];
					$salary = $_POST['salary'];
					$reasonForTermination = $_POST['reasonForTermination'];
				}
				//parttime employee, fills out the parttime
				//employee feilds
				if($employeeType == "ptEmployee")
				{
					$firstName = $_POST['firstName'];
					$dateOfBirth = $_POST['dob'];
					$SIN = $_POST['sinNumber'];
					$dateOfHire = $_POST['doh'];
					$dateOfTermination = $_POST['dot'];
					$hourlyRate = $_POST['hourlyRate'];
					$reasonForTermination = $_POST['reasonForTermination'];
				}
				//seasonal employee, fills out the seasonal
				//employee feilds
				if($employeeType == "sEmployee")
				{
					$firstName = $_POST['firstName'];
					$dateOfBirth = $_POST['dob'];
					$SIN = $_POST['sinNumber'];
					$year = $_POST['year'];		
					$season = $_POST['season'];
					$piecePay = $_POST['piecePay'];	
					$reasonForTermination = $_POST['reasonForTermination'];
				}
				//contract employee, fills out the contract
				//employee feilds
				if($employeeType == "cEmployee")
				{
					$dateOfIncorportation = $_POST['dob'];
					$businessNumber = $_POST['sinNumber'];
					$contractStartDate = $_POST['doh'];
					$contractEndDate = $_POST['dot'];
					$contractAmount = $_POST['contractPay'];
					$reasonForTermination = $_POST['reasonForTermination'];
				}
			}
			/* check if the search employee page sent us the information we need to display an employee */
			else if((isset($_SESSION['SINfromSearch'])) && ($_SESSION['SINfromSearch'] != "") &&
			(isset($_SESSION['CompanyFromSearch'])) && ($_SESSION['CompanyFromSearch'] != "") &&
			(isset($_SESSION['EmployeeTypeFromSearch'])) && ($_SESSION['EmployeeTypeFromSearch']!= ""))
			{
				$queryType = "update";
				echo $queryType;
				$saveButtonValue = "Save Changes";
			
				$SIN = $_SESSION['SINfromSearch'];
				$_SESSION['SINfromSearch'] = "";// reset the session variable
				
				$company = $_SESSION['CompanyFromSearch'];
				$_SESSION['CompanyFromSearch'] = "";// reset the session variable
				
				$employeeType = $_SESSION['EmployeeTypeFromSearch'];
				
				$link = mysqli_connect($serverName, $userName, $password, $databaseName);// connect to the database
			
				if(!$link)//if the database connection failed display an error message
				{
					 echo "<br>Error: Could not connect to the database.";
				}
				else// we have a connection
				{												
					$queryString = "SELECT * FROM ";

					if($employeeType != 'cEmployee')
					{
						switch($employeeType)
						{
						case 'ftEmployee':
							$queryString .= "FT_Display ";
							break;
						case 'ptEmployee':
							$queryString .= "PT_Display ";
							break;
						case 'sEmployee':
							$queryString .= "SN_Display ";
							break;								
						}

						$queryString .= "WHERE SIN='$SIN' && Company=\"$company\";";
					
						//gets info for the the employee that needs to be edited
						if($result = $link->query($queryString))
						{
							
							while($row = $result->fetch_assoc())
							{
								$firstName = $row['First_Name'];
								$lastName = $row['Last_Name'];
								$dateOfBirth = $row['Date_of_Birth'];
								
								switch($employeeType)
								{
								case 'ftEmployee':
								case 'ptEmployee':
									$dateOfHire = $row['Date_of_hire'];
									break;
								case 'sEmployee':
									$season = $row['Season'];
									$year = $row['Year'];
									break;								
								}
																		
								
								if($userType == "administrator")
								{
									$dateOfTermination =  $row['Date_of_termination'];
									$reasonForTermination = $row['Reason_for_termination'];
										  
									switch($employeeType)
									{
									case 'ftEmployee':
										$salary = $row['Salary'];
										break;
									case 'ptEmployee':
										$hourlyRate = $row['Hourly_rate'];
										break;
									case 'sEmployee':
										$piecePay = $row['Piece_Pay'];
										break;								
									}
										  
									$status = $row['Status'];
										
								}	
							}																	
							
							$result->free();
						}
						else// query failed
						{
							echo "Could not display the Employees Information. Sorry for the inconvenience";
						}																
								
					
					}
					else// it's a contract employee
					{
						$queryString .= "CT_Display WHERE Business_Number='$SIN' && Company=\"$company\";";
						
						//will only let an administrator view contract employee
						if($userType != "administrator")
						{
							echo "<br>You do not have access to Contract Employees.<br>";
						}
						else
						{
								
							if($result = $link->query($queryString))
							{
								
								while($row = $result->fetch_assoc())
								{
									$contractCompanyName = $row['Company'];
									$businessNumber  = $row['Business_Number'];
									$dateOfIncorportation = $row['Date_of_incorportation'];
									$contractStartDate = $row['Contract_start_date'];
									$contractEndDate = $row['Contract_end_date'];
									$reasonForTermination = $row['Reason_for_termination'];
									$contractAmount = $row['Contract_amount'];
									$status = $row['Status'];											
										
								}
																			
								
								$result->free();
							}
							else// query failed
							{
								echo "Could not find the Employees Information. Sorry for the inconvenience";
							}
							
						}
					
					}
					
					$link->close();		
				}
			}
			else
			{
				$SIN = "";
				$company = "";
				$employeeType = "";
				
			}

		?>
	</head>


	<body>

		<div class="header">
			<br/>
			<h1>EMS-PPS</h1>
			<br/>
		</div>
		
		
		
		<!--
		Creates the navigation menu
		-->
		<div class="menu">
			</br> <b>Operation Modes:</b> </br></br>
			<a href= "EMS_HomePage.php" >Home</a><br></br>
			<a href= "EMS_EmployeeSearch.php" >Search For Employees</a><br></br>
			Manage Employees</br></br>
			<a href= "EMS_EmployeeReports.php">Employee Reports</a><br></br>
			<!-- if user is an administrator -->
			<a href= "EMS_SystemAdmin.php">System Administration</a><br></br>
		</div>

		<div class="margin">
		</div>
		<!--
		Creates the edit menu
		-->
		<div class="content"> </br>
			<h2>Employee Maintenance</h2>
			
			Employee Type:</br>
			<form method='POST'>
			<select id='employeeTypeDropdown' name='employeeTypeDropdown' onChange="employeeFormChange()">
				<option></option>
				<option value='ftEmployee' <?php if($employeeType=='ftEmployee'){echo "selected";}?> >Full-Time Employee</option>
				<option value='ptEmployee' <?php if($employeeType=='ptEmployee'){echo "selected";}?> >Part-Time Employee</option>
				<option value='sEmployee' <?php if($employeeType=='sEmployee'){echo "selected";}?> >Seasonal Employee</option>
				<option value='cEmployee' <?php if($employeeType=='cEmployee'){echo "selected";}?> >Contract Employee</option>
			</select>
			</br></br>

			<span id='employeeFeilds'></span>

		</div>

		<div class="margin">
		</div>

		<div class="footer">
			Copyright &copy MATTHEWSOFT
		</div>
		<!--
		Dynamically sets the fields to match the employee
		that is being edited
		-->
		<script>
			function employeeFormChange()
			{		
				if(document.getElementById('employeeTypeDropdown').value == 'ftEmployee')
				{
					document.getElementById('employeeFeilds').innerHTML = "<h3>Personal Information</h3>" +
					"<table border='0'>" +
						"<tr>" +
							"<th align='left'>First Name:</th>" +
							"<td><input type='text' value='<?php echo $firstName; ?>' name='firstName'></td>" +
						"</tr>" +
						"<tr>" +
							"<th align='left'>Last Name:</th>" +
							"<td><input type='text' value='<?php echo $lastName; ?>' name='lastName'></td>" +
						"</tr>" +
						"<tr>" +
							"<th align='left'>Date Of Birth:</th>" +
							"<td><input type='date' name='dob' value='<?php echo $dateOfBirth; ?>' min='1940-01-01' max='<?php date_default_timezone_set("EST"); echo date('Y-m-d'); ?>'></td>" +
						"</tr>" +
						"<tr>" +
							"<th align='left'>SIN:</th>" +
							"<td><input type='text' value='<?php echo $SIN; ?>' name='sinNumber'></td>" +
						"</tr>" +
					"</table>" +
						"<h3>Business Information</h3>" +
					"<table>" +
						"<tr>" +
							"<th align='left'>Business:</th>" +
							"<td><input type='text' name='company' value=\"<?php echo $company; ?>\"></td>" +
						"</tr>" +
						"<tr>" +
							"<th align='left'>Date Of Hire:</th>" +
							"<td><input type='date' name='doh' value='<?php echo $dateOfHire; ?>' min='1940-01-01' max='<?php date_default_timezone_set("EST"); echo date('Y-m-d'); ?>'></td>" +
						"</tr>" +
						"<tr>" +
							"<th align='left'>Date Of Termination:</th>" +
							"<td><input type='date' name='dot' value='<?php echo $dateOfTermination; ?>' min='1940-01-01' max='<?php date_default_timezone_set("EST"); echo date('Y-m-d'); ?>'></td>" +
						"</tr>" +
						"<tr>" +
							"<th align='left'>Salary:</th>" +
							"<td><input type='text' value='<?php echo $salary; ?>' name='salary'></td>" +
						"</tr>" +
						"<tr>" +
							"<th align='left'>Reason For Termination:</th>" +
							"<td><input type='text' value='<?php echo $reasonForTermination; ?>' name='reasonForTermination'></td>" +
						"</tr>" +
						"<tr>" +
							"<td><input type='hidden' value='<?php echo $queryType; ?>' name='queryType'></td>" +
						"</tr>" +
					"</table>" +
					"</br><input type='submit' name='saveButton' value='<?php echo $saveButtonValue; ?>'></input>" +
					"</form>";
				} 
				else if(document.getElementById('employeeTypeDropdown').value == 'ptEmployee')
				{
					document.getElementById('employeeFeilds').innerHTML = "<h3>Personal Information</h3>" +
					"<table border='0'>" +
						"<tr>" +
							"<th align='left'>First Name:</th>" +
							"<td><input type='text' value='<?php echo $firstName; ?>' name='firstName'></td>" +
						"</tr>" +
						"<tr>" +
							"<th align='left'>Last Name:</th>" +
							"<td><input type='text' value='<?php echo $lastName; ?>' name='lastName'></td>" +
						"</tr>" +
						"<tr>" +
							"<th align='left'>Date Of Birth:</th>" +
							"<td><input type='date' name='dob' value='<?php echo $dateOfBirth; ?>' min='1940-01-01' max='<?php date_default_timezone_set("EST"); echo date('Y-m-d'); ?>'></td>" +
						"</tr>" +
						"<tr>" +
							"<th align='left'>SIN:</th>" +
							"<td><input type='text' value='<?php echo $SIN; ?>' name='sinNumber'></td>" +
						"</tr>" +
					"</table>" +
						"<h3>Business Information</h3>" +
					"<table>" +
						"<tr>" +
							"<th align='left'>Business:</th>" +
							"<td><input type='text' name='company' value=\"<?php echo $company; ?>\"></td>" +
						"</tr>" +
						"<tr>" +
							"<th align='left'>Date Of Hire:</th>" +
							"<td><input type='date' name='doh' value='<?php echo $dateOfHire; ?>' min='1940-01-01' max='<?php date_default_timezone_set("EST"); echo date('Y-m-d'); ?>'></td>" +
						"</tr>" +
						"<tr>" +
							"<th align='left'>Date Of Termination:</th>" +
							"<td><input type='date' name='dot' value='<?php echo $dateOfTermination; ?>' min='1940-01-01' max='<?php date_default_timezone_set("EST"); echo date('Y-m-d'); ?>'></td>" +
						"</tr>" +
						"<tr>" +
							"<th align='left'>Hourly Rate:</th>" +
							"<td><input type='text' value='<?php echo $hourlyRate; ?>' name='hourlyRate'></td>" +
						"</tr>" +
						"<tr>" +
							"<th align='left'>Reason For Termination:</th>" +
							"<td><input type='text' value='<?php echo $reasonForTermination; ?>' name='reasonForTermination'></td>" +
						"</tr>" +
						"<tr>" +
							"<td><input type='hidden' value='<?php echo $queryType; ?>' name='queryType'></td>" +
						"</tr>" +
					"</table>" +
					"</br><input type='submit' name='saveButton' value='<?php echo $saveButtonValue; ?>'></input>" +
					"</form>";
				} 
				else if(document.getElementById('employeeTypeDropdown').value == 'sEmployee')
				{
					document.getElementById('employeeFeilds').innerHTML = "<h3>Personal Information</h3>" +
					"<table border='0'>" +
						"<tr>" +
							"<th align='left'>First Name:</th>" +
							"<td><input type='text' value='<?php echo $firstName; ?>' name='firstName'></td>" +
						"</tr>" +
						"<tr>" +
							"<th align='left'>Last Name:</th>" +
							"<td><input type='text' value='<?php echo $lastName; ?>' name='lastName'></td>" +
						"</tr>" +
						"<tr>" +
							"<th align='left'>Date Of Birth:</th>" +
							"<td><input type='date' name='dob' value='<?php echo $dateOfBirth; ?>' min='1940-01-01' max='<?php date_default_timezone_set("EST"); echo date('Y-m-d'); ?>'></td>" +
						"</tr>" +
						"<tr>" +
							"<th align='left'>SIN:</th>" +
							"<td><input type='text' value='<?php echo $SIN; ?>' name='sinNumber'></td>" +
						"</tr>" +
					"</table>" +
						"<h3>Business Information</h3>" +
					"<table>" +
						"<tr>" +
							"<th align='left'>Business:</th>" +
							"<td><input type='text' name='company' value=\"<?php echo $company; ?>\"></td>" +
						"</tr>" +
						"<tr>" +
							"<th align='left'>Season:</th>" +
							"<td><input type='text' value='<?php echo $season; ?>' name='season'></td>" +
						"</tr>" +
						"<tr>" +
							"<th align='left'>Year:</th>" +
							"<td><input type='text' value='<?php echo $year; ?>' name='year'></td>" +
						"</tr>" +
						"<tr>" +
							"<th align='left'>Piece Pay:</th>" +
							"<td><input type='text' value='<?php echo $piecePay; ?>' name='piecePay'></td>" +
						"</tr>" +
						"<tr>" +
							"<th align='left'>Reason For Termination:</th>" +
							"<td><input type='text' value='<?php echo $reasonForTermination; ?>' name='reasonForTermination'></td>" +
						"</tr>" +
						"<tr>" +
							"<td><input type='hidden' value='<?php echo $queryType; ?>' name='queryType'></td>" +
						"</tr>" +
					"</table>" +
					"</br><input type='submit' name='saveButton' value='<?php echo $saveButtonValue; ?>'></input>" +
					"</form>";
				} 
				else if(document.getElementById('employeeTypeDropdown').value == 'cEmployee')
				{
					document.getElementById('employeeFeilds').innerHTML = "<h3>Personal Information</h3>" +
					"<table>" +
						"<tr>" +
							"<th align='left'>Company:</th>" +
							"<td><input type='text' value='<?php echo $company; ?>' name='company'></td>" +
						"</tr>" +
						"<tr>" +
							"<th align='left'>Date Of Incorporation:</th>" +
							"<td><input type='date' name='dob' value='<?php echo $dateOfIncorportation; ?>' min='1940-01-01' max='<?php date_default_timezone_set("EST"); echo date('Y-m-d'); ?>'></td>" +
						"</tr>" +
						"<tr>" +
							"<th align='left'>BN:</th>" +
							"<td><input type='text' value='<?php echo $businessNumber; ?>' name='sinNumber'></td>" +
						"</tr>" +
					"</table>" +
						"<h3>Business Information</h3>" +
					"<table>" +
						"<tr>" +
							"<th align='left'>Business:</th>" +
							"<td><input type='text' name='company' value=\"<?php echo $contractCompanyName; ?>\"></td>" +
						"</tr>" +
						"<tr>" +
							"<th align='left'>Contract Start Date:</th>" +
							"<td><input type='date' name='doh' value='<?php echo $contractStartDate; ?>' min='1940-01-01' max='<?php date_default_timezone_set("EST"); echo date('Y-m-d'); ?>'></td>" +
						"</tr>" +
						"<tr>" +
							"<th align='left'>Contract Stop Date:</th>" +
							"<td><input type='date' name='dot' value='<?php echo $contractEndDate; ?>' min='1940-01-01' max='<?php date_default_timezone_set("EST"); echo date('Y-m-d'); ?>'></td>" +
						"</tr>" +
						"<tr>" +
							"<th align='left'>Contract Pay:</th>" +
							"<td><input type='text' value='<?php echo $contractAmount; ?>' name='contractPay'></td>" +
						"</tr>" +
						"<tr>" +
							"<th align='left'>Reason For Termination:</th>" +
							"<td><input type='text' value='<?php echo $reasonForTermination; ?>' name='reasonForTermination'></td>" +
						"</tr>" +
						"<tr>" +
							"<td><input type='hidden' value='<?php echo $queryType; ?>' name='queryType'></td>" +
						"</tr>" +
					"</table>" +"</br><input type='submit' name='saveButton' value='<?php echo $saveButtonValue; ?>'></input>" +
					"</form>";
				}
			}
			
			employeeFormChange();
		</script>
		
		<?php
			$link = mysqli_connect($serverName, $userName, $password, $databaseName);// connect to the database
			if(!$link)//if the database connection failed display an error message
			{
				 echo "<br>Error: Could not connect to the database.";
			}
			else// we have a connection
			{
				//checks to see what employee type is being edited/created
				//will validate each field and submit to the database
				//only if it is all valid
				if(isset($_POST['employeeTypeDropdown']))
				{
					$queryType = $_POST['queryType'];
					$validEntry = 0;
					
					//validate a fulltime employee
					if($_POST['employeeTypeDropdown'] == 'ftEmployee')
					{	
						//validates first name
						if(ValidateName($firstName, $errorMessage) == 1)
						{
							echo "First Name</br>";
							echo $errorMessage;
							echo "</br></br>";
							$validEntry = 1;
						}//validates last name
						if(ValidateName($lastName, $errorMessage) == 1)
						{
							echo "Last Name</br>";
							echo $errorMessage;
							echo "</br></br>";
							$validEntry = 1;
						}//validates SIN
						if(ValidateSocialInsuranceNumber($SIN, $errorMessage) == 1)
						{
							echo "Social Insurance Number</br>";
							echo $errorMessage;
							echo "</br></br>";
							$validEntry = 1;
						}//validates date of birth
						if(ValidateDateOfBirth($dateOfBirth, $dateOfHire, $dateOfTermination, $errorMessage) == 1)
						{
							echo "Date Of Birth</br>";
							echo $errorMessage;
							echo "</br></br>";
							$validEntry = 1;
						}//validates date of birth
						if(ValidateDateOfHire($dateOfBirth, $dateOfHire, $dateOfTermination, $errorMessage))
						{
							echo "Date Of Hire</br>";
							echo $errorMessage;
							echo "</br></br>";
							$validEntry = 1;
						}//validates date of termination
						if(ValidateDateOfTermination($dateOfBirth, $dateOfHire, $dateOfTermination, $errorMessage))
						{
							echo "Date Of Termination</br>";
							echo $errorMessage;
							echo "</br></br>";
							$validEntry = 1;
						}//validates salary
						if(ValidateSalary($salary, $errorMessage))
						{
							echo "Salary</br>";
							echo $errorMessage;
							echo "</br></br>";
							$validEntry = 1;
						}//creates the insert query if this is a new employee
						if($queryType == "insert")
						{
							$insertQuery = "INSERT INTO Person (p_firstname, p_lastname, si_number, date_of_birth)";
							$insertQuery .= " VALUES ('".  $firstName . "', '".  $lastName ."', ".  $SIN .", '".  $dateOfBirth ."');";
								
							$insertQuery .= "INSERT INTO Employee (emp_id, person_id)";
							$insertQuery .= " VALUES (LAST_INSERT_ID(), LAST_INSERT_ID());";

							$insertQuery .= "INSERT INTO fulltime_employee";
							//checks to see if date of termination was included, otherwise
							//send a query without the date of termination
							if($dateOfTermination != "")
							{
								$insertQuery .= " VALUES (LAST_INSERT_ID(), (SELECT companyID FROM Company WHERE companyName = \"". $company ."\"), '". $dateOfHire ."', '". $dateOfTermination ."', '". $reasonForTermination ."', ". $salary .", (SELECT status_id FROM Employee_Status WHERE status_type = 'Active'));";
							}
							else
							{
								$insertQuery .= " VALUES (LAST_INSERT_ID(), (SELECT companyID FROM Company WHERE companyName = \"". $company ."\"), '". $dateOfHire ."', null, '". $reasonForTermination ."', ". $salary .", (SELECT status_id FROM Employee_Status WHERE status_type = 'Active'));";
							}
						}//if an employee is being updated, update the specified feild
						else if($queryType == "update")
						{
							$insertQuery = "UPDATE Person
											SET p_firstname = '" . $firstName . "', 
											p_lastname = '" . $lastName ."', 
											si_number = " . $SIN . ", 
											date_of_birth = '" . $dateOfBirth . "' 
											WHERE si_number = " . $SIN . ";
											UPDATE fulltime_employee 
											JOIN Employee ON ft_employee_id = empID
											JOIN Person ON person_id = p_id
											SET date_of_hire = '" . $dateOfHire . "', 
											date_of_termination = '" . $dateOfTermination . "', 
											reason_for_termination = '" . $reasonForTermination . "', 
											salary = " . $salary . " 
											WHERE ;";
						}
						if($validEntry != 1)
						{
							$queryResult = $link->multi_query($insertQuery);
						}
						//checks to see if the query was successful
						if(!$queryResult)
						{
							echo "Could Not Add Employee";
						}
					}//validate a parttime employee if it was specified
					else if($_POST['employeeTypeDropdown'] == 'ptEmployee')
					{
						//validates first name
						if(ValidateName($firstName, $errorMessage) == 1)
						{
							echo "First Name</br>";
							echo $errorMessage;
							echo "</br></br>";
							$validEntry = 1;
						}//validates lastname
						if(ValidateName($lastName, $errorMessage) == 1)
						{
							echo "Last Name</br>";
							echo $errorMessage;
							echo "</br></br>";
							$validEntry = 1;
						}//validates SIN
						if(ValidateSocialInsuranceNumber($SIN, $errorMessage) == 1)
						{
							echo "Social Insurance Number</br>";
							echo $errorMessage;
							echo "</br></br>";
							$validEntry = 1;
						}//validates date of birth
						if(ValidateDateOfBirth($dateOfBirth, $dateOfHire, $dateOfTermination, $errorMessage) == 1)
						{
							echo "Date Of Birth</br>";
							echo $errorMessage;
							echo "</br></br>";
							$validEntry = 1;
						}//validates date of hire
						if(ValidateDateOfHire($dateOfBirth, $dateOfHire, $dateOfTermination, $errorMessage))
						{
							echo "Date Of Hire</br>";
							echo $errorMessage;
							echo "</br></br>";
							$validEntry = 1;
						}//validates date of termination
						if(ValidateDateOfTermination($dateOfBirth, $dateOfHire, $dateOfTermination, $errorMessage))
						{
							echo "Date Of Termination</br>";
							echo $errorMessage;
							echo "</br></br>";
							$validEntry = 1;
						}//validate salary
						if(ValidateSalary($hourlyRate, $errorMessage))
						{
							echo "Hourly Rate</br>";
							echo $errorMessage;
							echo "</br></br>";
							$validEntry = 1;
						}//if the employee is created, use an insert statement
						if($queryType == "insert")
						{
							$insertQuery = "INSERT INTO Person (p_firstname, p_lastname, si_number, date_of_birth)";
							$insertQuery .= " VALUES ('".  $firstName . "', '".  $lastName ."', ".  $SIN .", '".  $dateOfBirth ."');";
							
							$insertQuery .= "INSERT INTO Employee (emp_id, person_id)";
							$insertQuery .= " VALUES (LAST_INSERT_ID(), LAST_INSERT_ID());";

							$insertQuery .= "INSERT INTO parttime_employee";
							
							//checks to see if the date of termination was included
							//otherwise do not include in the statement
							if($dateOfTermination != "")
							{
								$insertQuery .= " VALUES (LAST_INSERT_ID(), (SELECT companyID FROM Company WHERE companyName = \"". $company ."\"), '". $dateOfHire ."', '". $dateOfTermination ."', '". $reasonForTermination ."', ". $hourlyRate .", (SELECT status_id FROM Employee_Status WHERE status_type = 'Active'));";
							}
							else
							{
								$insertQuery .= " VALUES (LAST_INSERT_ID(), (SELECT companyID FROM Company WHERE companyName = \"". $company ."\"), '". $dateOfHire ."', null, '". $reasonForTermination ."', ". $hourlyRate .", (SELECT status_id FROM Employee_Status WHERE status_type = 'Active'));";
							}
						}//if the employee is being edited, us an update statement 
						else if($queryType == "update")
						{
							$insertQuery = "UPDATE Person
											SET p_firstname = '" . $firstName . "', 
											p_lastname = '" . $lastName ."', 
											si_number = " . $SIN . ", 
											date_of_birth = '" . $dateOfBirth . "' 
											WHERE si_number = " . $SIN . ";
											UPDATE parttime_employee 
											JOIN Employee ON ft_employee_id = empID
											JOIN Person ON person_id = p_id
											SET date_of_hire = '" . $dateOfHire . "', 
											date_of_termination = '" . $dateOfTermination . "', 
											reason_for_termination = '" . $reasonForTermination . "', 
											salary = " . $hourlyRate . " 
											WHERE ;";
						}
						
						if($validEntry != 1)
						{
							$queryResult = $link->multi_query($insertQuery);
						}
						//checks to see if the query passed or failed
						if(!$queryResult)
						{
							echo "Could Not Add Employee";
						}
					}//validates a seasonal employee if that is what is selected
					else if($_POST['employeeTypeDropdown'] == 'sEmployee')
					{
						//validates first name
						if(ValidateName($firstName, $errorMessage) == 1)
						{
							echo "First Name</br>";
							echo $errorMessage;
							echo "</br></br>";
							$validEntry = 1;
						}//validates last name
						if(ValidateName($lastName, $errorMessage) == 1)
						{
							echo "Last Name</br>";
							echo $errorMessage;
							echo "</br></br>";
							$validEntry = 1;
						}//validates SIN
						if(ValidateSocialInsuranceNumber($SIN, $errorMessage) == 1)
						{
							echo "Social Insurance Number</br>";
							echo $errorMessage;
							echo "</br></br>";
							$validEntry = 1;
						}//validates season
						if(ValidateSeason($season, $errorMessage))
						{
							echo "Season</br>";
							echo $errorMessage;
							echo "</br></br>";
							$validEntry = 1;
						}//validates piece pay
						if(ValidatePiecePay($piecePay, $errorMessage))
						{
							echo "Piece Pay</br>";
							echo $errorMessage;
							echo "</br></br>";
							$validEntry = 1;
						}//if the employee is being created use
						//an insert statement
						if($queryType == "insert")
						{
							$insertQuery = "INSERT INTO Person (p_firstname, p_lastname, si_number, date_of_birth)";
							$insertQuery .= " VALUES ('".  $firstName . "', '".  $lastName ."', ".  $SIN .", '".  $dateOfBirth ."');";

							$insertQuery .= "INSERT INTO Employee (emp_id, person_id)";
							$insertQuery .= " VALUES (LAST_INSERT_ID(), LAST_INSERT_ID()); ";
							
							$insertQuery .= "INSERT INTO seasonal_employee";
							$insertQuery .= " VALUES (LAST_INSERT_ID(), (SELECT companyID FROM Company WHERE companyName = \"". $company ."\"), '". $season ."', ". $year .", ". $piecePay .", '". $reasonForTermination ."', (SELECT status_id FROM Employee_Status WHERE status_type = 'Active'));";
						}//if the employee is being created
						//use an update statement 
						else if($queryType == "update")
						{
							$insertQuery = "UPDATE Person
											SET p_firstname = '" . $firstName . "', 
											p_lastname = '" . $lastName ."', 
											si_number = " . $SIN . ", 
											date_of_birth = '" . $dateOfBirth . "' 
											WHERE si_number = " . $SIN . ";
											UPDATE seasonal_employee 
											JOIN Employee ON ft_employee_id = empID
											JOIN Person ON person_id = p_id
											SET date_of_hire = '" . $dateOfHire . "', 
											date_of_termination = '" . $dateOfTermination . "', 
											reason_for_termination = '" . $reasonForTermination . "', 
											salary = " . $hourlyRate . " 
											WHERE ;";
						}
						if($validEntry != 1)
						{
							$queryResult = $link->multi_query($insertQuery);
						}
						//checks to ensure the query succeeds
						if(!$queryResult)
						{
							echo "Could Not Add Employee";
						}
					}//validates a contract employee if that selected
					else if($_POST['employeeTypeDropdown'] == 'cEmployee')
					{
						//validates first name
						if(ValidateName($firstName, $errorMessage) == 1)
						{
							echo "First Name</br>";
							echo $errorMessage;
							echo "</br></br>";
							$validEntry = 1;
						}//validates last name
						if(ValidateName($lastName, $errorMessage) == 1)
						{
							echo "Last Name</br>";
							echo $errorMessage;
							echo "</br></br>";
							$validEntry = 1;
						}//validates date of creation
						if(ValidateDateOfCreation($businessNumber, $dateOfIncorportation, $errorMessage) == 1)
						{
							echo "Date Of Creation</br>";
							echo $errorMessage;
							echo "</br></br>";
							$validEntry = 1;
						}//validates contract start date
						if(ValidateContractStartDate($dateOfIncorportation, $contractStartDate, $contractEndDate, $errorMessage) == 1)
						{
							echo "Contract Start Date</br>";
							echo $errorMessage;
							echo "</br></br>";
							$validEntry = 1;
						}//validates contract stop date
						if(ValidateContractStopDate($dateOfIncorportation, $contractStartDate, $contractEndDate, $errorMessage) == 1)
						{
							echo "Contract Stop Date</br>";
							echo $errorMessage;
							echo "</br></br>";
							$validEntry = 1;
						}//validates fixed contract amount
						if(ValidateFixedContractAmount($contractAmount, $errorMessage) == 1)
						{
							echo "Contract Amount</br>";
							echo $errorMessage;
							echo "</br></br>";
							$validEntry = 1;
						}
						//if the employee is being created
						//use an insert statement
						if($queryType == "insert")
						{
							$insertQuery = "INSERT INTO Person (p_firstname, p_lastname, si_number, date_of_birth)";
							$insertQuery .= " VALUES ('".  $firstName . "', '".  $lastName ."', ".  $businessNumber .", '".  $dateOfIncorportation ."');";

							$insertQuery .= "INSERT INTO Employee (emp_id, person_id)";
							$insertQuery .= " VALUES (LAST_INSERT_ID(), LAST_INSERT_ID());";

							$insertQuery .= "INSERT INTO Contract_Employee";
							$insertQuery .= " VALUES (LAST_INSERT_ID(), (SELECT companyID FROM Company WHERE companyName = \"". $company ."\"), '". $contractStartDate ."', '". $contractEndDate ."', ". $contractAmount .",'". $reasonForTermination ."', (SELECT status_id FROM Employee_Status WHERE status_type = 'Active'));";
						}
						//if the employee is being edited
						//use an insert statement
						else if($queryType == "update")
						{
							$insertQuery = "UPDATE Person
											SET p_firstname = '" . $firstName . "', 
											p_lastname = '" . $lastName ."', 
											si_number = " . $SIN . ", 
											date_of_birth = '" . $dateOfBirth . "' 
											WHERE si_number = " . $SIN . ";
											UPDATE Contract_Employee 
											JOIN Employee ON ft_employee_id = empID
											JOIN Person ON person_id = p_id
											SET date_of_hire = '" . $contractStartDate . "', 
											date_of_termination = '" . $contractEndDate . "', 
											reason_for_termination = '" . $reasonForTermination . "', 
											salary = " . $contractAmount . " 
											WHERE ;";
						}
						
						if($validEntry != 1)
						{
							$queryResult = $link->multi_query($insertQuery);
						}
						//checks to see if the query failed
						if(!$queryResult)
						{
							echo "Could Not Add Employee";
						}
					}
				}
			}//close the link
			$link->close();	
		?>
	</body>
</html>