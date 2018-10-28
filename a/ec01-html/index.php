<?php

/**
 * EC01 HTML.
 *
 * A lighweight alternative to displaying HTML. Can be used on its own, or as a WordPress theme.
 * A basic set of constants are in this index file.
 *
 * @package EC01HTML
 * @since 2018.10.25
 * @author Clarence Bos <cbos@tnoep.ca>
 * @copyright Copyright (c) 2018, Clarence Bos
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 * @link http://wp.cbos.ca/themes/ec01-html/
 *
 * @wordpress-theme
 * Theme Name: EC01 HTML
 * Theme URI: http://wp.cbos.ca/themes/ec01-html/
 * Description: A lightweight alternative to displaying HTML. Can be used on its own or as a WordPress theme.
 * Version: 2018.10.25
 * Author: Clarence Bos
 * Author URI: https://www.tnoep.ca/
 * Text Domain: ec01-html
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
	defined('NDA') || exit('No direct access.');
}

if ( ! defined( 'SITE_PATH' ) && defined('ABSPATH') )
{
	/** Set SITE_PATH to ABSPATH, if in WordPress. */
	define( 'SITE_PATH', ABSPATH );
}
elseif ( ! defined( 'SITE_PATH' ) )
{
	/** Last ditch effort. The site path is two levels up. */
	define( 'SITE_PATH', __DIR__ . '/../../' );
}
elseif( defined( 'SITE_PATH' ) )
{
	/**
	 * It should be entirely possible to run this thing without a humungous config file.
	 * That means if it isn't there, this should still load but set a basic set of constants.
	 * Lets see what they are.
	 */

	/** The following is conditional. If these conditions are not met, it won't work. */
	if ( file_exists( SITE_PATH . '/c/config/cfg-load.php' ) )
	{
		/** Load the complete config file set, and use it. */
		define( 'SITE_USE_BASIC', false );

		/** Require the configuration files. */
		require_once( SITE_PATH . '/c/config/cfg-load.php' );
	}
	else
	{
		/** Lets set some basic config here. */
		define( 'SITE_TITLE', 'Site Title' );
		define( 'SITE_DESCRIPTION', 'Site Description' );
		define( 'SITE_USE_BASIC', true );
		define( 'SITE_LANG', 'en' );
		define( 'SITE_CHARSET', 'UTF-8' );
		define( 'SITE_ELAPSED_TIME', false );
		define( 'SITE_IS_FIXED_WIDTH', false );
		define( 'SITE_USE_CSS_CHILD', false );
		define( 'SITE_HTML_EXT', '.html' );
		define( 'SITE_ARTICLE_FILE', '/article.html' );
		define( 'SITE_DEFAULT_FILE', '/default.html' );
		define( 'SITE_HTML_PATH', SITE_PATH . '/1' );
	}

	/** Require the "engine" file. This is expected to be there. */
	require_once( __DIR__ . '/includes/engine.php' );

	/**
	 * Instantiate the EC01HTML class and echo it.
	 *
	 * The class does all the rest of the work.
	 * It does not use a database. If we got this far, the class exists
	 * in the engine directory, otherwise it is *really* broken.
	 */
	$html = new EC01HTML();
	echo $html->get();
}
else
{
	/** Bail, and ask for help. */
	exit( 'The SITE_PATH needs to be set to the root directory of this site.' );
}
