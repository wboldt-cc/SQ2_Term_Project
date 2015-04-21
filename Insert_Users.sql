USE EMS;

INSERT INTO access_level (accessDescription) VALUES ('admin');
INSERT INTO access_level (accessDescription) VALUES ('general');

INSERT INTO Users VALUES ('general1', 'cr@zyR@bb1t', null, null, (SELECT accessLevel FROM access_level WHERE accessDescription = 'general'));
INSERT INTO Users VALUES ('general2', 'sly0ldF0x', null, null, (SELECT accessLevel FROM access_level WHERE accessDescription = 'general'));
INSERT INTO Users VALUES ('fredAnd', '3th31Mrtz', null, null, (SELECT accessLevel FROM access_level WHERE accessDescription = 'general'));
INSERT INTO Users VALUES ('admin', 'ems-pss-admin', null, null, (SELECT accessLevel FROM access_level WHERE accessDescription = 'admin'));

select * from users;