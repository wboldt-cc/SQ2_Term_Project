USE EMS;

INSERT INTO access_level (accessDescription) VALUES ('admin');
INSERT INTO access_level (accessDescription) VALUES ('general');

INSERT INTO Users VALUES ('general1', 'cr@zyR@bb1t', null, null, (SELECT accessLevel FROM access_level WHERE accessDescription = 'general'));

INSERT INTO Users VALUES ('general2', 'sly0ldF0x', null, null, (SELECT accessLevel FROM access_level WHERE accessDescription = 'general'));

INSERT INTO Users VALUES ('fredAnd', '3th31Mrtz', null, null, (SELECT accessLevel FROM access_level WHERE accessDescription = 'general'));

INSERT INTO Users VALUES ('admin', 'ems-pss-admin', null, null, (SELECT accessLevel FROM access_level WHERE accessDescription = 'admin'));


CREATE USER 'general1'@'localhost' IDENTIFIED BY 'cr@zyR@bb1t';
GRANT ALL PRIVILEGES ON EMS.* TO 'general1'@'localhost';

CREATE USER 'general2'@'localhost' IDENTIFIED BY 'sly0ldF0x';
GRANT ALL PRIVILEGES ON EMS.* TO 'general2'@'localhost';

CREATE USER 'fredAnd'@'localhost' IDENTIFIED BY '3th31Mrtz';
GRANT ALL PRIVILEGES ON EMS.* TO 'general1'@'localhost';

CREATE USER 'admin'@'localhost' IDENTIFIED BY 'ems-pss-admin';
GRANT ALL PRIVILEGES ON EMS.* TO 'admin'@'localhost';


select * from users;