update macho_user_page_acceses set menu_icon='fa fas fa-receipt rupee-icon' where id=27;
update macho_user_page_acceses set menu_icon='fa fas fa-coins rupee-icon' where id=28;
update macho_user_page_acceses set menu_icon='fa fas fa-credit-card rupee-icon' where id=25;
ALTER TABLE macho_test_type ADD sample_type VARCHAR(100) default NULL;
update macho_test_type set method=NULL where method='';