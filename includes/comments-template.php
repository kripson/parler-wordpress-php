<?php
/* Dummy comments template file.
 * This replaces the theme's comment template when comments are disabled everywhere
 */

if (get_option( 'parler_default_location' )) {
	echo '<div id="comments"></div>';
}