<?php

	Class extension_datemodified extends Extension{
	
		public function about(){
			return array('name' => 'Field: Date Modified',
						 'version' => '1.2.2',
						 'release-date' => '2011-05-06',
						 'author' => array('name' => 'craig zheng',
										   'email' => 'craig@symphony-cms.com')
				 		);
		}
		
		public function uninstall(){
			Symphony::Database()->query("DROP TABLE `tbl_fields_datemodified`");
		}
		
		public function update($previousVersion){

			try{
				if(version_compare($previousVersion, '1.1', '<')){
					Symphony::Database()->query(
						"ALTER TABLE `tbl_fields_datemodified`
						ADD COLUMN `mode` enum('normal','disabled','hidden')
						NOT NULL
						DEFAULT 'normal'"
					);
				}
			}
			catch(Exception $e){
				// Discard
			}
		}

		public function install(){

			return Symphony::Database()->query("CREATE TABLE 	
			`tbl_fields_datemodified` (	
				`id` int(11) unsigned NOT NULL auto_increment,
				`field_id` int(11) unsigned NOT NULL,
				`pre_populate` enum('yes','no') NOT NULL default 'yes',
				`editable` enum('yes','no') NOT NULL default 'no',
				`mode` enum('normal','disabled','hidden') NOT NULL default 'normal',
				PRIMARY KEY  (`id`),
				KEY `field_id` (`field_id`)
			) TYPE=MyISAM;");

		}
			
	}
