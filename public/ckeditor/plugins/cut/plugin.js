CKEDITOR.plugins.add( 'cut',
{
	init: function( editor )
	{
		editor.addCommand( 'insertСut',
			{
				exec : function( editor )
				{    
					var cut = '<div id="cut">----------------</div>';
					editor.insertHtml(cut);
				}
			});
		editor.ui.addButton( 'Сut',
		{
			label: 'Вставить разделитель CUT',
			command: 'insertСut',
			icon: this.path + 'images/cut.png'
		} );
	}
} );