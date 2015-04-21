USE EMS;

CREATE TABLE seniority_report
(
	emp_name varchar(100),
    emp_sin int,
    emp_type varchar(50),
    hire_date date,
    company_name varchar(50),
    length_of_service int
);
       
/* Fulltime seniority */
INSERT INTO seniority_report
SELECT concat(p_lastname, ', ', p_firstname), si_number, 'Fulltime', ft_date_of_hire, companyName, (DATEDIFF(CURDATE(), ft_date_of_hire))
FROM FT_View
JOIN Company
ON ft_company_id = companyID
WHERE current_status = 1;

select * from seniority_report;


/* Parttime seniority */
INSERT INTO seniority_report
SELECT concat(p_lastname, ', ', p_firstname), si_number, 'Parttime', pt_date_of_hire, companyName, (DATEDIFF(CURDATE(), pt_date_of_hire))
FROM PT_View
JOIN Company
ON pt_company_id = companyID
WHERE current_status = 1;

/* Contract Employees */
INSERT INTO seniority_report
SELECT p_lastname, si_number, 'Contract', contract_start_date, companyName, (DATEDIFF(CURDATE(), contract_start_date))
FROM CT_View
JOIN Company
ON ct_company_id = companyID
WHERE current_status = 1;

/* Seasonal seniority */
INSERT INTO seniority_report
SELECT concat(p_lastname, ', ', p_firstname), si_number, 'Seasonal', CONCAT(season_year, season_start_date), companyName, (DATEDIFF(CURDATE(), CONCAT(season_year, season_start_date)))
FROM SN_View
JOIN Seasons
ON season = season_type
JOIN Company
ON sn_company_id = companyID
WHERE current_status = 1;





/* Fulltime Payroll report */ 
CREATE TABLE FT_Payroll
(
	full_name varchar(50),
    company_id varchar(50),
    si_num int,
    worked_hours float,
    hours_mon float,
    hours_tues float,
    hours_wed float,
    hours_thurs float,
    hours_fri float,
    hours_sat float,
    hours_sun float,
    weekly_pay float,
    pay_date date,
    notes varchar(100)
);

INSERT INTO FT_payroll (full_name, company_id, si_num, hours_mon, hours_tues, hours_wed, hours_thurs, hours_fri, hours_sat, hours_sun, weekly_pay, pay_date)
SELECT CONCAT(p_lastname, ', ', p_firstname), companyName, si_number, mon_hours, tues_hours, wed_hours, thurs_hours, fri_hours, sat_hours, sun_hours, salary, pay_period_start_date
FROM FT_View
JOIN time_cards
ON (ft_employee_id = tc_employee_id) AND (ft_company_id = tc_company_id)
JOIN Company
ON ft_company_id = companyID;

UPDATE FT_payroll
SET weekly_pay = weekly_pay / 52,
	worked_hours = hours_mon + hours_tues + hours_wed + hours_thurs + hours_fri + hours_sat + hours_sun,
	notes = CASE
	WHEN worked_hours < 37.5 THEN 'Not full work week'
    Else ''
END;


/* Parttime Payroll report */
CREATE TABLE PT_Payroll
(
	full_name varchar(50),
    company_id varchar(50),
    si_num int,
    worked_hours float,
    hours_mon float,
    hours_tues float,
    hours_wed float,
    hours_thurs float,
    hours_fri float,
    hours_sat float,
    hours_sun float,
    weekly_pay float,
    pay_date date,
    notes varchar(100)
);

INSERT INTO PT_payroll (full_name, company_id, si_num, hours_mon, hours_tues, hours_wed, hours_thurs, hours_fri, hours_sat, hours_sun, weekly_pay, pay_date)
SELECT CONCAT(p_lastname, ', ', p_firstname), companyName, si_number, mon_hours, tues_hours, wed_hours, thurs_hours, fri_hours, sat_hours, sun_hours, hourlyRate, pay_period_start_date
FROM PT_View
JOIN time_cards
ON (pt_employee_id = tc_employee_id) AND (pt_company_id = tc_company_id) AND (current_status = 1)
JOIN Company
ON pt_company_id = companyID;

UPDATE PT_Payroll
SET worked_hours = hours_mon + hours_tues + hours_wed + hours_thurs + hours_fri + hours_sat + hours_sun,
	weekly_pay = (weekly_pay * worked_hours),
	notes = CASE
	WHEN worked_hours > 40 THEN (worked_hours - 40)
    ELSE ''
    END;
    

