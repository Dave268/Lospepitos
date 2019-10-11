/**
 * @license Copyright (c) 2003-2019, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	config.language = 'fr';
	// config.uiColor = '#AADC6E';

	config.toolbarGroups = [
		//{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links' },
		//{ name: 'insert' },
		//{ name: 'forms' },
		{ name: 'tools' },
		{ name: 'document',	   groups: [ 'mode' ] },
		{ name: 'others' },
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align' ] },
		{ name: 'styles' },
		{ name: 'colors' },
		//{ name: 'about' }
	];

    //config.contentCss = "https://fonts.googleapis.com/css?family=Cedarville+Cursive"
	
    config.extraPlugins = "bootstrapVisibility, pastetext";

    config.contentsCss = 'https://fonts.googleapis.com/css?family=Andada|Buenard|Cedarville+Cursive|Cookie|Cormorant+Garamond|Dancing+Script|EB+Garamond|Indie+Flower|Montserrat|Old+Standard+TT|Pacifico&display=swap';
    config.font_names = "'Montserrat';'Cedarville Cursive';'Indie Flower';'Pacifico';'Dancing Script';'EB Garamond';'Old Standard TT';'Cormorant Garamond';'Cookie';'Buenard';'Andada';";
    config.font_defaultLabel = 'Montserrat';
    config.fontSize_defaultLabel = '12px';
	
	//config.removePlugins = "iframe,magicline";
	//config.allowedContent = true;
	
	//config.urlFileManager = "/admin/filemanager"
	//config.urlFiles = "/admin/filemanager";
};
