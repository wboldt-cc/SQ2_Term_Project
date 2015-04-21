<!---
Filename: "Home Page.html"
Programmer: William Boldt
Date: December 8, 2013
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
			
			if(isset($_POST['employeeTypeDropdown']))
			{
				$employeeType = $_POST['employeeTypeDropdown'];
				$lastName = $_POST['lastName'];
				$company = $_POST['company'];
				
				
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
				
				if($employeeType == "cEmployee")
				{
					$dateOfIncorportation = $_POST['dob'];
					$businessNumber = $_POST['sinNumber'];
					$contractStartDate = $_POST['doh'];
					$contractEndDate = $_POST['dot'];
					$contractAmount = $_POST['contractPay'];
					$reasonForTermination = $_POST['reasonForTermination'];
				}
				//$contractCompanyName = "";
				//$status = $_POST[''];
				//$company = "";
				//$employeeType = "";
				//$employedWithCompany = $_POST[''];
				//$reasonForTermination = $_POST[''];
			}
			/* check if the search employee page sent us the information we need to display an employee */
			else if((isset($_SESSION['SINfromSearch'])) && ($_SESSION['SINfromSearch'] != "") &&
			(isset($_SESSION['CompanyFromSearch'])) && ($_SESSION['CompanyFromSearch'] != "") &&
			(isset($_SESSION['EmployeeTypeFromSearch'])) && ($_SESSION['EmployeeTypeFromSearch']!= ""))
			{
				$SIN = $_SESSION['SINfromSearch'];
				$_SESSION['SINfromSearch'] = "";// reset the session variable
				
				$company = $_SESSION['CompanyFromSearch'];
				$_SESSION['CompanyFromSearch'] = "";// reset the session variable
				
				$employeeType = $_SESSION['EmployeeTypeFromSearch'];
				//$_SESSION['EmployeeTypeFromSearch'] = "";// reset the session variable
				
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
					
					
						if($result = $link->query($queryString))
						{
							
							while($row = $result->fetch_assoc())
							{
								$firstName = $row['First_Name'];
								$lastName = $row['Last_Name'];
								//SIN: " . $row['SIN'] . "</br>
								$dateOfBirth = $row['Date_of_Birth'];
								
								//Employed with Company: " . $row['Company'] . " </br>";
								
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
							//echo "<hr>$queryString";
						}																
								
					
					}
					else// it's a contract employee
					{
						$queryString .= "CT_Display WHERE Business_Number='$SIN' && Company=\"$company\";";
						
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
									$contractCompanyName = $row['Contract_company_name'];
									$businessNumber  = $row['Business_Number'];
									$dateOfIncorportation = $row['Date_of_incorportation'];
									$contractStartDate = $row['Contract_start_date'];
									$contractEndDate = $row['Contract_end_date'];
									$reasonForTermination = $row['Reason_for_termination'];
									$contractAmount = $row['Contract_amount'];
									//Company: " . $row['Company'] . " </br>
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
//echo "$SIN $company $employeeType";
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
					"</table>" +
					"</br><input type='submit' name='saveButton' value='Save'></input>" +
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
					"</table>" +
					"</br><input type='submit' name='saveButton' value='Save'></input>" +
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
					"</table>" +
					"</br><input type='submit' name='saveButton' value='Save'></input>" +
					"</form>";
				} 
				else if(document.getElementById('employeeTypeDropdown').value == 'cEmployee')
				{
					document.getElementById('employeeFeilds').innerHTML = "<h3>Personal Information</h3>" +
					"<table>" +
						"<tr>" +
							"<th align='left'>Last Name:</th>" +
							"<td><input type='text' value='<?php echo $lastName; ?>' name='lastName'></td>" +
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
							"<td><input type='text' name='company' value=\"<?php echo $company; ?>\"></td>" +
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
					"</table>" +
					"</br><input type='submit' name='saveButton' value='Save'></input>" +
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
				if(isset($_POST['employeeTypeDropdown']))
				{
					if($_POST['employeeTypeDropdown'] == 'ftEmployee')
					{					
						$insertQuery = "INSERT INTO Person (p_firstname, p_lastname, si_number, date_of_birth)";
						$insertQuery .= " VALUES ('".  $firstName . "', '".  $lastName ."', ".  $SIN .", '".  $dateOfBirth ."');";

						$insertQuery .= "INSERT INTO Employee (emp_id, person_id)";
						$insertQuery .= " VALUES (LAST_INSERT_ID(), LAST_INSERT_ID());";

						$insertQuery .= "INSERT INTO fulltime_employee";
						echo $dateOfBirth."</br>";
						echo $dateOfHire."</br>";
						echo $dateOfTermination;
						$insertQuery .= " VALUES (LAST_INSERT_ID(), (SELECT companyID FROM Company WHERE companyName = \"". $company ."\"), '". $dateOfHire ."', '". $dateOfTermination ."', '". $reasonForTermination ."', ". $salary .", (SELECT status_id FROM Employee_Status WHERE status_type = 'Active'));";
						//$insertQuery .= " VALUES (2, 1, '2000-01-01', null, null, 555, 1);";
						$queryResult = $link->multi_query($insertQuery);
						
						if(!$queryResult)
						{
							echo "Could Not Add Employee";
						}
					}
					
					if($_POST['employeeTypeDropdown'] == 'ptEmployee')
					{
						$insertQuery = "INSERT INTO Person (p_firstname, p_lastname, si_number, date_of_birth)";
						$insertQuery .= "VALUES ('".  $firstName . "', '".  $lastName ."', ".  $SIN .", '".  $dateOfBirth ."');";

						$insertQuery .= "INSERT INTO Employee (emp_id, person_id)";
						$insertQuery .= "VALUES (LAST_INSERT_ID(), LAST_INSERT_ID());";

						$insertQuery .= "INSERT INTO parttime_employee";
						$insertQuery .= "VALUES (LAST_INSERT_ID(), LAST_INSERT_ID(),'". $dateOfHire ."', '". $dateOfTermination ."', ". $reasonForTermination .",". $hourlyRate .", (SELECT status_id FROM Employee_Status WHERE status_type = 'Active'));";
						
						$queryResult = $link->multi_query($insertQuery);
						
						if(!$queryResult)
						{
							echo "Could Not Add Employee";
						}
					}
					
					if($_POST['employeeTypeDropdown'] == 'sEmployee')
					{					
						$insertQuery = "INSERT INTO Person (p_firstname, p_lastname, si_number, date_of_birth)";
						$insertQuery .= "VALUES ('".  $firstName . "', '".  $lastName ."', ".  $SIN .", '".  $dateOfBirth ."');";

						$insertQuery .= "INSERT INTO Employee (emp_id, person_id)";
						$insertQuery .= "VALUES (LAST_INSERT_ID(), LAST_INSERT_ID());";

						$insertQuery .= "INSERT INTO Seasonal_Employee";
						$insertQuery .= "VALUES (LAST_INSERT_ID(), LAST_INSERT_ID(),'". $season ."', '". $year ."', ". $reasonForTermination .",". $piecePay .", (SELECT status_id FROM Employee_Status WHERE status_type = 'Active'));";
						
						$queryResult = $link->multi_query($insertQuery);
						
						if(!$queryResult)
						{
							echo "Could Not Add Employee";
						}
					}
					
					if($_POST['employeeTypeDropdown'] == 'cEmployee')
					{					
						$insertQuery = "INSERT INTO Person (p_firstname, p_lastname, si_number, date_of_birth)";
						$insertQuery .= "VALUES ('".  $firstName . "', '".  $lastName ."', ".  $businessNumber .", '".  $dateOfIncorportation ."');";

						$insertQuery .= "INSERT INTO Employee (emp_id, person_id)";
						$insertQuery .= "VALUES (LAST_INSERT_ID(), LAST_INSERT_ID());";

						$insertQuery .= "INSERT INTO Seasonal_Employee";
						$insertQuery .= "VALUES (LAST_INSERT_ID(), LAST_INSERT_ID(),'". $contractStartDate ."', '". $contractEndDate ."', ". $reasonForTermination .",". $contractAmount .", (SELECT status_id FROM Employee_Status WHERE status_type = 'Active'));";
						
						$queryResult = $link->multi_query($insertQuery);
						
						if(!$queryResult)
						{
							echo "Could Not Add Employee";
						}
					}
				}
			}
			
			$link->close();	
		?>
	</body>
</html>