/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
//	 config.language = 'fr';
//	 config.uiColor = '#AADC6E';
	/*
	config.toolbar = 
		[
			[ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ],
			[ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ],
			[ 'Link','Unlink' ],
			[ 'Image','Table','HorizontalRule','SpecialChar','PageBreak' ],
			[ 'Styles','Format','Font','FontSize' ],
			[ 'TextColor','BGColor' ],
			[ 'Maximize', 'ShowBlocks','-','About' ]
		];
		*/
	config.font_names = "宋体" + config.font_names;
};
CKEDITOR.plugins.add( 'abbr',
		{
			init: function( editor )
			{
				// Plugin logic goes here...
			}
		} );

