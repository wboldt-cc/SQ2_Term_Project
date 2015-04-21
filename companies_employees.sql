USE EMS;

INSERT INTO Company (companyName) VALUES("Bob's Fish and Tackle");
INSERT INTO Company (companyName) VALUES("VeraCorp Inc");
INSERT INTO Company (companyName) VALUES("FF-Fresh Fruit Corp");
INSERT INTO Company (companyName) VALUES("Joe's Gas and Feed");

INSERT INTO Employee_Status (status_type) VALUES ('Active');
INSERT INTO Employee_Status (status_type) VALUES ('Inactive');
INSERT INTO Employee_Status (status_type) VALUES ('Incomplete');

SELECT * FROM Employee_Status;

INSERT INTO Seasons VALUES('Spring', '03-21');
INSERT INTO Seasons VALUES('Summer', '06-21');
INSERT INTO Seasons VALUES('Fall', '09-21');
INSERT INTO Seasons VALUES('Winter', '12-21');



/* Insert Employees */
/*
INSERT INTO Person (p_firstname, p_lastname, si_number, date_of_birth)
VALUES ("Bob", "Smith", 555111228, '1945-06-20');

INSERT INTO Employee (emp_id, person_id)
VALUES (LAST_INSERT_ID(), LAST_INSERT_ID());

INSERT INTO fulltime_employee
VALUES (LAST_INSERT_ID(), LAST_INSERT_ID(), '2005-01-01', null, null, 45000.50, (SELECT status_id FROM Employee_Status WHERE status_type = 'Active'));
*/

/* Fulltime Employee view */
CREATE VIEW FT_View AS
SELECT * FROM Person
JOIN Employee
ON Person.p_id = Employee.person_id
JOIN fulltime_employee
ON Employee.emp_id = fulltime_employee.ft_employee_id;

CREATE VIEW FT_Display AS
SELECT p_firstname as 'First_Name', p_lastName as 'Last_Name', si_number as 'SIN', date_of_birth as 'Date_of_Birth', 
	   ft_date_of_hire as 'Date_of_hire', ft_date_of_termination as 'Date_of_termination', reason_for_termination as 'Reason_for_termination',
       salary as 'Salary', companyName as 'Company', status_type as 'Status'
FROM FT_View
JOIN Company
ON ft_company_id = companyID
JOIN Employee_Status
ON current_status = status_id;



/* Parttime Employee View */
CREATE VIEW PT_View AS
SELECT * FROM Person
JOIN Employee
ON Person.p_id = Employee.person_id
JOIN parttime_employee
ON Employee.emp_id = parttime_employee.pt_employee_id;

CREATE VIEW PT_Display AS
SELECT p_firstname as 'First_Name', p_lastName as 'Last_Name', si_number as 'SIN', date_of_birth as 'Date_of_Birth', 
	   pt_date_of_hire as 'Date_of_hire', pt_date_of_termination as 'Date_of_termination', reason_for_termination as 'Reason_for_termination',
       hourlyRate as 'Hourly_rate', companyName as 'Company', status_type as 'Status'
FROM PT_View
JOIN Company
ON pt_company_id = companyID
JOIN Employee_Status
ON current_status = status_id;


/* Contract Employee View */
CREATE VIEW CT_View AS
SELECT * FROM Person
JOIN Employee
ON Person.p_id = Employee.person_id
JOIN contract_employee
ON Employee.emp_id = contract_employee.ct_employee_id;

CREATE VIEW CT_Dispaly AS
SELECT p_lastName as 'Contract_company_name', si_number as 'Business_Number', date_of_birth as 'Date_of_incorportation', 
	   contract_start_date as 'Contract_start_date', contract_stop_date as 'Contract_end_date', reason_for_termination as 'Reason_for_termination',
       fixedContractAmount as 'Contract_amount', companyName as 'Company', status_type as 'Status'
FROM CT_View
JOIN Company
ON ct_company_id = companyID
JOIN Employee_Status
ON current_status = status_id;


/* Seasonal Employee view */
CREATE VIEW SN_View AS
SELECT * FROM Person
JOIN Employee
ON Person.p_id = Employee.person_id
JOIN seasonal_employee
ON Employee.emp_id = seasonal_employee.sn_employee_id;

CREATE VIEW SN_Display AS
SELECT p_firstname as 'First_Name', p_lastName as 'Last_Name', si_number as 'SIN', date_of_birth as 'Date_of_Birth', 
	   season as 'Season', season_year as 'Year', reason_for_termination as 'Reason_for_termination',
       piece_pay as 'Piece_Pay', companyName as 'Company', status_type as 'Status'
FROM SN_View
JOIN Company
ON sn_company_id = companyID
JOIN Employee_Status
ON current_status = status_id;

SELECT * FROM SN_Display