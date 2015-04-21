<html>
 <!-- 
Program: EMS-PSS
File: EMS_LoginPage.php
Programmers: Matthew Thiessen & Willi Boldt
First Version: April 11, 2015
Description: This page will allow the user to 
 -->
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
								
								$link = mysqli_connect($serverName, $userName, $password, $databaseName); //connects to the database
								
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
								<td><input type='text' name='userName' value='root'></td>
							</tr>
							<tr>
								<th align='right'>Password:</th>
								<td><input type='text' name='password' value='Conestoga1'></td>
							</tr>
							<tr>
								<th align='right'>Database Name:</th>
								<td><input type='text' name='databaseName' value='newDbase'></td>
							</tr>
						</table>
						<br>
						<br>
						
						<input type='submit' value='Login'><br>
					</form>";
			}
			else// we have a connection
			{
				
				$_SESSION['serverName'] = $serverName;
				$_SESSION['userName'] = $userName;
				$_SESSION['password'] = $password;
				$_SESSION['databaseName'] = $databaseName;	
				$_SESSION['userType'] = $userType;
				
				$link->close();
				
				header('Location: EMS_HomePage.php');
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
