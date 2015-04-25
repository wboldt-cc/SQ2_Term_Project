<!---
File: "EMS_SystemAdmin.php"
Project: EMS-PPS Term Project
Programmers: 
First Version: April 21, 2015
Description: This page will allow Administrative users to:
                -Add new companies to the database
				-Add new users to the system
				-View the Audit Table (not implemented)
				
			General Users do not have access to any of these capabilities.
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
			
			$userIdToAdd = "";
			$userPasswordToAdd = "";
			$userFirstNameToAdd = "";
			$userLastNameToAdd = "";
			$securityLevel = "";
			$returnedString = "";
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
			<a href= "EMS_EmployeeReports.php">Employee Reports</a><br></br>
			System Administration

		</div>

		<div class="margin">
		</div>

		<div class="content"> </br>
			<h2>System Administration</h2>
			
			
			<form method='post'>
			
			<?php
			if($userType == 'administrator')
			{
				
				/* Display Add company UI */				
				echo "<hr><h3>Add Company</h3>";
				echo "Enter the name of the Company to add: ";
				echo "<input type='text' name='companyToAdd'> ";
				echo "&nbsp&nbsp<input type='submit' name='addCompanyBtn' value='Add'><br>";
				
				if(isset($_POST['addCompanyBtn']))// check if the user pressed the "Add Company" button
				{				
					if(!empty($_POST['companyToAdd']))// make sure the name of the company to add is not empty
					{
						$companyToAdd = $_POST['companyToAdd'];
						
						if($companyToAdd != "")
						{
							if(strlen($companyToAdd) >= 50)// make sure the company name isn't too long
							{
								echo "The company name you entered was too long. We accept up to 50 characters. Please enter a shorter company name.";
							}
							else// company length is proper length
							{
							
								$link = mysqli_connect($serverName, $userName, $password, $databaseName);// connect to the database
											
								if(!$link)
								{
									//if the database connection failed send error message
									 echo "<br>Error: Could not connect to the database.";
								}
								else// we have a connection
								{							
									$returnedString = addCompany($companyToAdd, $link);
								
									echo "<br>$returnedString";
									
								}
							}
							
						}// end 'if' statement
						
					}
					else// user tried to add a company without a company name
					{
						echo "You must enter a company name to add a company.";
					}
					
				}// end 'if' statement	
				
				/* end Display Add company UI */
				
				/* save all of the data entered in the "add user" section into variables */
				if(isset($_POST['userIdToAdd']))
				{
					$userIdToAdd = $_POST['userIdToAdd'];
				}
				if(isset($_POST['userPasswordToAdd']))
				{
					$userPasswordToAdd = $_POST['userPasswordToAdd'];
				}
				if(isset($_POST['userFirstNameToAdd']))
				{
					$userFirstNameToAdd = $_POST['userFirstNameToAdd'];
				}
				if(isset($_POST['userLastNameToAdd']))
				{
					$userLastNameToAdd = $_POST['userLastNameToAdd'];
				}
				
				/* Display Add User UI */
				echo "<hr><h3>Add User</h3>";
				echo "Enter the following information for the User to add. User ID and Password are required.";
				echo "<br><br>First Name: ";
				echo "<input type='text' name='userFirstNameToAdd' value=\"$userFirstNameToAdd\"> ";
				echo " &nbsp&nbsp Last Name: ";
				echo "<input type='text' name='userLastNameToAdd' value=\"$userLastNameToAdd\"> ";
				echo "<br><br> &nbsp&nbsp&nbsp&nbsp User ID: ";
				echo "<input type='text' name='userIdToAdd' value=\"$userIdToAdd\"'> ";
				echo " &nbsp&nbsp&nbsp&nbsp Password: ";
				echo "<input type='password' name='userPasswordToAdd' value=\"$userPasswordToAdd\"> ";
				echo "<br><br>Select the security level: <select name='securityLevelDropDown'>
								<option value='2'>General</option>
								<option value='1'>Administrator</option>
							  </select>";
				echo "&nbsp&nbsp<input type='submit' name='addUserBtn' value='Add'><br>";
								
				
				if(isset($_POST['addUserBtn']))// check if the user pressed the "Add User" button
				{				
					if(!empty($_POST['userIdToAdd']) && !empty($_POST['userPasswordToAdd']))
					{
						$userIdToAdd = $_POST['userIdToAdd'];
						$userPasswordToAdd = $_POST['userPasswordToAdd'];
						$userFirstNameToAdd = "";
						$userLastNameToAdd = "";
						$securityLevel = $_POST['securityLevelDropDown'];
						
						if(!empty($_POST['userFirstNameToAdd']))// check if the first name of the user was entered
						{
							$userFirstNameToAdd = $_POST['userFirstNameToAdd'];
						}
						
						if(!empty($_POST['userLastNameToAdd']))// check if the last name of the user was entered
						{
							$userLastNameToAdd = $_POST['userLastNameToAdd'];
						}
						
						$link = mysqli_connect($serverName, $userName, $password, $databaseName);// connect to the database
									
						if(!$link)
						{
							//if the database connection failed send error message
							 echo "<br>Error: Could not connect to the database.";
						}
						else// we have a connection
						{						
							$returnedString = addUser($userIdToAdd, $userPasswordToAdd, $userFirstNameToAdd, $userLastNameToAdd, $securityLevel, $link, $databaseName);
						
							echo "<br>$returnedString";
							
						}						
												
					}
					else// user tried to add a user without an ID or password
					{
						echo "You must enter a User ID and a Password to add a user.";
					}
					
				}// end 'if' statement	
				
				/* end Display Add User UI */
				
				/* Display Audit Table UI */
				echo "<hr><h3>Audit Table</h3>";
				echo "Unfortunately this functionality is not available. Sorry for the inconvenience.";
				
				/* end Display Audit Table UI */
				
			}
			else// user is not an administrator
			{
				echo "You do not have access to this page.";
			}
			
			
			?>
			
			
			</form>
		</div>

		<div class="margin">
		</div>

		<div class="footer">
			Copyright &copy MATTHEWSOFT
		</div>
		
		<?php
		// this is where all of the functions for the page are declared
			
			/*
			 * Function: addCompany()
			 * Description: This function adds a company to the database
			 * Parameters: $companyToAdd - the name of the company to add
						   $link - a connection to the database
			 * Return: Whether or not the company was successfully added
			 */
			function addCompany($companyToAdd, $link)
			{
				$returnString = "";				
				$queryString = "INSERT INTO Company (companyName) VALUES(\"$companyToAdd\");";							
				
				if($result = $link->query($queryString))// make sure query was successful
				{				
					$returnString .= "The company: '$companyToAdd' was successfully added to the database.";
				}
				else// query failed
				{
					$returnString = "There was an error attempting to enter the company into the database.";
				}	
				
				return $returnString;
			}
					
			/*
			 * Function: addUser()
			 * Description: This function adds a user to the database
			 * Parameters: $userIdToAdd - the id of the user to add
			               $userPasswordToAdd - the password of the user to add
			               $userFirstNameToAdd - the first name of the user to add
			               $userLastNameToAdd - the last name of the user to add
			               $securityLevel - the security level of the user to add
						   $link - a connection to the database
						   $databaseName - the name of the database						   
			 * Return: Whether or not adding the user to the database was successful
			 */
			function addUser($userIdToAdd, $userPasswordToAdd, $userFirstNameToAdd, $userLastNameToAdd, $securityLevel, $link, $databaseName)
			{
				$returnString = "";				
				$queryString = "INSERT INTO Users ";
				
				if(($userFirstNameToAdd == "") || ($userLastNameToAdd == ""))
				{
					if($userFirstNameToAdd == "")
					{
						if($userLastNameToAdd == "")// if true both are blank
						{
							$queryString .= "(userID, userPassword, securityLevel) VALUES(\"$userIdToAdd\", \"$userPasswordToAdd\", $securityLevel);";
						}
						else// only first name is blank
						{
							$queryString .= "(userID, userPassword, u_lastName, securityLevel) VALUES(\"$userIdToAdd\", \"$userPasswordToAdd\", \"$userLastNameToAdd\", $securityLevel);";
						}
					
					}
					else// only last name is blank
					{
						$queryString .= "(userID, userPassword, u_firstName, securityLevel) VALUES(\"$userIdToAdd\", \"$userPasswordToAdd\", \"$userFirstNameToAdd\", $securityLevel);";
					}
					
				}
				else// all fields have values
				{
					$queryString .=	"VALUES(\"$userIdToAdd\", \"$userPasswordToAdd\", \"$userFirstNameToAdd\", \"$userLastNameToAdd\", $securityLevel);";							

				}
				
				$queryString .=	"CREATE USER \"$userIdToAdd\"@'localhost' IDENTIFIED BY \"$userPasswordToAdd\";";
				
				$queryString .=	"GRANT ALL ON $databaseName.* TO \"$userIdToAdd\"@'localhost';FLUSH PRIVILEGES;";
				
				
				if($result = $link->query($queryString))// make sure query was successful
				{		
					$returnString .= "The user: '$userIdToAdd' was successfully added to the database.";

					
				}
				else// query failed
				{
					$returnString = "There was an error attempting to enter the user into the database.";
				}	
				
				echo "$queryString";
			
				return $returnString;
			}		
			
		?>

	</body>


</html>