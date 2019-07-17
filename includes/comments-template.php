<?php
/* This replaces the theme's comment template with the Parler div needed */
if ( get_option( 'parler_default_location' ) ) {
    // Check first if we have overrides before echoing anything out
    $pp_width = get_option( 'parler_custom_width' );
    $pp_margin = get_option( 'parler_custom_margin' );
	$pp_padding = get_option( 'parler_custom_padding' );
	
	// Just needed to add a div with id="comments" to template so react can inject the comments.
    echo '<div id="comments"><div id="parler-comments-container" style=" ';
    if ( get_option( 'parler_advanced_settings_toggler' ) ) {
		if ($pp_width) {
			echo 'max-width: ' . $pp_width . '; ';
		}
		if ($pp_margin) {
			echo 'margin: ' . $pp_margin . '; ';
		}
		if ($pp_padding) {
			echo 'padding: ' . $pp_padding . ';';
		}
	}
	echo '"></div></div>';
}