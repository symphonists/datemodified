<?php

	require_once(TOOLKIT . "/fields/field.date.php");
	if (!defined('__IN_SYMPHONY__')) die('<h2>Symphony Error</h2><p>You cannot directly access this file</p>');
	
	Class fieldDateModified extends fieldDate {
	
	/*-------------------------------------------------------------------------
		Definition:
	-------------------------------------------------------------------------*/
	
		public function __construct(&$parent){
			Field::__construct($parent);
			$this->_name = 'Date Modified';
			$this->_required = true;
		}
		
		public function displaySettingsPanel(&$wrapper, $errors = null) {
			Field::displaySettingsPanel($wrapper, $errors);

			$label = Widget::Label();
			$input = Widget::Input('fields['.$this->get('sortorder').'][editable]', 'yes', 'checkbox');
			if($this->get('editable') == 'yes') $input->setAttribute('checked', 'checked');
			$label->setValue(__('%s Allow this field to be edited manually', array($input->generate())));
			$wrapper->appendChild($label);		
			
			$this->appendShowColumnCheckbox($wrapper);
		}
		
		function commit(){
			
			if(!Field::commit()) return false;
			
			$id = $this->get('id');

			if($id === false) return false;	
			
			$fields = array();

			$fields['field_id'] = $id;
			$fields['pre_populate'] = ($this->get('pre_populate') ? $this->get('pre_populate') : 'no');
			$fields['editable'] = ($this->get('editable') ? $this->get('editable') : 'no');
			
			$this->_engine->Database->query("DELETE FROM `tbl_fields_datemodified` WHERE `field_id` = '$id' LIMIT 1");			
			return $this->_engine->Database->insert($fields, 'tbl_fields_datemodified');
		}
		
		function displayPublishPanel(&$wrapper, $data = null, $error = null, $prefix = null, $postfix = null) {
			if ($this->get('editable') == 'yes') {
				$name = $this->get('element_name');
				$value = DateTimeObj::get(__SYM_DATETIME_FORMAT__, null);
			
				$label = Widget::Label($this->get('label'));
				$label->appendChild(Widget::Input("fields{$prefix}[{$name}]{$name}", $value));
				$label->setAttribute('class', 'date');
			
				if (!is_null($error)) {
					$label = Widget::wrapFormElementWithError($label, $error);
				}
			
				$wrapper->appendChild($label);
			}
		}
		
		public function processRawFieldData($data, &$status, $simulate=false, $entry_id=NULL){
			$status = self::__OK__;
			$timestamp = null;
			
			if (is_null($data) || $data == '') {
				$timestamp = strtotime(DateTimeObj::get(__SYM_DATETIME_FORMAT__, null));
			}
			
			else  {
				$timestamp = strtotime($data);
			}
			
			if (!is_null($timestamp)) {
				return array(
					'value' => DateTimeObj::get('c', $timestamp),
					'local' => strtotime(DateTimeObj::get('c', $timestamp)),
					'gmt' => strtotime(DateTimeObj::getGMT('c', $timestamp))			
				);
			}
			
			return array(
				'value'		=> null,
				'local'		=> null,
				'gmt'		=> null
			);
		}
		
	}
