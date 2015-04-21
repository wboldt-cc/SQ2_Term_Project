<html>
<head>
	<?php
		include 'validate.php';
	?>
</head>
<body>
	<div class="margin">
	</div>

	<div class="content"> </br>
		<h2>Employee Maintenance</h2>
		Employee Type:</br>
		<form method='post'>
			<input type='text' value='1000-01-01' name='testText1'></input>
			<input type='text' value='1000-01-01' name='testText2'></input>
			<input type='text' value='1000-01-01' name='testText3'></input>
			<input type='submit' id='desu' value='Herru'></input>
		</form>
		</br></br>
		<span id='testSpan'>
			<?php
				$errorMessage='';
				echo "Value:" . $_POST['testText1'] . "</br>";
				echo "Value:" . $_POST['testText2'] . "</br>";
				echo "Value:" . $_POST['testText3'] . "</br>";
				if(ValidateDateOfCreation($_POST['testText1'], $_POST['testText2'], $errorMessage) != 0)
				{
					echo "</br>";
					echo "OH NOOO!";
					echo "</br>";
					echo $errorMessage;
				}
				else
				{
					echo "YAY!";
				}
			?>
		</span>

	</div>
	</br>
</body>
</html>