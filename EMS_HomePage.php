<!---
File: "EMS_HomePage.php"
Project: EMS-PPS Term Project
Programmers: Matthew Thiessen, Willi Boldt, Ping Chang Ueng, and Tylor McLaughlin
First Version: April 21, 2015
Description: This page simply greats the user and allows them to
             navigate to the other pages
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
			Home<br></br>
			<a href= "EMS_EmployeeSearch.php" >Search For Employees</a><br></br>
			<a href= "EMS_EmployeeMaintenance.php">Manage Employees</a><br></br>
			<a href= "EMS_EmployeeReports.php">Employee Reports</a><br></br>
			<!-- if user is an administrator -->
			<a href= "EMS_SystemAdmin.php">System Administration</a><br></br>

		</div>

		<div class="margin">
		</div>

		<div class="content"> </br>
			<h2>Welcome <?php echo $_SESSION['userName']; ?>.</h2>
			Please select an option on the left.
			
			You are a <?php echo $_SESSION['userType']; ?> user.

		</div>

		<div class="margin">
		</div>

		<div class="footer">
			Copyright &copy MATTHEWSOFT
		</div>


	</body>


</html>