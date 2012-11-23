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
 * @author: Robert Chapin (miqrogroove)
 * @version: 1.3
 * @copyright Copyright � 2012 by Robert Chapin
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
 * Inserts the javascript link for this plugin when appropriate.
 *
 * @since 1.0
 */
function miqro_hidpi_gravatars() {
    if (is_admin()) {

        if (empty($_COOKIE['miqro_hidpi'])) {
            add_action('admin_footer', 'miqro_hidpi_gravatars_admin', 1001, 0); // Priority must be > 1000, see wp-includes/admin-bar.php
        } else {
            add_filter('get_avatar', 'miqro_hidpi_gravatars_filter', 10, 1);
        }

    } elseif (function_exists('is_admin_bar_showing') and is_admin_bar_showing()) {

        add_action('wp_footer', 'miqro_hidpi_gravatars_admin', 1001, 0);

    } else {

        add_filter('get_avatar', 'miqro_hidpi_gravatars_detect', 10, 1);
        add_action('wp_footer', 'miqro_hidpi_gravatars_check', 1001, 0);

    }
}

/**
 * Handles the admin bar, which is positioned after all enqueued scripts.
 *
 * @since 1.0
 */
function miqro_hidpi_gravatars_admin() {
    // Avoid multiple inclusions.
    static $done = FALSE;
    if ($done) return;
    $done = TRUE;

    // Include the script.
    $src = plugins_url('hidpi-gravatars.js', __FILE__) . '?ver=1.3';
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
    if (FALSE === strpos($input, '.gravatar.com')) return;
    $temp = strpos($input, '&s=');
    if (FALSE === $temp) $temp = strpos($input, '?s=');
    if (FALSE === $temp) return;
    $temp += 3;
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
    remove_action('wp_footer', 'miqro_hidpi_gravatars_check', 1001, 0);
    return $input;
}

/**
 * Allow a theme or other plugin to trigger HiDPI.
 *
 * Implement like <?php define('MIQRO_HIDPI_THIS_PAGE', TRUE); ?>
 *
 * @since 1.3
 */
function miqro_hidpi_gravatars_check() {
    if (defined('MIQRO_HIDPI_THIS_PAGE')) miqro_hidpi_gravatars_admin();
}
?>
