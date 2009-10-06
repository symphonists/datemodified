# Date Modified Field

- Version: 1.0
- Author: craig zheng
- Build Date: 5th October 2009
- Requirements: Symphony 2.*

A simple extension of the built-in date field that will record the time an entry is saved.

## Installation

1. Upload the 'datemodified' folder in this archive to your Symphony 'extensions' folder.

2. Enable it by selecting the "Field: Date Modified", choose Enable from the with-selected menu, then click Apply. You can now add the "Date Modified" field to your sections.

## Usage

When you add the field to a section, the only available option is a checkbox to indicate whether or not the field should be manually editable: 
	
- Unchecked (default): the field will not be displayed in the entry publishing screen and the value of the field will be automatically set when the entry is saved.

- Checked: the field will appear (pre-populated with the current timestamp as of page load) and its value will be used when the entry is saved.
