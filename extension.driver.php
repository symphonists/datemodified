<?php

	Class extension_datemodified extends Extension{

		public function uninstall(){
			Symphony::Database()->query("DROP TABLE `tbl_fields_datemodified`");
		}

		public function update($previousVersion = false){

			try{
				if(version_compare($previousVersion, '1.1', '<')){
					Symphony::Database()->query(
						"ALTER TABLE `tbl_fields_datemodified`
						ADD COLUMN `mode` enum('normal','disabled','hidden')
						NOT NULL
						DEFAULT 'normal'"
					);
				}
				if(version_compare($previousVersion, '1.4', '<')){
					// Drop `local`/`gmt` from Date fields, add `date` column. #693
					$date_fields = Symphony::Database()->fetchCol("field_id", "SELECT `field_id` FROM `tbl_fields_datemodified`");

					foreach($date_fields as $id) {
						$table = 'tbl_entries_data_' . $id;

						// Don't catch an Exception, we should halt updating if something goes wrong here
						// Add the new `date` column for Date fields
						if(!Symphony::Database()->tableContainsField($table, 'date')) {
							Symphony::Database()->query("ALTER TABLE `" . $table . "` ADD `date` DATETIME DEFAULT NULL");
							Symphony::Database()->query("CREATE INDEX `date` ON `" . $table . "` (`date`)");
						}

						if(Symphony::Database()->tableContainsField($table, 'date')) {
							// Populate new Date column
							if(Symphony::Database()->query("UPDATE `" . $table . "` SET date = CONVERT_TZ(SUBSTRING(value, 1, 19), SUBSTRING(value, -6), '+00:00')")) {
								// Drop the `local`/`gmt` columns from Date fields
								if(Symphony::Database()->tableContainsField($table, 'local')) {
									Symphony::Database()->query("ALTER TABLE `" . $table . "` DROP `local`;");
								}

								if(Symphony::Database()->tableContainsField($table, 'gmt')) {
									Symphony::Database()->query("ALTER TABLE `" . $table . "` DROP `gmt`;");
								}
							}
						}

						Symphony::Database()->query("OPTIMIZE TABLE " . $table);
					}
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
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");

		}

	}
