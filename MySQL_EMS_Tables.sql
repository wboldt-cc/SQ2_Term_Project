CREATE DATABASE EMS;
USE EMS;

CREATE TABLE access_level
(
	accessLevel int AUTO_INCREMENT,
	accessDescription varchar(200),
    PRIMARY KEY (accessLevel)
);


CREATE TABLE Users
(
	userID varchar(25),
	userPassword varchar(30),
	u_firstName varchar(15),
	u_lastName varchar(15),
	securityLevel int,
    PRIMARY KEY (userID),
    FOREIGN KEY (securityLevel) REFERENCES access_level(accessLevel)
);


CREATE TABLE Company
(
	companyID int AUTO_INCREMENT,
	companyName varchar(50),
    PRIMARY KEY (companyID)
);


CREATE TABLE Person
(
	p_firstName varchar(15),
	p_lastName varchar(15),
	si_number varchar(9) NOT NULL UNIQUE,
	date_of_birth date,
	p_id int AUTO_INCREMENT,
    PRIMARY KEY (p_id)
);


CREATE TABLE Employee_Status
(
	status_id int AUTO_INCREMENT,
    status_type varchar(50),
    PRIMARY KEY (status_id)
);


CREATE TABLE Employee
(
	emp_id int PRIMARY KEY,
	person_id int,
    FOREIGN KEY (person_id) REFERENCES Person(p_id)
);


CREATE TABLE Fulltime_Employee
(
	ft_employee_id int,
	ft_company_id int,
	ft_date_of_hire date,
	ft_date_of_termination date,
    reason_for_termination varchar(50),
	salary float,
    current_status int,
	PRIMARY KEY (ft_employee_id, ft_company_id),
    FOREIGN KEY (ft_employee_id) REFERENCES Employee(emp_id),
    FOREIGN KEY (ft_company_id) REFERENCES Company(companyID),
	FOREIGN KEY (current_status) REFERENCES Employee_Status(status_id)
);


CREATE TABLE Parttime_Employee
(
	pt_employee_id int,
	pt_company_id int,
	pt_date_of_hire date,
	pt_date_of_termination date,
    reason_for_termination varchar(50),
	hourlyRate float,
    current_status int,
	PRIMARY KEY (pt_employee_id, pt_company_id),
    FOREIGN KEY (pt_employee_id) REFERENCES Employee(emp_id),
    FOREIGN KEY (pt_company_id) REFERENCES Company(companyID),
	FOREIGN KEY (current_status) REFERENCES Employee_Status(status_id)
);


CREATE TABLE Contract_Employee
(
	ct_employee_id int,
	ct_company_id int,
	contract_start_date date,
	contract_stop_date date,
	fixedContractAmount float,
    reason_for_termination varchar(50),
    current_status int,
	PRIMARY KEY (ct_employee_id, ct_company_id),
    FOREIGN KEY (ct_employee_id) REFERENCES Employee(emp_id),
    FOREIGN KEY (ct_company_id) REFERENCES Employee(emp_id),
	FOREIGN KEY (current_status) REFERENCES Employee_Status(status_id)
);


CREATE TABLE Seasonal_Employee
(
	sn_employee_id int,
	sn_company_id int,
	season varchar(7),
	season_year int,
	piece_pay float,
    reason_for_termination varchar(50),
    current_status int,
	PRIMARY KEY (sn_employee_id, sn_company_id),
    FOREIGN KEY (sn_employee_id) REFERENCES Employee(emp_id),
    FOREIGN KEY (sn_company_id) REFERENCES Company(companyID),
	FOREIGN KEY (current_status) REFERENCES Employee_Status(status_id)
);

CREATE TABLE Seasons
(
	season_type varchar(7) PRIMARY KEY,
    season_start_date varchar(6)
);


CREATE TABLE Time_Cards
(
	tc_employee_id int,
	tc_company_id int,
	pay_period_start_date date,
    mon_hours float,
    tues_hours float,
    wed_hours float,
    thurs_hours float,
    fri_hours float,
    sat_hours float,
    sun_hours float,
    mon_pieces float,
    tues_pieces float,
    wed_pieces float,
    thurs_pieces float,
    fri_pieces float,
    sat_pieces float,
    sun_pieces float,
	PRIMARY KEY (tc_employee_id, tc_company_id, pay_period_start_date),
    FOREIGN KEY (tc_employee_id) REFERENCES EMPLOYEE(emp_id),
    FOREIGN KEY (tc_company_id) REFERENCES Company(companyID)
);


CREATE TABLE Audits
(
	audit_id int,
	au_employee_id int,
	time_of_action date,
	audited_action varchar(50),
	audited_field varchar(50),
	old_value varchar(50),
	new_value varchar(50),
    PRIMARY KEY (audit_id),
    FOREIGN KEY (au_employee_id) REFERENCES Employee(emp_id)
);
