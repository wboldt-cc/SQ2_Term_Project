/* Insert Employees */
INSERT INTO Person (p_firstname, p_lastname, si_number, date_of_birth)
VALUES ("Bob", "Smith", 555111228, '1945-06-20');

INSERT INTO Employee (emp_id, person_id)
VALUES (LAST_INSERT_ID(), LAST_INSERT_ID());

INSERT INTO fulltime_employee
VALUES (LAST_INSERT_ID(), (SELECT companyID FROM Company WHERE companyName = "Bob's Fish and Tackle"), '2005-01-01', null, null, 45000.50, (SELECT status_id FROM Employee_Status WHERE status_type = 'Active'));


/* Insert Employees */
INSERT INTO Person (p_firstname, p_lastname, si_number, date_of_birth)
VALUES ("Larry", "Budmelman", 851222125, '1958-08-30');

INSERT INTO Employee (emp_id, person_id)
VALUES (LAST_INSERT_ID(), LAST_INSERT_ID());

INSERT INTO fulltime_employee
VALUES (LAST_INSERT_ID(), (SELECT companyID FROM Company WHERE companyName = "VeraCorp Inc"), '1990-03-15', null, null, null, (SELECT status_id FROM Employee_Status WHERE status_type = 'Active'));


/* Insert Employees */
INSERT INTO Person (p_firstname, p_lastname, si_number, date_of_birth)
VALUES ("Frank", "Findley", 995642352, null);

INSERT INTO Employee (emp_id, person_id)
VALUES (LAST_INSERT_ID(), LAST_INSERT_ID());

INSERT INTO fulltime_employee
VALUES (LAST_INSERT_ID(), (SELECT companyID FROM Company WHERE companyName = "FF-Fresh Fruit Corp"), '1999-12-31', null, null, 45000.50, (SELECT status_id FROM Employee_Status WHERE status_type = 'Incomplete'));


/* Insert Employees */
INSERT INTO Person (p_firstname, p_lastname, si_number, date_of_birth)
VALUES ("Darryl", "Smith", 193456787, '1960-02-29');

INSERT INTO Employee (emp_id, person_id)
VALUES (LAST_INSERT_ID(), LAST_INSERT_ID());

INSERT INTO fulltime_employee
VALUES (LAST_INSERT_ID(), (SELECT companyID FROM Company WHERE companyName = "Bob's Fish and Tackle"), '2008-12-05', '2015-01-27', "Fired", 32768.00, (SELECT status_id FROM Employee_Status WHERE status_type = 'Inactive'));



/* Insert Employees */
INSERT INTO parttime_employee
VALUES (LAST_INSERT_ID(), (SELECT companyID FROM Company WHERE companyName = "Joe's Gas and Feed"), '2010-11-25', null, null, 10.25, (SELECT status_id FROM Employee_Status WHERE status_type = 'Active'));


/* Insert Employees */
INSERT INTO Person (p_firstname, p_lastname, si_number, date_of_birth)
VALUES ("Sally", "Struthers", 654852458, '1971-07-03');

INSERT INTO Employee (emp_id, person_id)
VALUES (LAST_INSERT_ID(), LAST_INSERT_ID());

INSERT INTO parttime_employee
VALUES (LAST_INSERT_ID(), (SELECT companyID FROM Company WHERE companyName = "VeraCorp Inc"), '2005-02-14', null, null, 12.56, (SELECT status_id FROM Employee_Status WHERE status_type = 'Active'));


/* Insert Employees */
INSERT INTO Person (p_firstname, p_lastname, si_number, date_of_birth)
VALUES ("Ted", "Martin", 546511247, '1995-07-26');

INSERT INTO Employee (emp_id, person_id)
VALUES (LAST_INSERT_ID(), LAST_INSERT_ID());

INSERT INTO parttime_employee
VALUES (LAST_INSERT_ID(), (SELECT companyID FROM Company WHERE companyName = "Joe's Gas and Feed"), '2012-12-20', null, null, null, (SELECT status_id FROM Employee_Status WHERE status_type = 'Incomplete'));


/* Insert Employees */
INSERT INTO Person (p_firstname, p_lastname, si_number, date_of_birth)
VALUES ("Alice", "Kramdon", 876543216, '1950-09-11');

INSERT INTO Employee (emp_id, person_id)
VALUES (LAST_INSERT_ID(), LAST_INSERT_ID());

INSERT INTO parttime_employee
VALUES (LAST_INSERT_ID(), (SELECT companyID FROM Company WHERE companyName = "VeraCorp Inc"), '1990-03-15', '2009-02-14', 'Retired', 7.56, (SELECT status_id FROM Employee_Status WHERE status_type = 'Inactive'));



/* Insert Employees */
INSERT INTO Person (p_firstname, p_lastname, si_number, date_of_birth)
VALUES ("Tod", "Joad", 325440550, '1980-10-20');

