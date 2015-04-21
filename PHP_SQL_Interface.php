<html>
 <!-- 
Program: PHP_SQL_Interface
File: PHP_SQL_Interface.php
Programmers: Matthew Thiessen & Willi Boldt
First Version: Dec. 11/2014
Description: This page will allow the user to search and sort
			 nothwind database's customer table. They may sort
			 by City, CompanyName, or ContactName. They may also
			 filter the records by CompanyName. The user may add
			 records to the database.
 -->
 <head>
	<title>PHP SQL Interface</title>
	<?php 
		//initialize all data members
		
		$query = "";
		$companyName = "";
		$sortType = "City";
		$connected = "";
		
		$ipAddress = '';
		$userName = '';
		$password = '';
		$databaseName = '';
		
		$link = "";
		$customerID = "";
		$companyName = "";
		$contactName = "";
		$contactTitle = "";
		$address = "";
		$city = "";
		$region = "";
		$postalCode = "";
		$country = "";
		$phone = "";
		$fax = "";
	?>
 </head>
	 <body>
		<?php
			if(!empty($_POST['ipAddress']))
			{
				if(!empty($_POST['ipAddress']))
				{
					$ipAddress = $_POST['ipAddress'];
				}
				
				if(!empty($_POST['userName']))
				{
					$userName = $_POST['userName'];
				}
				
				if(!empty($_POST['password']))
				{
					$password = $_POST['password'];
				}
				
				if(!empty($_POST['databaseName']))
				{
					$databaseName = $_POST['databaseName'];
				}
				
				$link = mysqli_connect($ipAddress, $userName, $password, $databaseName); //connects to the database
				
				if(!$link)
				{
					//if the database connection failed, send error message and quit
					 echo "<br>Could not connect";
				}
			}
			
			if (!$link)
			{				  
				  echo "<form method='post'>
						<h3>Login Menu</h3><hr>
						<table border='0'>       
							<tr>
								<th align='right'>IP Address:</th>
								<td><input type='text' name='ipAddress'></td>
							</tr>
							<tr>
								<th align='right'>User Name:</th>
								<td><input type='text' name='userName'></td>
							</tr>
							<tr>
								<th align='right'>Password:</th>
								<td><input type='text' name='password'></td>
							</tr>
							<tr>
								<th align='right'>Database Name:</th>
								<td><input type='text' name='databaseName'></td>
							</tr>
						</table>
						
						<input type='submit' value='Login'><br><hr>
					</form>";
			}
			else
			{
				//if the user adds a customer, check the fields
				//if a field is not bland add it to the record
				if(!empty($_POST['addCustomer']))
				{
					if(!empty($_POST['customerID']))
					{
						$customerID = $_POST['customerID'];
					}
					
					if(!empty($_POST['companyName']))
					{
						$companyName = $_POST['companyName'];
					}
					if(!empty($_POST['contactName']))
					{
						$contactName = $_POST['contactName'];
					}
					
					if(!empty($_POST['contactTitle']))
					{
						$contactTitle = $_POST['contactTitle'];
					}
					
					if(!empty($_POST['address']))
					{
						$address = $_POST['address'];
					}
					
					if(!empty($_POST['city']))
					{
						$city = $_POST['city'];
					}
					
					if(!empty($_POST['region']))
					{
						$region = $_POST['region'];
					}
					
					if(!empty($_POST['postalCode']))
					{
						$postalCode = $_POST['postalCode'];
					}
					
					if(!empty($_POST['country']))
					{
						$country = $_POST['country'];
					}
					
					if(!empty($_POST['phone']))
					{
						$phone = $_POST['phone'];
					}
					
					if(!empty($_POST['fax']))
					{
						$fax = $_POST['fax'];
					}
					
					$insert = "INSERT INTO customers VALUES('$customerID', '$companyName', '$contactName', '$contactTitle', '$address', '$city', '$region', '$postalCode', '$country', '$phone', '$fax')";
					
					//try to insert record into the database.
					//if it fails, send an error message to the user
					//if the record is added successfully, empty the 
					//user's input
					if(mysqli_query($link, $insert))
					{
						echo "New record created successfully<br>";
						$customerID = "";
						$companyName = "";
						$contactName = "";
						$contactTitle = "";
						$address = "";
						$city = "";
						$region = "";
						$postalCode = "";
						$country = "";
						$phone = "";
						$fax = "";
					}
					else
					{
						echo "Error: $insert <br>" .$link->error;
					}
				}
				
				//if the user is not adding a record, display the search menu
				if(empty($_POST['createButton']) && empty($_POST['addCustomer']))
				{	
					//gets the sort method if there is one
					if(!empty($_POST['sortType']))
					{
						$sortType = $_POST['sortType'];
					}
					
					//gets the filter method if there is one
					if(!empty($_POST['companyName']))
					{
						$companyName = $_POST['companyName'];
					}
					
					echo "<form method='post'>
							<h2>Customer Data Table</h2><br><hr>
							<select name = 'sortType'><!--dropdown box which specifies the sorting method-->
								<option value='City'>City</option>
								<option value='CompanyName'>Company Name</option>
								<option value='ContactName'>Contact Name</option>
							</select> Sort By</br></br>
							<input type='text' name='companyName' value='$companyName'><!--text box specifies record filter--> 
							Filter By Company Name(Optional)</br></br>
							
							<input type='hidden' value='$ipAddress' name='ipAddress'>
							<input type='hidden' value='$userName' name='userName'>
							<input type='hidden' value='$password' name='password'>
							<input type='hidden' value='$databaseName' name='databaseName'>
							<input type='submit' value='Create' name='createButton'>
							<input type='submit' value='Search'><br><hr>
						</form>";
					
					
					//sots by the City column
					if($sortType == "City")
					{
						$query = "SELECT * FROM Customers ORDER BY City";
						
						if($companyName != "")
						{
							$query = "SELECT * FROM Customers WHERE CompanyName LIKE '$companyName%' ORDER BY City";
						}
						
						if($result = $link->query($query))
						{
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
							
							while($row = $result->fetch_assoc())
							{
								echo "<tr>";
								echo "<td>" . $row["CustomerID"] . "</td>";
								echo "<td>" . $row["CompanyName"] . "</td>";
								echo "<td>" . $row["ContactName"] . "</td>";
								echo "<td>" . $row["ContactTitle"] . "</td>";
								echo "<td>" . $row["Address"] . "</td>";
								echo "<td>" . $row["City"] . "</td>";
								echo "<td>" . $row["Region"] . "</td>";
								echo "<td>" . $row["PostalCode"] . "</td>";
								echo "<td>" . $row["Country"] . "</td>";
								echo "<td>" . $row["Phone"] . "</td>";
								echo "<td>" . $row["Fax"] . "</td>";
								echo "</tr>";
							}
							
							echo "</table>";
							
							$result->free();
						}
					}
					//sorts by the CompanyName column
					else if($sortType == "CompanyName")
					{
						$query = "SELECT * FROM Customers ORDER BY CompanyName";
						
						if($companyName != "")
						{
							$query = "SELECT * FROM Customers WHERE CompanyName LIKE '$companyName%' ORDER BY CompanyName";
						}
						
						if($result = $link->query($query))
						{
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
							
							while($row = $result->fetch_assoc())
							{
								echo "<tr>";
								echo "<td>" . $row["CustomerID"] . "</td>";
								echo "<td>" . $row["CompanyName"] . "</td>";
								echo "<td>" . $row["ContactName"] . "</td>";
								echo "<td>" . $row["ContactTitle"] . "</td>";
								echo "<td>" . $row["Address"] . "</td>";
								echo "<td>" . $row["City"] . "</td>";
								echo "<td>" . $row["Region"] . "</td>";
								echo "<td>" . $row["PostalCode"] . "</td>";
								echo "<td>" . $row["Country"] . "</td>";
								echo "<td>" . $row["Phone"] . "</td>";
								echo "<td>" . $row["Fax"] . "</td>";
								echo "</tr>";
							}
							
							$result->free();
						}
					}
					//sorts by the ContactName column
					else if($sortType == "ContactName")
					{
						$query = "SELECT * FROM Customers ORDER BY ContactName";
						
						if($companyName != "")
						{
							$query = "SELECT * FROM Customers WHERE CompanyName LIKE '$companyName%' ORDER BY ContactName";
						}
						
						if($result = $link->query($query))
						{
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
							
							while($row = $result->fetch_assoc())
							{
								echo "<tr>";
								echo "<td>" . $row["CustomerID"] . "</td>";
								echo "<td>" . $row["CompanyName"] . "</td>";
								echo "<td>" . $row["ContactName"] . "</td>";
								echo "<td>" . $row["ContactTitle"] . "</td>";
								echo "<td>" . $row["Address"] . "</td>";
								echo "<td>" . $row["City"] . "</td>";
								echo "<td>" . $row["Region"] . "</td>";
								echo "<td>" . $row["PostalCode"] . "</td>";
								echo "<td>" . $row["Country"] . "</td>";
								echo "<td>" . $row["Phone"] . "</td>";
								echo "<td>" . $row["Fax"] . "</td>";
								echo "</tr>";
							}
							
							$result->free();
						}
					}
				}
				else
				{
					//if the user is adding a record,
					//display the add record menu
					echo "<form method='post'>
							<h2>Enter The Customer Data</h2>
							<table border='0'>       
								<tr>
									<th align='right'>CustomerID:</th>
									<td><input name='customerID' type='text' value='$customerID'/></td>
								</tr>
								<tr>
									<th align='right'>Comapany Name:</th>
									<td><input name='companyName' type='text' value='$companyName'/></td>
								</tr>
								<tr>
									<th align='right'>Contact Name:</th>
									<td><input name='contactName' type='text' value='$contactName'/></td>
								</tr>
								<tr>
									<th align='right'>Contact Title:</th>
									<td><input name='contactTitle' type='text' value='$contactTitle'/></td>
								</tr>
								<tr>
									<th align='right'>Address:</th>
									<td><input name='address' type='text' value='$address'/></td>
								</tr>
								<tr>
									<th align='right'>City:</th>
									<td><input name='city' type='text' value='$city'/></td>
								</tr>
								<tr>
									<th align='right'>Region:</th>
									<td><input name='region' type='text' value='$region'/></td>
								</tr>
								<tr>
									<th align='right'>Postal Code:</th>
									<td><input name='postalCode' type='text' value='$postalCode'/></td>
								</tr>
								<tr>
									<th align='right'>Country:</th>
									<td><input name='country' type='text' value='$country'/></td>
								</tr>
								<tr>
									<th align='right'>Phone:</th>
									<td><input name='phone' type='text' value='$phone'/></td>
								</tr>
								<tr>
									<th align='right'>Fax:</th>
									<td><input name='fax' type='text' value='$fax'/></td>
								</tr>
							</table>
							
							
							<input type='hidden' value='$ipAddress' name='ipAddress'>
							<input type='hidden' value='$userName' name='userName'>
							<input type='hidden' value='$password' name='password'>
							<input type='hidden' value='$databaseName' name='databaseName'>
							<input type='submit' value='Back'>
							<input type='submit' value='Add' name='addCustomer'>
						</form>";
				}
			$link->close();
			}
		?>
	 </body>
</html> 