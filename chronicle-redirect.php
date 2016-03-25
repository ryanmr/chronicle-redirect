<?php
/**
 * Plugin Name:			Chronicle Redirect
 * Description:			Captures 404's, checks to see if an old WordPress installation has valid slugs and redirects if so.
 * Version:				0.1.0
 * Author:				Ryan Rampersad
 * Author URI:			http://ryanrampersad.com
 */

if (!defined('ABSPATH')) {
	exit();
}

define('CR_CORE', __FILE__);
define('CR_CORE_PATH', plugin_dir_path( __FILE__ ));
define('CR_CORE_URL', plugin_dir_url( __FILE__ ));
define('CR_CORE_VIEWS', CR_CORE_PATH . 'views/');

require_once('chronicle-redirect/Singleton.php');
require_once('chronicle-redirect/Core.php');

\ChronicleRedirect\Core::get_instance()->initialize();
