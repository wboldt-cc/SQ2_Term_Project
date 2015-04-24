<?php
/// File: Validation.php
/// Project: EMS-PSS Term Project
/// Programmers: Matthew Thiessen, Willi Boldt, Ping Chang Ueng, and Tylor McLaughlin
/// First Version: April.21/2015
/// Description: Contains all of the validation functions

	date_default_timezone_set("EST");
	/**
	* \brief Validates the first/lastName attribute within the Employee class
	*
	* \details <b>Details</b>
	*
	* This method will take in a string of user input representing the desired
	* employee first/last name and check whether or not it is a
	* valid name. Returns a true or false depending on whether or not the 
	* attribute is valid.
	* 
	* \param firstName - The employee's first/last name 
	* given by the user.
	* 
	* \param errorMessage - The error message container 
	* which is passed as a reference from the calling method
	* 
	* \return - Returns 0 if the attribute is valid.
	* Returns 1 if the attribute is not valid.
	*/
	function ValidateName($name, &$errorMessage)
	{
		$validateStatus = 0;
		$errorMessage = "Invalid Characters Found:</br>";

		for ($i = 0; $i < strlen($name); $i++)
		{
			if (ctype_alpha($name[$i]) == false)
			{
				if ($name[$i] != '\'' && $name[$i] != '-')
				{
					$errorMessage .= $name[$i] . " ";
					$validateStatus = 1;
				}
			}
		}

		if ($validateStatus == 1)
		{
			$errorMessage .= "</br></br>Please Be Sure To Only Enter:</br>A-Z</br>a-z</br></br>";
		}
		else
		{
			$errorMessage = "";
		}
		
		return $validateStatus;
	}
	
	/**
	* \brief Validates the socialInsuranceNumber attribute within the Employee class
	*
	* \details <b>Details</b>
	*
	* This method will take in a string of user input representing the desired
	* employee social insurance number and check whether or not it is a
	* valid social insurance number. Returns a true or false depending on whether or not the 
	* attribute is valid.
	* 
	* \param socialInsuranceNumber - The employee's social insurance
	* number given by the user.
	* 
	* \param errorMessage - The error message container 
	* which is passed as a reference from the calling method
	* 
	* \return Returns 0 if the attribute is valid.
	* Returns 1 if the attribute is not valid.
	*/
	function ValidateSocialInsuranceNumber($socialInsuranceNumber, &$errorMessage)
	{
		$validateStatus = 0;
		$sinValNumTwoStr = "";
		$checkSum = 0;
		$sinNumLength = 9;
		$sinNumInt = array();
		$sinValNumOne = 0;
		$sinValNumTwo = 0;
		$roundedUpInt = 0;
		$errorMessage = "Invalid Characters Found:</br>";

		for ($i = 0; $i < strlen($socialInsuranceNumber); $i++)
		{
			if($socialInsuranceNumber[$i] == ' ')
			{
				$socialInsuranceNumber = str_replace(' ', '', $socialInsuranceNumber);
			}
		}

		if (strlen($socialInsuranceNumber) == $sinNumLength)
		{
			for ($i = 0; $i < strlen($socialInsuranceNumber); $i++)
			{
				if (ctype_digit ($socialInsuranceNumber[$i]) == false)
				{
					$errorMessage .= $socialInsuranceNumber[$i] . " ";
					$validateStatus = 1;
				}
				else
				{
					$sinNumInt[$i] = $socialInsuranceNumber[$i];
				}
			}

			if ($validateStatus == 0)
			{
				//add up all the numbers in the odd placeholders
				for ($i = 0; $i <= 6; $i++)
				{
					$sinValNumOne .= intval($sinNumInt[$i]);

					$i++;
				}

				//add up all the numbers in the even placeholders
				for ($i = 1; $i <= 7; $i++)
				{
					$sinValNumTwoStr .= (string)(intval($sinNumInt[$i]) * 2);
					$i++;
				}

				for ($i = 0; $i < strlen($sinValNumTwoStr); $i++)
				{
					$sinValNumTwo .= $sinValNumTwoStr[$i];
				}

				$sinValNumOne .= $sinValNumTwo;
				$roundedUpInt = $sinValNumOne;

				while (($roundedUpInt % 10) != 0)
				{
					$roundedUpInt++;
				}

				$checkSum = intval($socialInsuranceNumber[8]);
				if ($checkSum != ($roundedUpInt - $sinValNumOne))
				{
					$validateStatus = 1;
					$errorMessage = "Invalid Checksum. Please Be Sure To Enter A Valid SIN.</br>";
				}
			}
			else
			{
				$errorMessage .= "</br></br>Please Be Sure To Only Enter:</br>0-9</br>";
			}
		}
		else
		{
			$errorMessage = "Please Be Sure You Social Insurance Number</br>Is 9 Digits In Length</br>";
			$validateStatus = 1;
		}

		if ($validateStatus == 0)
		{
			$errorMessage = "";
		}

		return $validateStatus;
	}
	
	/**
	* \brief Sets the dateOfBirth attribute within the Employee class
	*
	* \details <b>Details</b>
	*
	* This method will take in a dateOfBirth variable representing the 
	* employee's date of birth and check whether or not it is a
	* valid entry. Returns a true or false depending on whether or not the 
	* attribute is valid.
	* 
	* \param dateOfBirth - The employee's date of birth 
	* given by the user.
	* 
	* \param dateOfHire - The employee's date of hire 
	* given by the user.
	* 
	* \param dateOfTermination - The employee's date of termination 
	* given by the user.
	* 
	* \param errorMessage - The error message container 
	* which is passed as a reference from the calling method
	* 
	* \return - Returns 0 if the attribute is valid.
	* Returns 1 if the attribute is not valid.
	*/
	function ValidateDateOfBirth($dateOfBirth, $dateOfHire, $dateOfTermination, &$errorMessage)
	{
		$validateStatus = 0;
		$ageRequirement = 16;
		$errorMessage = "";

		$dateOfBirthParse = explode('-', $dateOfBirth);
		$dateOfHireParse = explode('-', $dateOfHire);
		
		$dateOfBirthYear = $dateOfBirthParse[0];
		$dateOfHireYear = $dateOfHireParse[0];
		
		if ($dateOfBirth != "1000-01-01")
		{
			if ($dateOfHire != "1000-01-01")
			{
				if (($dateOfHireYear - $dateOfBirthYear) < $ageRequirement)
				{
					$validateStatus = 1;
					$errorMessage .= "Please Be Sure The Employee Is Over 16 Years Old</br>Before Hiring</br></br>";
				}
			}

			if ($dateOfTermination != "1000-01-01")
			{
				if ($dateOfBirth > $dateOfTermination)
				{
					$validateStatus = 1;
					$errorMessage .= "Please Be Sure The Employee Is Over 16 Years Old</br>Before Terminating</br></br>";
				}
			}
		}
		else 
		{
			$validateStatus = 1;
			$errorMessage = "Please Enter A Valid Date Of Birth</br>";
		}

		return $validateStatus;
	}
	
	/**
	* \brief Sets the dateOfBirth attribute within the Employee class
	*
	* \details <b>Details</b>
	*
	* This method will take in a dateOfBirth variable representing the 
	* employee's date of birth and check whether or not it is a
	* valid entry. Returns a true or false depending on whether or not the 
	* attribute is valid.
	* 
	* \param dateOfBirth The employee's date of birth 
	* given by the user.
	* 
	* \param errorMessage The error message container 
	* which is passed as a reference from the calling method
	* 
	* \return Returns 0 if the attribute is valid.
	* Returns 1 if the attribute is not valid.
	*/
	function ValidateDateOfBirth2($dateOfBirth, &$errorMessage)
	{
		$validateStatus = 0;

		if ($dateOfBirth != "1000-01-01")
		{
			if ($dateOfBirth > date('Y-m-d'))
			{
				$validateStatus = 1;
				$errorMessage = "Please Be Sure The Business Creation Date Does Not Exceed The Present Date</br>" . date("Y-m-d") . "</br></br>";
			}
		}
		else
		{
			$validateStatus = 1;
			$errorMessage = "Please Enter A Valid Date Of Birth</br>";
		}

		return $validateStatus;
	}
	
	/**
	* \brief Sets the dateOfHire attribute within the FulltimeEmployee class
	*
	* \details <b>Details</b>
	*
	* This method will take in a dateOfHire variable representing the 
	* employee's date of hire and check whether or not it is a
	* valid entry. Returns a true or false depending on whether or not the 
	* attribute is valid.
	* 
	* \param dateOfBirth The employee's date of birth 
	* given by the user.
	* 
	* \param dateOfHire The employee's date of hire 
	* given by the user.
	* 
	* \param dateOfTermination The employee's date of termination 
	* given by the user.
	* 
	* \param errorMessage - The error message container 
	* which is passed as a reference from the calling method
	* 
	* \return - Returns 0 if the attribute is valid.
	* Returns 1 if the attribute is not valid.
	*/
	function ValidateDateOfHire($dateOfBirth, $dateOfHire, $dateOfTermination, &$errorMessage)
	{
		$validateStatus = 0;
		$ageRequirement = 16;
		$errorMessage = "";

		$dateOfBirthParse = explode('-', $dateOfBirth);
		$dateOfHireParse = explode('-', $dateOfHire);
		
		$dateOfBirthYear = $dateOfBirthParse[0];
		$dateOfHireYear = $dateOfHireParse[0];
		
		if ($dateOfHire != "1000-01-01")
		{
			if ($dateOfHire > date("Y-m-d"))
			{
				$validateStatus = 1;
				$errorMessage .= "Please Be Sure The Date Of Hire Does Not Exceed The Present Day\n" . date("Y-m-d") . "\n";
			}

			if ($dateOfBirth != "1000-01-01")
			{
				if (($dateOfHireYear - $dateOfBirthYear) < $ageRequirement)
				{
					$validateStatus = 1;
					$errorMessage .= "Please Be Sure The Employee Is Over 16 Years Old\nBefore Hiring\n\n";
				}
			}

			if ($dateOfTermination != "1000-01-01")
			{
				if ($dateOfHire > $dateOfTermination)
				{
					$validateStatus = 1;
					$errorMessage .= "Please Be Sure The Date Of Hire Does Not Exceed The Date Of Termination:\n" . $dateOfTermination . "\n\n";
				}
			}
		}
		else
		{
			$validateStatus = 1;
			$errorMessage = "Please Enter A Valid Date Of Hire\n";
		}

		return $validateStatus;
	}
	
	/**
	* \brief Sets the dateOfTermination attribute within the FulltimeEmployee class
	*
	* \details <b>Details</b>
	*
	* This method will take in a dateOfTermination variable representing the 
	* employee's date of termination and check whether or not it is a
	* valid entry. Returns a true or false depending on whether or not the 
	* attribute is valid.
	* 
	* \param dateOfBirth - The employee's date of birth 
	* given by the user.
	* 
	* \param dateOfHire - The employee's date of hire 
	* given by the user.
	* 
	* \param dateOfTermination - The employee's date of termination 
	* given by the user.
	* 
	* \param errorMessage - The error message container 
	* which is passed as a reference from the calling method
	* 
	* \return - Returns 0 if the attribute is valid.
	* Returns 1 if the attribute is not valid.
	*/
	function ValidateDateOfTermination($dateOfBirth, $dateOfHire, $dateOfTermination, &$errorMessage)
	{
		$validateStatus = 0;
		$ageRequirement = 16;
		$errorMessage = "";

		$dateOfBirthParse = explode('-', $dateOfBirth);
		$dateOfTerminationParse = explode('-', $dateOfTermination);
		
		$dateOfBirthYear = $dateOfBirthParse[0];
		$dateOfTerminationYear = $dateOfTerminationParse[0];
		
		if ($dateOfTermination != "1000-01-01")
		{
			if ($dateOfTermination > date("Y-m-d"))
			{
				$validateStatus = 1;
				$errorMessage .= "Please Be Sure The Date Of Termination Does Not Exceed The Present Day\n" . date("Y-d-m") . "\n\n";
			}

			if ($dateOfBirth != "1000-01-01")
			{
				if (($dateOfTerminationYear - $dateOfBirthYear) < $ageRequirement)
				{
					$validateStatus = 1;
					$errorMessage .= "Please Be Sure The Employee Is Over 16 Years Old\nBefore Terminating\n\n";
				}
			}

			if ($dateOfHire != "1000-01-01")
			{
				if ($dateOfTermination < $dateOfHire)
				{
					$validateStatus = 1;
					$errorMessage .= "Please Be Sure The Date Of Termination Does Not Precede The Date Of Hire\n" . $dateOfHire . "\n\n";
				}
			}
		}
		else
		{
			$validateStatus = 1;
			$errorMessage = "Please Enter A Valid Date Of Termination\n";
		}

		return $validateStatus;
	}
	
	 /**
	* \brief Sets the salary attribute within the FulltimeEmployee class
	*
	* \details <b>Details</b>
	*
	* This method will take in a salary variable representing the 
	* employee's wage and check whether or not it is a
	* valid entry. Returns a true or false depending on whether or not the 
	* attribute is valid.
	* 
	* \param salary - The amount of money the employee makes
	* given by the user.
	* 
	* \param errorMessage - The error message container 
	* which is passed as a reference from the calling method
	* 
	* \return - Returns 0 if the attribute is valid.
	* Returns 1 if the attribute is not valid.
	*/
	function ValidateSalary($salary, &$errorMessage)
	{
		$validateStatus = 0;
		$salaryMinimum = 0;
		$errorMessage = "";

		if ($salary == $salaryMinimum)
		{
			$validateStatus = 1;
			$errorMessage = "Please Be Sure To Enter A Non-Zero Salary.\n";
		}

		if($salary < $salaryMinimum)
		{
			$validateStatus = 1;
			$errorMessage = "Please Be Sure To Enter A Non-Negative Salary.\n";
		}

		return $validateStatus;
	}
	
	/**
	* \brief Sets the contractStartDate attribute within the ContractEmployee class
	*
	* \details <b>Details</b>
	*
	* This method will take in a contractStartDate variable representing the 
	* employee's contract starting date and check whether or not it is a
	* valid entry. Returns a true or false depending on whether or not the 
	* attribute is valid.
	* 
	* \param dateOfBirth - The employee's date of birth 
	* given by the user.
	* 
	* \param contractStartDate - The employee's date of contract start date
	* given by the user.
	* 
	* \param contractStopDate - The employee's contract stop date 
	* given by the user.
	* 
	* \param errorMessage - The error message container 
	* which is passed as a reference from the calling method
	* 
	* \return - Returns 0 if the attribute is valid.
	* Returns 1 if the attribute is not valid.
	*/
	function ValidateContractStartDate($dateOfBirth, $contractStartDate, $contractStopDate, &$errorMessage)
	{
		$validateStatus = 0;
		$errorMessage = "";

		$dateOfBirthParse = explode('-', $dateOfBirth);
		$contractStartDateParse = explode('-', $contractStartDate);
		
		$dateOfBirthYear = $dateOfBirthParse[0];
		$contractStartDateYear = $contractStartDateParse[0];
		
		if ($contractStartDate != "1000-01-01")
		{
			if ($contractStartDate > date("Y-m-d"))
			{
				$validateStatus = 1;
				$errorMessage = "Please Be Sure The Contract Start Date Does Not Exceed The Present Day\n" . date("Y-m-d") . "\n\n";
			}

			if ($dateOfBirth != "1000-01-01")
			{
				if ($contractStartDateYear < $dateOfBirthYear)
				{
					$validateStatus = 1;
					$errorMessage = "Please Be Sure The Contract Start Date Does Not Precede The Company's Creation Date\n" . $dateOfBirth . "\n\n";
				}
			}

			if ($contractStopDate != "1000-01-01")
			{
				if ($contractStartDate > $contractStopDate)
				{
					$validateStatus = 1;
					$errorMessage = "Please Be Sure The Contract Start Date Does Not Exceed The Contract Stop Date\n" . $contractStopDate . "\n\n";
				}
			}
		}
		else
		{
			$validateStatus = 1;
			$errorMessage = "Please Enter A Valid Contract Start Date\n";
		}

		return $validateStatus;
	}
	
	/**
	* \brief Sets the contractStopDate attribute within the ContractEmployee class
	*
	* \details <b>Details</b>
	*
	* This method will take in a contractStopDate variable representing the 
	* employee's contract ending date and check whether or not it is a
	* valid entry. Returns a true or false depending on whether or not the 
	* attribute is valid.
	* 
	* \param dateOfBirth - The employee's date of birth 
	* given by the user.
	* 
	* \param contractStartDate - The employee's date of contract start date
	* given by the user.
	* 
	* \param contractStopDate - The employee's contract stop date 
	* given by the user.
	* 
	* \param errorMessage - The error message container 
	* which is passed as a reference from the calling method
	* 
	* \return - Returns true if the attribute is valid.
	* Returns false if the attribute is not valid.
	*/
	function ValidateContractStopDate($dateOfBirth, $contractStartDate, $contractStopDate, &$errorMessage)
	{
		$validateStatus = 0;
		$errorMessage = "";
		
		$dateOfBirthParse = explode('-', $dateOfBirth);
		$contractStopDateParse = explode('-', $contractStopDate);
		
		$dateOfBirthYear = $dateOfBirthParse[0];
		$contractStopDateYear = $contractStopDateParse[0];
		
		if ($contractStopDate != "1000-01-01")
		{
			if ($dateOfBirth != "1000-01-01")
			{
				if ($contractStopDateYear < $dateOfBirthYear)
				{
					$validateStatus = 1;
					$errorMessage = "Please Be Sure The Contract Stop Date Does Not Precede The Company's Creation Date\n" . $dateOfBirth . "\n\n";
				}
			}

			if ($contractStartDate != "1000-01-01")
			{
				if ($contractStopDate < $contractStartDate)
				{
					$validateStatus = 1;
					$errorMessage = "Please Be Sure The Contract Stop Date Does Not Precede The Contract Start Date\n" . $contractStartDate . "\n\n";
				}
			}
		}
		else
		{
			$validateStatus = 1;
			$errorMessage = "Please Enter A Valid Contract Stop Date\n";
		}

		return $validateStatus;
	}
	
	/**
	* \brief Sets the salary fixedContractAmount within the ContractEmployee class
	*
	* \details <b>Details</b>
	*
	* This method will take in a fixedContractAmount representing the 
	* employee's contract wage and check whether or not it is a
	* valid entry. Returns a true or false depending on whether or not the 
	* attribute is valid.
	* 
	* \param fixedContractAmount - The amount of money the employee will make
	* for the given contract given by the user.
	* 
	* \param errorMessage - The error message container 
	* which is passed as a reference from the calling method
	* 
	* \return - Returns 0 if the attribute is valid.
	* Returns 1 if the attribute is not valid.
	*/
	function ValidateFixedContractAmount($fixedContractAmount, &$errorMessage)
	{
		$validateStatus = 0;
		$amountMinimum = 0;
		$errorMessage = "";

		if ($fixedContractAmount == $amountMinimum)
		{
			$validateStatus = 1;
			$errorMessage = "Please Be Sure To Enter A Non-Zero Amount.\n";
		}
				
		if ($fixedContractAmount < $amountMinimum)
		{
			$validateStatus = 1;
			$errorMessage = "Please Be Sure To Enter A Non-Negative Amount.\n";
		}

		return $validateStatus;
	}
	
	/**
	* \brief Sets the hourlyRate attribute within the ParttimeEmployee class
	*
	* \details <b>Details</b>
	*
	* This method will take in a hourlyRate representing the 
	* employee's hourly wage and check whether or not it is a
	* valid entry. Returns a true or false depending on whether or not the 
	* attribute is valid.
	* 
	* \param hourlyRate - The amount of money the employee makes per
	* hour given by the user.
	* 
	* \param errorMessage - The error message container 
	* which is passed as a reference from the calling method
	* 
	* \return - Returns 0 if the attribute is valid.
	* Returns 1 if the attribute is not valid.
	*/
	function ValidateHourlyRate($hourlyRate, &$errorMessage)
	{
		$validateStatus = 0;
		$rateMinimum = 0;
		$errorMessage = "";

		if ($hourlyRate == $rateMinimum)
		{
			$validateStatus = 1;
			$errorMessage = "Please Be Sure To Enter A Non-Zero Rate.\n";
		}
		
		if ($hourlyRate <= $rateMinimum)
		{
			$validateStatus = 1;
			$errorMessage = "Please Be Sure To Enter A Non-Negative Rate.\n";
		}

		return $validateStatus;
	}
	
	/**
	* \brief Validates the season attribute within the SeasonalEmployee class
	*
	* \details <b>Details</b>
	*
	* This method will take in a string of user input representing the desired
	* season the employee will be working and check whether or not it is a
	* valid season. Returns a true or false depending on whether or not the 
	* attribute is valid.
	* 
	* \param season - The season the employee will be employed in 
	* given by the user.
	* 
	* \param errorMessage - The error message container 
	* which is passed as a reference from the calling method
	* 
	* \return - Returns 0 if the attribute is valid.
	* Returns 1 if the attribute is not valid.
	*/
	function ValidateSeason($season, &$errorMessage)
	{
		$validateStatus = 0;

		if(strlen($season) >= 4)
		{
			$season = strtolower($season);
			$season[0] = strtoupper($season[0]);

			$errorMessage = "";

			if($season != "Summer" && $season != "Fall" && $season != "Winter" && $season != "Spring")
			{
				$validateStatus = 1;
				$errorMessage = "Please Enter A Valid Season.\n";
			}
		}
		else
		{
			$validateStatus = 1;
			$errorMessage = "Please Be Sure To Enter A Season\n";
		}

		return $validateStatus;
	}
	
	/**
	* \brief Validates the piecePay attribute within the SeasonalEmployee class
	*
	* \details <b>Details</b>
	*
	* This method will take in a piecePay representing the desired
	* piece pay the employee will receive and check whether or not it is a
	* valid entry. Returns a true or false depending on whether or not the 
	* attribute is valid.
	* 
	* \param piecePay - The piece pay the employee will recieve 
	* given by the user.
	* 
	* \param errorMessage - The error message container 
	* which is passed as a reference from the calling method
	* 
	* \return - Returns 0 if the attribute is valid.
	* Returns 1 if the attribute is not valid.
	*/
	function ValidatePiecePay($piecePay, &$errorMessage)
	{
		$validateStatus = 0;
		$payMinimum = 0;
		$errorMessage = "";

		if ($piecePay == $payMinimum)
		{
			$validateStatus = 1;
			$errorMessage = "Please Be Sure To Enter A Non-Zero Pay Amount.\n";
		}
		
		if ($piecePay < $payMinimum)
		{
			$validateStatus = 1;
			$errorMessage = "Please Be Sure To Enter A Non-Negative Pay Amount.\n";
		}

		return $validateStatus;
	}
	
	/**
	* \brief Validates the businessNumber attribute within the Employee class
	*
	* \details <b>Details</b>
	*
	* This method will take in a string of user input representing the desired
	* company business number and check whether or not it is a
	* valid business number. Returns a true or false depending on whether or not the 
	* attribute is valid.
	* 
	* \param businessNumber - The company's business
	* number given by the user.
	* 
	* \param dateOfCreation - The company's date of creation 
	* given by the user.
	* 
	* \param errorMessage - The error message container 
	* which is passed as a reference from the calling method
	* 
	* \return - Returns 0 if the attribute is valid.
	* Returns 1 if the attribute is not valid.
	*/
	function ValidateBusinessNumber($businessNumber, $dateOfCreation, &$errorMessage)
	{
		$validateStatus = 0;
		
		$errorMessage = "";

		if ($dateOfCreation != "1000-01-01")
		{
			for ($i = 0; $i < 2; $i++)
			{
				if ($businessNumber[$i] != $dateOfCreation[intval($i + 2)])
				{
					$validateStatus = 1;
					$errorMessage = "Please Be Sure The Business Number's First Two Digits\nMatch The Business' Date Of Creation's Year.\nex. Year: 1982\n  Business#: 82xxx xxxx";
				}
			}
		}

		if($validateStatus == 0)
		{
			if(ValidateSocialInsuranceNumber($businessNumber, $errorMessage) == 1)
			{
				$validateStatus = 1;
			}
		}
		return $validateStatus;
	}
	
	/**
	* \brief Sets the "dateOfCreation" attribute within the Employee class
	*
	* \details <b>Details</b>
	*
	* This method will take in a dateOfCreation variable representing the 
	* employee's date of birth and check whether or not it is a
	* valid entry. Returns a true or false depending on whether or not the 
	* attribute is valid. NOTE: dateOfBirth in the employee class represents
	* the dateOfCreation attribute for the ContractEmployee class.
	* 
	* \param businessNumbeR - The company's business
	* number given by the user.
	* 
	* \param dateOfCreatioN - The company's date of creation 
	* given by the user.
	* 
	* \param errorMessage - The error message container 
	* which is passed as a reference from the calling method
	* 
	* \return bool - Returns 0 if the attribute is valid.
	* Returns 1 if the attribute is not valid.
	*/
	function ValidateDateOfCreation($businessNumber, $dateOfCreation, &$errorMessage)
	{
		$validateStatus = 0;
		
		$errorMessage = "";

		if ($dateOfCreation != "1000-01-01")
		{
			if (ValidateDateOfBirth2($dateOfCreation, $errorMessage) == 1)
			{
				$validateStatus = 1;
			}
			else if($businessNumber != "")
			{
				for ($i = 0; $i < 2; $i++)
				{
					if ($dateOfCreation[$i] != $businessNumber[($i + 2)])
					{
						$validateStatus = 1;
						$errorMessage .= "Please Be Sure The Business' Date Of Creation's Year\nMatches The Business Number's First Two Digits.\nex. Year: 1982\n  Business#: 82xxx xxxx\n";
					}
				}
			}
		}
		else
		{
			$validateStatus = false;
			$errorMessage = "Please Enter A Valid Date Of Creation\n";
		}

		return $validateStatus;
	}
?>