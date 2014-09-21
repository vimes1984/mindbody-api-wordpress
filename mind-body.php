<?php
/**
 * Mind Body
 *
 * Mind body API integration
 *
 * @package   mind-body
 * @author    C.J.Churchill <churchill.c.j@gmail.com>
 * @license   GPL-2.0+
 * @link      http://accruemarketing.com/
 * @copyright 4-29-2014 Accrue
 *
 * @wordpress-plugin
 * Plugin Name: Mind Body
 * Plugin URI:  http://accruesupport.com
 * Description: Mind body API integration
 * Version:     1.0.0
 * Author:      C.J.Churchill
 * Author URI:  http://accruemarketing.com/
 * Text Domain: mind-body-locale
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 */

// If this file is called directly, abort.
if (!defined("WPINC")) {
	die;
}

require_once(plugin_dir_path(__FILE__) . "MindBody.php");

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook(__FILE__, array("MindBody", "activate"));
register_deactivation_hook(__FILE__, array("MindBody", "deactivate"));

//settings page

MindBody::get_instance();