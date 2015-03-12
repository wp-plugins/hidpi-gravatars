<?php
/**
 * Plugin Name: HiDPI Gravatars
 *
 * Description: Enables high resolution Gravatar images on any browser that supports them.
 *
 * This plugin does not make any permanent changes.
 *
 * Plugin URI: http://www.miqrogroove.com/pro/software/
 * Author URI: http://www.miqrogroove.com/
 *
 * @author: Robert Chapin
 * @version: 1.5
 * @copyright Copyright © 2012-2015 by Robert Chapin
 * @license GPL
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
 
/* Plugin Bootup */

if (!function_exists('get_bloginfo')) {
	header('HTTP/1.0 403 Forbidden');
	exit("Not allowed to run this file directly.");
}

add_action('wp_enqueue_scripts', 'miqro_hidpi_gravatars', 10, 0);
add_action('admin_init', 'miqro_hidpi_gravatars', 10, 0); // Ajax compatible


/* Plugin Functions */

/**
 * Hooks the needed filters and actions based on page type.
 *
 * @since 1.0
 */
function miqro_hidpi_gravatars() {
	// Collect environment info.
	$nocookie = empty($_COOKIE['miqro_hidpi']);
	if (!$nocookie and !empty($_COOKIE['miqro_srcset'])) {
		$modern = 'yes' == $_COOKIE['miqro_srcset'];
	} else {
		$modern = false;
	}
	$easy = version_compare(get_bloginfo('version'), '4.2-alpha-31722', '>=');
	$admin = is_admin();

	// Add the srcset attribute except for HiDPI admin pages sent to older browsers.
	if ( $modern or $nocookie or !$admin ) {
		if (!$easy) {
			add_filter('get_avatar', 'miqro_hidpi_gravatars_srcset', 10, 1);
		}
	} elseif ($easy) {
		// Force 2x static avatars using new filters.  Ajax compatible in old browsers.
		add_filter('pre_get_avatar_data', 'miqro_hidpi_gravatars_data', 10, 1);
	} else {
		// Fall back to old HTML filter.  Ajax compatible in old browsers.
		add_filter('get_avatar', 'miqro_hidpi_gravatars_filter', 10, 1);
	}

	// Add Javascript when needed.
	if ($admin) {
		if ($nocookie) {
			// Javascript for client detection.
			add_action('admin_footer', 'miqro_hidpi_gravatars_admin', 1001, 0); // Priority must be > 1000, see default-filters.php
		}
	} elseif (is_admin_bar_showing()) {
		// Javascript for clients that don't support srcset.
		add_action('wp_footer', 'miqro_hidpi_gravatars_admin', 1001, 0);
	} else {
		// Detect pages that have avatars.
		add_filter('get_avatar', 'miqro_hidpi_gravatars_detect', 10, 1);
	}
}

/**
 * Handles the admin bar, which is positioned after all enqueued scripts.
 *
 * @since 1.0
 */
function miqro_hidpi_gravatars_admin() {
	$src = plugins_url('hidpi-gravatars-v14.js', __FILE__) . '?ver=1.5';
	echo "<script type='text/javascript' src='$src'></script>\n";
}

/**
 * Performs sever-side avatar filtering.
 *
 * @since 1.2
 *
 * @param string $input The IMG element for one avatar.
 * @return string
 */
function miqro_hidpi_gravatars_filter($input) {
	if (FALSE === strpos($input, '.gravatar.com')) return $input;
	$delimeters = array( '?s=', '&#038;s=', '&amp;s=', '&s=' );
	foreach ($delimeters as $delim) {
		$temp = strpos($input, $delim);
		if (FALSE !== $temp) {
			$temp += strlen($delim);
			break;
		}
	}
	if (FALSE === $temp) return $input;
	$size = intval(substr($input, $temp));
	$output = substr($input, 0, $temp) . $size * 2 . substr($input, $temp + strlen($size));
	$temp = strpos($output, '%3Fs%3D');
	if (FALSE === $temp) $temp = strpos($output, '%26s%3D');
	if (FALSE !== $temp) {
		$temp += 7;
		$size = intval(substr($output, $temp));
		$output = substr($output, 0, $temp) . $size * 2 . substr($output, $temp + strlen($size));
	}
	return $output;
}

/**
 * Detect if this page contains any Gravatars.
 *
 * This strategy should be more efficient than dumbly adding Javascript to every page.
 *
 * @since 1.3
 *
 * @param string $input Optional. The IMG element for one avatar.
 * @return string
 */
function miqro_hidpi_gravatars_detect($input = '') {
	add_action('wp_footer', 'miqro_hidpi_gravatars_admin', 1001, 0);
	remove_filter('get_avatar', 'miqro_hidpi_gravatars_detect', 10, 1);
	return $input;
}

/**
 * Adds the srcset attribute to avatar IMG elements.
 *
 * @since 1.4
 *
 * @param string $input The IMG element for one avatar.
 * @return string
 */
function miqro_hidpi_gravatars_srcset($input) {
	if (FALSE === strpos($input, '.gravatar.com')) return $input;
	
	// Find original URL.
	$start = strpos($input, "src='");
	if (FALSE === $start) {
		$start = strpos($input, 'src="');
		if (FALSE === $start) {
			return $input;
		} else {
			$delim = '"';
		}
	} else {
		$delim = "'";
	}
	$start += 5;
	$end = strpos($input, $delim, $start);
	if (FALSE === $end) return $input;
	$url = substr($input, $start, $end - $start);
	
	// Generate 2x URL.
	$delimeters = array( '?s=', '&#038;s=', '&amp;s=', '&s=' );
	foreach ($delimeters as $delim) {
		$temp = strpos($url, $delim);
		if (FALSE !== $temp) {
			$temp += strlen($delim);
			break;
		}
	}
	if (FALSE === $temp) return $input;
	$size = intval(substr($url, $temp));
	$url2 = substr($url, 0, $temp) . $size * 2 . substr($url, $temp + strlen($size));
	$temp = strpos($url2, '%3Fs%3D');
	if (FALSE === $temp) $temp = strpos($url2, '%26s%3D');
	if (FALSE !== $temp) {
		$temp += 7;
		$size = intval(substr($url2, $temp));
		$url2 = substr($url2, 0, $temp) . $size * 2 . substr($url2, $temp + strlen($size));
	}

	// Insert srcset attribute.
	$srcset = " srcset='$url2 2x'";
	
	return substr($input, 0, $end + 1) . $srcset . substr($input, $end + 1);
}

/**
 * Performs sever-side avatar args filtering.
 *
 * @since 1.5
 *
 * @param array $args The inputs that were given to get_avatar_data().
 * @return array
 */
function miqro_hidpi_gravatars_data($args) {
    $args['size'] *= 2;
    return $args;
}
?>