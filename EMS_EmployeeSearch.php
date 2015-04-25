<!---
File: "EMS_EmployeeSearch.php"
Project: EMS-PPS Term Project
Programmers: 
First Version: April 21, 2015
Description: This page allows the user to search for employees based on company
             and employee type and one or more of the following: first name, last
			 name, and SIN.
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
			include 'validate.php';
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
			
			if ($_SERVER['REQUEST_METHOD'] == 'POST')// make sure the user submitted the form
			{
				if (!isset($_POST['searchBtn']) && !isset($_POST['displayBtn']))// check if the user wants to edit the current employee being displayed
				{
					/* save the data into session variables */
					$_SESSION['SINfromSearch'] = $_POST['hiddenSIN'];
					$_SESSION['CompanyFromSearch'] = $_POST['hiddenCompany'];
					$_SESSION['EmployeeTypeFromSearch'] = $_POST['hiddenEmployeeType'];
					
					header('Location: ./EMS_EmployeeMaintenance.php');// go to modify employee page 
				}
			}
			
			if(!empty($_POST['firstName']))// check if the first name field has information
			{
				$firstNameToSearchFor = $_POST['firstName'];
			}
			else
			{
				$firstNameToSearchFor = "";
			}
			
			if(!empty($_POST['lastName']))// check if the last name field has information
			{
				$lastNameToSearchFor = $_POST['lastName'];
			}
			else
			{
				$lastNameToSearchFor = "";
			}
			
			if(!empty($_POST['SIN']))// check if the SIN field has information
			{
				$SINtoSearchFor = $_POST['SIN'];
			}
			else
			{
				$SINtoSearchFor = "";
			}
			
			if(!empty($_POST['employeeTypeDropDown']))// check if the user has choosen an employee type from the drop down
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
						
			if($userType == 'administrator')// find out if we need to also allow the user to search for contract employees
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
			 * Function: constructDropdownMenu()
			 * Description: This method takes in parameters to use to search the database for employees and then
			                constructs a drop down menu of employees found in the format lastname, firstname, SIN.
							The value of each option contains 2 name=value pairs. The first is the SIN, and the 
							second is the company the employee works for
			 * Parameters: $lastNameToSearchFor - the last name of the employee to search for
			               $firstNameToSearchFor - the first name of the employee to search for
			               $SINtoSearchFor - the SIN of the employee to search for
						   $link - a connection to the database
						   $employeeType - the type of employee to search for
			 * Return: The html code that constructs the drop down menu of employees found in the search
			 */
			function constructDropdownMenu($lastNameToSearchFor, $firstNameToSearchFor, $SINtoSearchFor, $link, $employeeType)
			{
				$returnString = "";
				$queryString = "";
							
				$userType = $_SESSION['userType'];

				if($employeeType != "cEmployee")// make sure the user does not want to search for contract employees
				{
					$queryString = "SELECT Last_Name, First_Name, SIN, Company FROM ";
					
					switch($employeeType)// find out the type of employee
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
					}	
				}
				else// it is a CT employee
				{					
					$queryString = "SELECT Contract_company_name, Business_Number FROM CT_Display ";
					
					if($lastNameToSearchFor != "" && $SINtoSearchFor != "")// check if the user wants to search by both name and SIN (business number)
					{
						$queryString .= "WHERE Contract_company_name LIKE \"%$lastNameToSearchFor%\" AND SIN LIKE '%$SINtoSearchFor%' ";
					}
					else if($lastNameToSearchFor != "")// check if user only want to search by name
					{
						$queryString .= "WHERE Contract_company_name LIKE \"%$lastNameToSearchFor%\" ";
					}
					else// user only wants to seach based on SIN (business number)
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
						//echo "$queryString";// debugging statement
					}	
					
				}
									
				return $returnString;
			}
			
			/*
			 * Function: changeDisplayedEmployee()
			 * Description: This method finds the employee that the user wants to display
			                and displays all of the information of that employee
			 * Parameters: $SINofEmployee - the SIN of the employee to display
			               $Company - the company of the employee to display
			               $link - a link to the database 
			               $employeeType - the employee type of the employee to display
			 * Return: The html code that contains all of the information of the employee
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