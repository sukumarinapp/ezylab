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