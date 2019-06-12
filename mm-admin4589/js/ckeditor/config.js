/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	config.enterMode = CKEDITOR.ENTER_BR;
	config.shiftEnterMode = CKEDITOR.ENTER_P;
	config.removePlugins = 'forms,iframe,pagebreak,flash,about,style,format,print,templates,pastetext,find,replace,anchor,smiley';
	config.height = 100;
	config.extraPlugins = 'autogrow';
	config.autoGrow_minHeight = 100;
	config.autoGrow_maxHeight = 400;
	config.filebrowserBrowseUrl = '/admin/js/ckfinder/ckfinder.html';
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
};
