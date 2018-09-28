<?php

/**
 * FireFly HTML.
 *
 * A lighweight alternative to displaying HTML. Can be used on its own, or as a WordPress theme.
 * Requires SITE_ constants, defined in: `/c/cfg-structure.php`.
 *
 * @package FireFlyHTML
 * @since 2018.9.0
 * @author Clarence Bos <cbos@tnoep.ca>
 * @copyright Copyright (c) 2018, Clarence Bos
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 * @link http://wp.cbos.ca/themes/firefly-html/
 *
 * @wordpress-theme
 * Plugin Name: FireFly HTML
 * Plugin URI: http://wp.cbos.ca/themes/firefly-html/
 * Description: A lightweight alternative to displaying HTML. Can be used on its own or as a WordPress theme.
 * Version: 2018.9.0
 * Author: Clarence Bos
 * Author URI: https://www.tnoep.ca/
 * Text Domain: firefly-html
 * License: GPL-3.0+
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * If `wp_get_server_protocol` exists, we are in WordPress, otherwise not.
 */
if( function_exists( 'wp_get_server_protocol' ) )
{
	/** We are in WordPress, and check for direct access. */
	defined('ABSPATH') || exit('No direct access.');
}
else
{
	/** We are not in WordPress, and check for direct access. */
	defined('SITE') || exit('No direct access.');
}

if ( ! defined( 'SITE_PATH' ) && defined('ABSPATH') )
{
	/** Set SITE_PATH to ABSPATH, if in WordPress. */
	define( 'SITE_PATH', ABSPATH );
}
elseif( defined( 'SITE_PATH' ) )
{
	/** The following is conditional. If these conditions are not met, it won't work. */
	if ( file_exists( SITE_PATH . '/c/config/cfg-load.php' ) )
	{
		/** Require the configuration files. */
		require_once( SITE_PATH . '/c/config/cfg-load.php' );

		/** Require the "engine" file. This is expected to be there. */
		require_once( __DIR__ . '/includes/engine.php' );

		/**
		 * Instantiate the FireFlyHTML class and echo it.
		 *
		 * The class does all the rest of the work.
		 * It does not use a database. If we got this far, the class exists
		 * in the engine directory, otherwise it is *really* broken.
		 */
		$html = new FireFlyHTML();
		echo $html->get();
	}
	else
	{
		/** Bail, and ask for help. */
		exit( 'Please check the path to the configuration files.' );
	}
}
else
{
	/** Bail, and ask for help. */
	exit( 'The SITE_PATH needs to be set to the root directory of this site.' );
}
