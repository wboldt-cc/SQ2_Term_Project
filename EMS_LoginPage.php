<!---
File: "EMS_LoginPage.php"
Project: EMS-PPS Term Project
Programmers: Matthew Thiessen, Willi Boldt, Ping Chang Ueng, and Tylor McLaughlin
First Version: April 21, 2015
Description: This page allows the user to log into the system using:
             -the IP address of the database
			 -the userName
			 -the password
			 -the name of the database
			 -the type of user they are (either administrator or general
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
		//initialize all data members		
		$query = "";
		$connected = "";
		
		$serverName = '';
		$userName = '';
		$password = '';
		$databaseName = '';
		$userType = '';
		
		$link = "";
		
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
		</div>

		<div class="margin">
		</div>

		<div class="content"> </br>
		
		<?php
			/* make sure all the fields have been filled in */
			if(!empty($_POST['serverName']))
			{
				$serverName = $_POST['serverName'];
				
				if(!empty($_POST['userName']))
				{
					$userName = $_POST['userName'];
				
				
					if(!empty($_POST['password']))
					{
						$password = $_POST['password'];					
					
						if(!empty($_POST['databaseName']))
						{
							$databaseName = $_POST['databaseName'];
						
							if(!empty($_POST['userType']))
							{
								$userType = $_POST['userType'];
								
								$link = mysqli_connect($serverName, $userName, $password, $databaseName);// try connecting to the database
								
								if(!$link)
								{
									//if the database connection failed, send error message and quit
									 echo "<br>Could not connect";
								}
								
							}
						}
					}
				}
			}
			
			if (!$link)// check if we have a connection
			{				  
				echo "<form method='post'>
						<h2>Login Menu</h2>
						
						<input type='radio' name='userType' value='general'>General User
						<br>
						<input type='radio' name='userType' value='administrator'>Administrative User							
						<br>
						<br>
						<table border='0'>       
							<tr>
								<th align='right'>IP Address:</th>
								<td><input type='text' name='serverName' value='localhost'></td>
							</tr>
							<tr>
								<th align='right'>User Name:</th>
								<td><input type='text' name='userName' value=''></td>
							</tr>
							<tr>
								<th align='right'>Password:</th>
								<td><input type='text' name='password' value=''></td>
							</tr>
							<tr>
								<th align='right'>Database Name:</th>
								<td><input type='text' name='databaseName' value='EMS'></td>
							</tr>
						</table>
						<br>
						<br>
						
						<input type='submit' value='Login'><br>
					</form>";
			}
			else// we have a connection
			{
				/* save all of the data into session variables */
				$_SESSION['serverName'] = $serverName;
				$_SESSION['userName'] = $userName;
				$_SESSION['password'] = $password;
				$_SESSION['databaseName'] = $databaseName;	
				$_SESSION['userType'] = $userType;
				
				$link->close();
				
				header('Location: EMS_HomePage.php');// navigate to the next page
			}
		?>
		
		</div>
		
		<div class="margin">
		</div>

		<div class="footer">
			Copyright &copy MATTHEWSOFT
		</div>
		
		
	 </body>
	 
</html> 
