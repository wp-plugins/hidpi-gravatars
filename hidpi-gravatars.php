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
 * @version: 1.1 (in development)
 * @copyright Copyright © 2012 by Robert Chapin
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
add_action('admin_enqueue_scripts', 'miqro_hidpi_gravatars', 10, 0);


/* Plugin Functions */

/**
 * Inserts the javascript link for this plugin when appropriate.
 *
 * @since 1.0
 */
function miqro_hidpi_gravatars() {
    if (is_admin()) {

        add_action('admin_footer', 'miqro_hidpi_gravatars_admin', 1001, 0); // Priority must be > 1000, see wp-includes/admin-bar.php

    } elseif (is_singular() or function_exists('is_admin_bar_showing') and is_admin_bar_showing()) {

        add_action('wp_footer', 'miqro_hidpi_gravatars_admin', 1001, 0);

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
    $src = plugins_url('hidpi-gravatars.js', __FILE__) . '?ver=1.1';
    echo "<script type='text/javascript' src='$src'></script>\n";
}
?>
