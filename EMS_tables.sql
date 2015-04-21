USE EMS
GO

CREATE TABLE access_level
(
	accessLevel int PRIMARY KEY,
	accessDescription varchar(200)
);


CREATE TABLE Users
(
	userID varchar(25) PRIMARY KEY,
	userPassword varchar(30),
	u_firstName varchar(15),
	u_lastName varchar(15),
	securityLevel int FOREIGN KEY REFERENCES access_level(accessLevel)
);


CREATE TABLE Company
(
	companyID int PRIMARY KEY,
	companyName varchar(50)
);


CREATE TABLE Person
(
	p_firstName varchar(15),
	p_lastName varchar(15),
	si_number varchar(9) NOT NULL UNIQUE,
	date_of_birth date,
	p_id int PRIMARY KEY
);


CREATE TABLE Employee
(
	emp_id int PRIMARY KEY,
	person_id int FOREIGN KEY REFERENCES Person(p_id)
);


CREATE TABLE Fulltime_Employee
(
	ft_employee_id int FOREIGN KEY REFERENCES Employee(emp_id),
	ft_company_id int FOREIGN KEY REFERENCES Company(companyID),
	ft_date_of_hire date,
	ft_date_of_termination date,
	salary float
	PRIMARY KEY (ft_employee_id, ft_company_id)
);


CREATE TABLE Parttime_Employee
(
	pt_employee_id int FOREIGN KEY REFERENCES Employee(emp_id),
	pt_company_id int FOREIGN KEY REFERENCES Company(companyID),
	pt_date_of_hire date,
	pt_date_of_termination date,
	hourlyRate float
	PRIMARY KEY (pt_employee_id, pt_company_id)
);


CREATE TABLE Contract_Employee
(
	ct_employee_id int FOREIGN KEY REFERENCES Employee(emp_id),
	ct_company_id int FOREIGN KEY REFERENCES Company(companyID),
	contract_start_date date,
	contract_stop_date date,
	fixedContractAmount float
	PRIMARY KEY (ct_employee_id, ct_company_id)
);


CREATE TABLE Seasonal_Employee
(
	sn_employee_id int FOREIGN KEY REFERENCES Employee(emp_id),
	sn_company_id int FOREIGN KEY REFERENCES Company(companyID),
	season varchar(7),
	season_year int,
	piece_pay float,
	PRIMARY KEY (sn_employee_id, sn_company_id)
);


CREATE TABLE Time_Cards
(
	tc_employee_id int FOREIGN KEY REFERENCES Employee(emp_id),
	tc_company_id int FOREIGN KEY REFERENCES Company(companyID),
	pay_period_start_date date,
	hours_worked float,
	pieces_completed float,
	PRIMARY KEY (tc_employee_id, tc_company_id, pay_period_start_date)
);


CREATE TABLE Audits
(
	audit_id int PRIMARY KEY,
	au_employee_id int FOREIGN KEY REFERENCES Employee(emp_id),
	time_of_action date,
	audited_action varchar(50),
	audited_field varchar(50),
	old_value varchar(50),
	new_value varchar(50)
)