/* Seasonal Payroll report */
CREATE TABLE SN_Payroll
(
	full_name varchar(50),
    company_id varchar(50),
    si_num int,
    worked_hours float,
    hours_mon float,
    hours_tues float,
    hours_wed float,
    hours_thurs float,
    hours_fri float,
    hours_sat float,
    hours_sun float,
    pieces_mon float,
    pieces_tues float,
    pieces_wed float,
    pieces_thurs float,
    pieces_fri float,
    pieces_sat float,
    pieces_sun float,
    weekly_pieces float,
    weekly_pay float,
    pay_date date,
    notes varchar(100)
);

INSERT INTO SN_payroll (full_name, company_id, si_num, hours_mon, hours_tues, hours_wed, hours_thurs, hours_fri, hours_sat, hours_sun, pieces_mon, pieces_tues, pieces_wed, pieces_thurs, pieces_fri, pieces_sat, pieces_sun, weekly_pay, pay_date)
SELECT CONCAT(p_lastname, ', ', p_firstname), companyName, si_number, mon_hours, tues_hours, wed_hours, thurs_hours, fri_hours, sat_hours, sun_hours, mon_pieces, tues_pieces, wed_pieces, thurs_pieces, fri_pieces, sat_pieces, sun_pieces, piece_pay, pay_period_start_date
FROM SN_View
JOIN time_cards
ON (sn_employee_id = tc_employee_id) AND (sn_company_id = tc_company_id) AND (current_status = 1)
JOIN Company
ON sn_company_id = companyID;

UPDATE SN_Payroll
SET weekly_pieces = (pieces_mon + pieces_tues + pieces_wed + pieces_thurs + pieces_fri + pieces_sat + pieces_sun),
	worked_hours = (hours_mon + hours_tues + hours_wed + hours_thurs + hours_fri + hours_sat + hours_sun),
    weekly_pay = weekly_pay * weekly_pieces,
    weekly_pay = CASE
    WHEN worked_hours > 40 THEN (weekly_pay + 150)
    END,
	notes = CASE
    WHEN weekly_pieces = (SELECT max_pieces FROM((SELECT MAX(weekly_pieces) AS max_pieces FROM SN_Payroll) AS a)) THEN 'Most productive'
    END;
    
    
/* Contract Payroll */
CREATE TABLE CT_Payroll
(
	full_name varchar(50),
    company_id varchar(50),
    si_num int,
    contract_start date,
    contract_end date,
    worked_hours varchar(10),
    weekly_pay float,
    pay_date date,
    notes varchar(100)
);

INSERT INTO CT_payroll (full_name, company_id, si_num, contract_start, contract_end, worked_hours, weekly_pay, pay_date)
SELECT p_lastname, companyName, si_number, contract_Start_date, contract_stop_date, '--', fixedContractAmount , pay_period_start_date
FROM CT_View
JOIN time_cards
ON (ct_employee_id = tc_employee_id) AND (ct_company_id = tc_company_id) AND (current_status = 1)
JOIN Company
ON ct_company_id = companyID;

UPDATE CT_Payroll
SET weekly_pay = (weekly_pay * 7 / DATEDIFF(contract_end, contract_start)),
	notes = DATEDIFF(contract_end, CURDATE()) + ' days remaining';
    
    
    
/* Hours worked */
CREATE VIEW FT_hours AS
SELECT full_name, company_id, si_num, worked_hours
FROM FT_Payroll;

CREATE VIEW PT_hours AS
SELECT full_name, company_id, si_num, worked_hours
FROM PT_Payroll;

CREATE VIEW SN_hours AS
SELECT full_name, company_id, si_num, worked_hours
FROM SN_Payroll;

/*
CREATE VIEW FT_hours AS
SELECT CONCAT(p_lastname, ', ', p_firstname) AS 'Name', ft_company_id AS 'Company', si_number AS 'SIN', hours_worked AS 'Hours', pay_period_start_date AS 'Period'
FROM FT_View
JOIN Time_Cards
ON (ft_employee_id = tc_employee_id) AND (ft_company_id = tc_company_id);

CREATE VIEW PT_hours AS
SELECT CONCAT(p_lastname, ', ', p_firstname) AS 'Name', pt_company_id AS 'Company', si_number AS 'SIN', hours_worked AS 'Hours', pay_period_start_date AS 'Period'
FROM PT_View
JOIN Time_Cards
ON (pt_employee_id = tc_employee_id) AND (pt_company_id = tc_company_id);

CREATE VIEW SN_hours AS
SELECT CONCAT(p_lastname, ', ', p_firstname) AS 'Name', sn_company_id AS 'Company', si_number AS 'SIN', hours_worked AS 'Hours', pay_period_start_date AS 'Period'
FROM SN_View
JOIN Time_Cards
ON (sn_employee_id = tc_employee_id) AND (sn_company_id = tc_company_id);
*/

/* Active Employees */
