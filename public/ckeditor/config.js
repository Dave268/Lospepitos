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
		{ name: 'insert' },
		//{ name: 'forms' },
		{ name: 'tools' },
		{ name: 'document',	   groups: [ 'mode'] },
		{ name: 'others' },
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
		{ name: 'styles' },
		{ name: 'colors' },
		//{ name: 'about' }
	];

	config.image2_captionedClass = 'img-fluid';
	
	config.extraPlugins = "bootstrapVisibility, pastetext, ckawesome, imageresponsive, lineutils, btgrid";
	config.allowedContent = true;
	
	config.contentsCss = 'https://fonts.googleapis.com/css?family=Andada|Buenard|Cedarville+Cursive|Cookie|Cormorant+Garamond|Dancing+Script|EB+Garamond|Indie+Flower|Montserrat|Old+Standard+TT|Pacifico&display=swap';
    config.font_names = "'Montserrat';'Cedarville Cursive';'Indie Flower';'Pacifico';'Dancing Script';'EB Garamond';'Old Standard TT';'Cormorant Garamond';'Cookie';'Buenard';'Andada';";
    config.font_defaultLabel = 'Montserrat';
    config.fontSize_defaultLabel = '12px';

};
