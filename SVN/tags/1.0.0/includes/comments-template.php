<?php
/* This replaces the theme's comment template with the Parler div needed */

if (get_option( 'parler_default_location' )) {
	echo '<div id="comments" style="max-width: ' . get_option( 'parler_custom_width' ) . '"></div>';
}