<?php

	require_once(TOOLKIT . "/fields/field.date.php");
	if (!defined('__IN_SYMPHONY__')) die('<h2>Symphony Error</h2><p>You cannot directly access this file</p>');
	
	Class fieldDateModified extends fieldDate {
	
	/*-------------------------------------------------------------------------
		Definition:
	-------------------------------------------------------------------------*/
	
		public function __construct(){
			Field::__construct();
			$this->_name = 'Date Modified';
			$this->_required = true;
		}
		
		public function displaySettingsPanel(&$wrapper, $errors = null) {
			Field::displaySettingsPanel($wrapper, $errors);
			
		// Check 'mode' exists; otherwise set manually from 'editable'
			$mode = $this->get('mode');
			
			if(!$mode) {
				if($this->get('editable') == 'yes') {
					$mode = 'normal';
				}
				else {
					$mode = 'hidden';
				}
			}

			$div = new XMLElement('div', NULL, array('class' => 'compact'));
			
			$label = Widget::Label(__('Display As'));
			
			$options = array(
				array(
					'normal',
					($mode == 'normal' ? TRUE : FALSE),
					__('Editable')
				),
				array(
					'disabled',
					($mode == 'disabled' ? TRUE : FALSE),
					__('Disabled')
				),
				array(
					'hidden',
					($mode == 'hidden' ? TRUE : FALSE),
					__('Hidden')
				)
			);
			$input = Widget::Select('fields['.$this->get('sortorder').'][mode]', $options);
			
			$label->appendChild($input);
			$div->appendChild($label);		
			
			$this->appendShowColumnCheckbox($div);
			$wrapper->appendChild($div);
		}
		
		function commit(){
			
			if(!Field::commit()) return false;
			
			$id = $this->get('id');

			if($id === false) return false;	
			
			$fields = array();

			$fields['field_id'] = $id;
			$fields['pre_populate'] = ($this->get('pre_populate') ? $this->get('pre_populate') : 'no');
			$fields['mode'] = ($this->get('mode') ? $this->get('mode') : 'normal');
			
			Symphony::Database()->query("DELETE FROM `tbl_fields_datemodified` WHERE `field_id` = '$id' LIMIT 1");			
			return Symphony::Database()->insert($fields, 'tbl_fields_datemodified');
		}
		
		function displayPublishPanel(&$wrapper, $data = null, $error = null, $prefix = null, $postfix = null) {
			
		// Check 'mode' exists; otherwise set manually from 'editable'
			$mode = $this->get('mode');
			
			if(!$mode) {
				if($this->get('editable') == 'yes') {
					$mode = 'normal';
				}
				else {
					$mode = 'hidden';
				}
			}
			
		// Render the field only if it's not hidden
			if ($mode != 'hidden') {
			
				$name = $this->get('element_name');
				
				$edited_local = DateTimeObj::get(__SYM_DATETIME_FORMAT__, $data['local']);
				$current_local = DateTimeObj::get(__SYM_DATETIME_FORMAT__, null);
			
				$label = Widget::Label($this->get('label'));

				if($mode == 'disabled') {
					$input = Widget::Input("{$name}-display", $edited_local);
					$input->setAttribute('disabled', 'disabled');
				} else {
					$note = new XMLElement('i', __('Previous value: %s', array($edited_local)));
					$label->appendChild($note);
					$input = Widget::Input("fields{$prefix}[{$name}]{$name}", $current_local);
				}
				
				$label->appendChild($input);
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
				$timestamp = strtotime(Lang::standardizeDate(DateTimeObj::get(__SYM_DATETIME_FORMAT__, null)));
			}
			
			else  {
				$timestamp = strtotime(Lang::standardizeDate($data));
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
