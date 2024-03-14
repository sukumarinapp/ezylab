update macho_user_page_acceses set menu_icon='fa fas fa-receipt rupee-icon' where id=27;
update macho_user_page_acceses set menu_icon='fa fas fa-coins rupee-icon' where id=28;
update macho_user_page_acceses set menu_icon='fa fas fa-credit-card rupee-icon' where id=25;
ALTER TABLE macho_test_type ADD sample_type VARCHAR(100) default NULL;
ALTER TABLE macho_users ADD colour VARCHAR(10) NOT NULL default value 'theme1';
update macho_test_type set method=NULL where method='';


CREATE TABLE `software_validation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

alter table macho_users modify dob varchar(20) DEFAULT NULL;
alter table macho_users modify salary_amount decimal(10,2) DEFAULT 0;
alter table macho_users modify salary_percentage decimal(10,2) DEFAULT 0;
alter table macho_users modify salary_duration_type int DEFAULT 0;
alter table macho_users modify service_from varchar(20) DEFAULT NULL;
alter table macho_users modify service_to varchar(20) DEFAULT NULL;
alter table macho_users modify access_token varchar(128) DEFAULT NULL;
alter table macho_users modify reset_key varchar(64) DEFAULT NULL;
alter table macho_users modify created varchar(30) DEFAULT NULL;
alter table macho_users modify modified varchar(30) DEFAULT NULL;
alter table macho_users modify editby int DEFAULT 0;
alter table macho_role modify modified varchar(30) DEFAULT NULL;
alter table macho_user_page_acceses modify modified varchar(30) DEFAULT NULL;


alter table macho_info add prefix varchar(10) DEFAULT NULL;
update macho_info set prefix = 'DEMO';
update macho_info set prefix = 'HCDC';
update macho_info set prefix = 'RMDH';

alter table macho_info add show_report_header int DEFAULT 0;
alter table macho_info add show_receipt_header int DEFAULT 0;

alter table macho_info add receipt_header varchar(1000) DEFAULT NULL;
alter table macho_info add report_header varchar(1000) DEFAULT NULL;
alter table macho_info add report_footer varchar(500) DEFAULT NULL;
