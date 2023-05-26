// Enable local "abbr" plugin from /myplugins/abbr/ folder.
CKEDITOR.plugins.addExternal( 'abbr', '/myplugins/abbr/', 'plugin.js' );

// extraPlugins needs to be set too.
CKEDITOR.replace( 'editor1', {
        extraPlugins: 'abbr'
} );
// Enable "moonocolor" skin from the /myskins/moonocolor/ folder.
CKEDITOR.replace( 'editor1', {
    skin: 'moonocolor,/myskins/moonocolor/'
} );