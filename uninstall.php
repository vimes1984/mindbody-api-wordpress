<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   mind-body
 * @author    C.J.Churchill <churchill.c.j@gmail.com>
 * @license   GPL-2.0+
 * @link      http://accruesupport.com
 * @copyright 4-29-2014 Accrue
 */

// If uninstall, not called from WordPress, then exit
if (!defined("WP_UNINSTALL_PLUGIN")) {
	exit;
}

// TODO: Define uninstall functionality here