INSERT INTO Employee (emp_id, person_id)
VALUES (LAST_INSERT_ID(), LAST_INSERT_ID());

INSERT INTO seasonal_employee
VALUES (LAST_INSERT_ID(), (SELECT companyID FROM Company WHERE companyName = "FF-Fresh Fruit Corp"), 'Winter', 2013, 2.35, null , (SELECT status_id FROM Employee_Status WHERE status_type = 'Active'));


/* Insert Employees */
INSERT INTO Person (p_firstname, p_lastname, si_number, date_of_birth)
VALUES ("Pa", "Joad", 540654654, '1950-01-10');

INSERT INTO Employee (emp_id, person_id)
VALUES (LAST_INSERT_ID(), LAST_INSERT_ID());

INSERT INTO seasonal_employee
VALUES (LAST_INSERT_ID(), (SELECT companyID FROM Company WHERE companyName = "FF-Fresh Fruit Corp"), 'Winter', 2013, 3.10, null , (SELECT status_id FROM Employee_Status WHERE status_type = 'Active'));


/* Insert Employees */
INSERT INTO Person (p_firstname, p_lastname, si_number, date_of_birth)
VALUES ("Al", "Joad", 252352133, '1987-04-20');

INSERT INTO Employee (emp_id, person_id)
VALUES (LAST_INSERT_ID(), LAST_INSERT_ID());

INSERT INTO seasonal_employee
VALUES (LAST_INSERT_ID(), (SELECT companyID FROM Company WHERE companyName = "FF-Fresh Fruit Corp"), null, null, 3.10, null , (SELECT status_id FROM Employee_Status WHERE status_type = 'Incomplete'));


/* Insert Employees */
INSERT INTO Person (p_firstname, p_lastname, si_number, date_of_birth)
VALUES ("Noah", "Joad", 984372367, '1975-09-22');

INSERT INTO Employee (emp_id, person_id)
VALUES (LAST_INSERT_ID(), LAST_INSERT_ID());

INSERT INTO seasonal_employee
VALUES (LAST_INSERT_ID(), (SELECT companyID FROM Company WHERE companyName = "FF-Fresh Fruit Corp"), 'Fall', 2013, 1.56, 'Fired/Season ended', (SELECT status_id FROM Employee_Status WHERE status_type = 'Inactive'));

/* STOP */

/* Insert Employees */
INSERT INTO Person (p_firstname, p_lastname, si_number, date_of_birth)
VALUES (null, "PoneSDLC", 586554895, '1958-05-05');

INSERT INTO Employee (emp_id, person_id)
VALUES (LAST_INSERT_ID(), LAST_INSERT_ID());

INSERT INTO contract_employee
VALUES (LAST_INSERT_ID(), (SELECT companyID FROM Company WHERE companyName = "VeraCorp Inc"), '2002-11-01', '2015-05-30', 650000.00, null, (SELECT status_id FROM Employee_Status WHERE status_type = 'Active'));


/* Insert Employees */
INSERT INTO Person (p_firstname, p_lastname, si_number, date_of_birth)
VALUES (null, "proF0-Code", 058488370, '2005-03-28');

INSERT INTO Employee (emp_id, person_id)
VALUES (LAST_INSERT_ID(), LAST_INSERT_ID());

INSERT INTO contract_employee
VALUES (LAST_INSERT_ID(), (SELECT companyID FROM Company WHERE companyName = "VeraCorp Inc"), '2012-11-01', '2015-10-31', 75000.00, null, (SELECT status_id FROM Employee_Status WHERE status_type = 'Active'));


/* Insert Employees */
INSERT INTO Person (p_firstname, p_lastname, si_number, date_of_birth)
VALUES (null, "Sally's Cleaning Services Ltd", 102545449, '2010-06-15');

INSERT INTO Employee (emp_id, person_id)
VALUES (LAST_INSERT_ID(), LAST_INSERT_ID());

INSERT INTO contract_employee
VALUES (LAST_INSERT_ID(), (SELECT companyID FROM Company WHERE companyName = "VeraCorp Inc"), null, null, 20000.00, null, (SELECT status_id FROM Employee_Status WHERE status_type = 'Incomplete'));


/* Insert Employees */
INSERT INTO Person (p_firstname, p_lastname, si_number, date_of_birth)
VALUES (null, "poneSDLC", 586554859, '1958-05-05');

INSERT INTO Employee (emp_id, person_id)
VALUES (LAST_INSERT_ID(), LAST_INSERT_ID());

INSERT INTO contract_employee
VALUES (LAST_INSERT_ID(), (SELECT companyID FROM Company WHERE companyName = "VeraCorp Inc"), '1958-05-05', '1992-11-01', 250000.00, 'Contract lapsed', (SELECT status_id FROM Employee_Status WHERE status_type = 'Inactive'));

