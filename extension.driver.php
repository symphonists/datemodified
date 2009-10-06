<?php

	Class extension_datemodified extends Extension{
	
		public function about(){
			return array('name' => 'Field: Date Modified',
						 'version' => '1.0',
						 'release-date' => '2009-10-05',
						 'author' => array('name' => 'craig zheng',
										   'email' => 'cz@mongrl.com')
				 		);
		}
		
		public function uninstall(){
			$this->_Parent->Database->query("DROP TABLE `tbl_fields_datemodified`");
		}

		public function install(){

			return $this->_Parent->Database->query("CREATE TABLE 	
			`tbl_fields_datemodified` (	
				`id` int(11) unsigned NOT NULL auto_increment,
				`field_id` int(11) unsigned NOT NULL,
				`pre_populate` enum('yes','no') NOT NULL default 'yes',
				`editable` enum('yes','no') NOT NULL default 'no',
				PRIMARY KEY  (`id`),
				KEY `field_id` (`field_id`)
			) TYPE=MyISAM;");

		}
			
	}
