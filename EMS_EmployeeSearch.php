<!---
Filename: "EMS_EmployeeSearch.php"
Programmers: William Boldt, , and 
Date: April 17, 2015
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
			
			$lastNameToSearchFor = "";
			$firstNameToSearchFor = "";
			$SINtoSearchFor = "";
			$employeeType = "";// the type of employee to search for (ex Full Time)
			
			$queryString = "";
			
			$serverName = $_SESSION['serverName'];
			$userName = $_SESSION['userName'];
			$password = $_SESSION['password'];
			$databaseName = $_SESSION['databaseName'];	
			$userType = $_SESSION['userType'];
			
			$link = "";
			
			
		?>
		
	</head>


	<body>

		<div class="header">
			<br/>
			<h1>EMS-PPS</h1>
			<br/>
		</div>

		
		<!-- Display navigation menu -->
		<div class="menu">
			</br> <b>Operation Modes:</b> </br></br>
			<a href= "EMS_HomePage.php" >Home</a><br></br>
			Search For Employees</br></br>
			<a href= "EMS_EmployeeMaintenance.php">Manage Employees</a><br></br>
			<a href= "EMS_EmployeeReports.php">Employee Reports</a><br></br>
			<!-- if user is an administrator -->
			<a href= "EMS_SystemAdmin.php">System Administration</a><br></br>

		</div>

		<div class="margin">
		</div>

		<div class="content"> </br>
			
			<h2>Search Employees</h2><hr>
			
			<form method='post'>
			<?php
			/* the following variables are used to have the employee type the user selected stay selected when they submit the form */
			$ftEmployeeSelected = "";
			$ptEmployeeSelected = "";
			$sEmployeeSelected = "";
			$cEmployeeSelected = "";
			
			if ($_SERVER['REQUEST_METHOD'] == 'POST')
			{
				if (!isset($_POST['searchBtn']) && !isset($_POST['displayBtn']))// check if the user wants to edit the current employee being displayed
				{
					//$tempOne = $_POST['hiddenCompany'];
					//$tempTwo = $_POST['hiddenSIN'];
					//echo "reached code<br> $tempOne $tempTwo";
					
					$_SESSION['SINfromSearch'] = $_POST['hiddenSIN'];
					$_SESSION['CompanyFromSearch'] = $_POST['hiddenCompany'];
					$_SESSION['EmployeeTypeFromSearch'] = $_POST['hiddenEmployeeType'];
					
					header('Location: ./EMS_EmployeeMaintenance.php');
					// go to modify employee page 
				}
			}
			
			if(!empty($_POST['firstName']))
			{
				$firstNameToSearchFor = $_POST['firstName'];
			}
			else
			{
				$firstNameToSearchFor = "";
			}
			
			if(!empty($_POST['lastName']))
			{
				$lastNameToSearchFor = $_POST['lastName'];
			}
			else
			{
				$lastNameToSearchFor = "";
			}
			
			if(!empty($_POST['SIN']))
			{
				$SINtoSearchFor = $_POST['SIN'];
			}
			else
			{
				$SINtoSearchFor = "";
			}
			
			if(!empty($_POST['employeeTypeDropDown']))
			{
				$employeeType = $_POST['employeeTypeDropDown'];
				
				/* this switch finds which of the employee types the user selected */
				switch($employeeType)
				{
				case "ftEmployee":
					$ftEmployeeSelected = "selected";
					break;
				case "ptEmployee":
					$ptEmployeeSelected = "selected";
					break;
				case "sEmployee":
					$sEmployeeSelected = "selected";
					break;
				case "cEmployee":
					$cEmployeeSelected = "selected";
					break;
				}
				
			}
			
			
			echo "First Name: 
					<input type='text' name='firstName' value='$firstNameToSearchFor'>
					&nbsp &nbsp Last Name: 
					<input type='text' name='lastName' value='$lastNameToSearchFor'></br></br>
					Social Insurance Number: 
					<input type='text' name='SIN' value='$SINtoSearchFor'>
					&nbsp&nbspEmployee Type: 
					<select name='employeeTypeDropDown'>
						<option value='ftEmployee' $ftEmployeeSelected>Full Time</option>
						<option value='ptEmployee' $ptEmployeeSelected>Part Time</option>
						<option value='sEmployee' $sEmployeeSelected>Seasonal</option>";
						
			if($userType == 'administrator')// find out if we need to also allow contract employees
			{
				echo "<option value='cEmployee' $cEmployeeSelected>Contract</option>";
			}
										
			echo "</select></br></br>
																			
					<input type='submit' name='searchBtn' value='Search'><br><hr>";
			
			if(($lastNameToSearchFor != "") || ($firstNameToSearchFor != "") || ($SINtoSearchFor != ""))// make sure at least one of the search criteria pieces is not blank
			{
				$link = mysqli_connect($serverName, $userName, $password, $databaseName);// connect to the database
				
				if(!$link)//if the database connection failed display an error message
				{
					 echo "<br>Error: Could not connect to the database.";
				}
				else// we have a connection
				{
					$dropdownMenu = constructDropdownMenu($lastNameToSearchFor, $firstNameToSearchFor, $SINtoSearchFor, $link, $employeeType);
					
					echo "$dropdownMenu";
							
					if(!empty($_POST['employeeToDisplayDropDown']))// check if the user has selected an employee from the drop down menu
					{
						//$SINofEmployee = $_POST['employeeToDisplayDropDown'];
						
						parse_str($_POST['employeeToDisplayDropDown']);// extract $SINofEmployee and $Company from the value of the drop down
						
						$employeeInfo = changeDisplayedEmployee($SINofEmployee, $Company, $link, $employeeType);
						
						echo $employeeInfo . "<br><hr><br><input type='submit' name='editBtn' value='Edit'>";
						echo "<input type='hidden' name='hiddenSIN' value=\"$SINofEmployee\">
						      <input type='hidden' name='hiddenCompany' value=\"$Company\">
						      <input type='hidden' name='hiddenEmployeeType' value=\"$employeeType\">";
						
					}														
					
					$link->close();
								
				}
			}
												
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
			 * Function: 
			 * Description: 
			 * Parameters: 
			 * Return: 
			 */
			function constructDropdownMenu($lastNameToSearchFor, $firstNameToSearchFor, $SINtoSearchFor, $link, $employeeType)
			{
				$returnString = "";
				$queryString = "";
							
				$userType = $_SESSION['userType'];
																																
				//select lastName, firstName, SIN from employees where employeeType != contract & employee is active
//$queryString = "SELECT p_lastName, p_firstName, si_number FROM Person ";

				if($employeeType != "cEmployee")
				{
					$queryString = "SELECT Last_Name, First_Name, SIN, Company FROM ";
					
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
					
					if($lastNameToSearchFor != "")
					{
						$queryString .= "WHERE Last_Name LIKE \"%$lastNameToSearchFor%\" ";
					}
					
					if($firstNameToSearchFor != "")
					{
						if($lastNameToSearchFor != "")// check if the last name was not blank
						{
							$queryString .= "AND First_Name LIKE \"%$firstNameToSearchFor%\" ";
						}
						else// last name was blank
						{
							$queryString .= "WHERE First_Name LIKE \"%$firstNameToSearchFor%\" ";
						}
					}
					
					if($SINtoSearchFor != "")
					{
						if(($lastNameToSearchFor != "") || ($firstNameToSearchFor != ""))// check if either of the names were not blank
						{
							$queryString .= "AND SIN LIKE '%$SINtoSearchFor%' ";
						}
						else// both names were blank
						{
							$queryString .= "WHERE SIN LIKE '%$SINtoSearchFor%' ";
						}
					}
					
					if($userType == "general")// general users only have access to active employees
					{
						$queryString .= "AND Status='Active' ";
					}
					
					$queryString .= "ORDER BY \"Last Name\";";
					
					//display lastname firstname and sin of employees found in list form.
					//User will be able to click on them and display that employees info
					$returnString = "<select name='employeeToDisplayDropDown'>
											<option value=''></option>";
											
					if($result = $link->query($queryString))// make sure query was successful
					{
						while($row = $result->fetch_assoc())
						{
							$returnString .= "<option value=\"SINofEmployee=" . $row["SIN"] . "&Company=" . $row["Company"] . "\">"
											 . $row["Last_Name"] . ", "
											 . $row["First_Name"] . ", "
											 . $row["SIN"] . "</option>";
						}
						
						$returnString .= "</select><input type='submit' name='displayBtn' value='Display'><br>";
						
						$result->free();
					}
					else// query failed
					{
						$returnString = "There was an error while running the SQL script";
	//$returnString .= $queryString;
					}	
				}
				else// CT employee
				{					
					$queryString = "SELECT Contract_company_name, Business_Number FROM CT_Display ";
					
					if($lastNameToSearchFor != "" && $SINtoSearchFor != "")
					{
						$queryString .= "WHERE Contract_company_name LIKE \"%$lastNameToSearchFor%\" AND SIN LIKE '%$SINtoSearchFor%' ";
					}
					else if($lastNameToSearchFor != "")
					{
						$queryString .= "WHERE Contract_company_name LIKE \"%$lastNameToSearchFor%\" ";
					}
					else
					{
						$queryString .= "WHERE Business_Number LIKE '%$SINtoSearchFor%' ";
					}
					
					$queryString .= "ORDER BY \"Last Name\";";
					
					$returnString = "<select name='employeeToDisplayDropDown'>
											<option value=''></option>";
											
					if($result = $link->query($queryString))// make sure query was successful
					{
						while($row = $result->fetch_assoc())
						{
							$returnString .= "<option value=\"SINofEmployee=" . $row["Business_Number"] . "&Company=" . $row["Contract_company_name"] . "\">"
											 . $row["Contract_company_name"] . ", "
											 . $row["Business_Number"] . "</option>";
						}
						
						$returnString .= "</select><input type='submit' name='displayBtn' value='Display'><br>";
						
						$result->free();
					}
					else// query failed
					{
						$returnString = "There was an error while running the SQL script";
	//$returnString .= $queryString;
	echo "$queryString";
					}	
					
				}
									
				return $returnString;
			}
			
			/*
			 * Function: 
			 * Description: 
			 * Parameters: 
			 * Return: 
			 */
			function changeDisplayedEmployee($SINofEmployee, $Company, $link, $employeeType)
			{
				$returnString = "";
				$fisrtName = "";
				$lastName = "";
				$dateOfBirth = "";
				$employedWithCompany = "";
				$dateOfHire = "";
				$dateOfTermination = "";
				$reasonForTermination = "";
				$salary = "";
				$hourlyRate = "";
				$status = "";
				$placeHolder = "";// place holder until we have the data for each field
				
				$userType = $_SESSION['userType'];
											
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

					$queryString .= "WHERE SIN=\"$SINofEmployee\" && Company=\"$Company\";";
				
				
					if($result = $link->query($queryString))
					{
					/*
						echo "<table border='1'>";
						echo "<tr>";
						echo "<th>CustomerID</th>";
						echo "<th>CompanyName</th>";
						echo "<th>ContactName</th>";
						echo "<th>ContactTitle</th>";
						echo "<th>Address</th>";
						echo "<th>City</th>";
						echo "<th>Region</th>";
						echo "<th>PostalCode</th>";
						echo "<th>Country</th>";
						echo "<th>Phone</th>";
						echo "<th>Fax</th>";
						echo "</tr>";
						
						
				$season = "";
				$year = "";
						*/
						
						while($row = $result->fetch_assoc())
						{
							$returnString .= "First Name: " . $row['First_Name'] . "</br>
									  Last Name: " . $row['Last_Name'] . "</br>
									  SIN: " . $row['SIN'] . "</br>
									  Date of Birth: " . $row['Date_of_Birth'] . " </br>
									  Employed with Company: " . $row['Company'] . " </br>";
							switch($employeeType)
							{
							case 'ftEmployee':
							case 'ptEmployee':
								$returnString .= "Date of Hire: " . $row['Date_of_hire'] . " </br>";
								break;
							case 'sEmployee':
								$returnString .= "Season: " . $row['Season'] . " </br>
								                  Year: " . $row['Year'] . " </br>";
								break;								
							}
									  								
							
							if($userType == "administrator")
							{
								$returnString .= "Date of Termination: " . $row['Date_of_termination'] . " </br>
									  Reason For Termination: " . $row['Reason_for_termination'] . " </br>";
									  
								switch($employeeType)
								{
								case 'ftEmployee':
									$returnString .= "Salary: " . $row['Salary'] . " </br>";
									break;
								case 'ptEmployee':
									$returnString .= "Hourly Rate: " . $row['Hourly_rate'] . " </br>";
									break;
								case 'sEmployee':
									$returnString .= "Piece Pay: " . $row['Piece_Pay'] . " </br>";
									break;								
								}
									  
								$returnString .= "Status: " . $row['Status'] . " </br>";
									
							}	
						}
																	
						
						$result->free();
					}
					else// query failed
					{
						$returnString = "Could not display the Employees Information. Sorry for the inconvenience";
						$returnString .= "<hr>$queryString";
					}																
							
				
				}
				else// it's a contract employee
				{
					$queryString .= "CT_Display WHERE Business_Number=\"$SINofEmployee\" && Contract_company_name=\"$Company\";";
					
					if($userType != "administrator")
					{
						$returnString = "<br>You do not have access to Contract Employees.<br>";
					}
					else
					{
								
						if($result = $link->query($queryString))
						{
							
							while($row = $result->fetch_assoc())
							{
								$returnString .= "Company Name: " . $row['Contract_company_name'] . "</br>
										  Business Number: " . $row['Business_Number'] . "</br>
										  Date of Incorporation: " . $row['Date_of_incorportation'] . " </br>
										  Contract Start Date: " . $row['Contract_start_date'] . " </br>
										  Contract End Date: " . $row['Contract_end_date'] . " </br>
										  Reason For Termination: " . $row['Reason_for_termination'] . " </br>
										  Contract Amount: " . $row['Contract_amount'] . " </br>
										  Company: " . $row['Company'] . " </br>
										  Status: " . $row['Status'] . " </br>";
										
									
							}
																		
							
							$result->free();
						}
						else// query failed
						{
							$returnString = "Could not display the Employees Information. Sorry for the inconvenience";
						}
						
					}
				
				}
							
				return $returnString;
			}

		?>				
		
		
		</form>

	</body>


</html>