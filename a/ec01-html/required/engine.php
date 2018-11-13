<?php
/**
 * EC01 Engine (Earth3300\EC01)
 *
 * This file constructs the page.
 *
 * File: engine.php
 * Created: 2018-10-01
 * Update: 2018-11-08
 * Time: 19:37 EST
 */

namespace Earth3300\EC01;

/** No direct access (NDA). */
defined('NDA') || exit('NDA');

/**
 * The EC01 HTML Engine.
 *
 * This file contains the entire codeset and logic to construct the page. The
 * page is actually constructed using the `template.php` file.
 */
class EC01HTML
{
	/**
	 * Get the HTML Page.
	 *
	 * @return string
	 */
	public function get()
	{
		$page = $this->getPage();
		$template = new EC01Template();
		$html = $template->getHtml( $page );
		return $html;
	}

	/**
	 * Get the page.
	 *
	 * This function takes no arguments. All of the work is done, starting from
	 * this function. Ths includes checking the URI, getting the page slug (which
	 * can include multiple directories. It also needs to include the name of
	 * the directory in which the file is found. This can then be used to check for
	 * a file of the same name as the containing direcory, in case the default
	 * name for the file (article.html) is not found. It returns the entire page
	 * as an array, which can then be translated into HTML by the template file.
	 *
	 * @param none
	 *
	 * @return array
	 */
	private function getPage()
	{
		$page = $this->getUri();
		$page['slug'] = $this->getPageSlug( $page );
		$page['dir'] = $this->getPageDir( $page );
		$page['file'] = $this->getArticlePathandFileName( $page );
		if( $page['file']['page'] )
		{
			$page['page'] = $this->getPageFile( $page );
			$page['article'] = 'Not available.';
		}
		else
		{
			$page['page']= false;
			$page['article'] = $this->getArticleFile( $page );
		}
		$page['tiers'] = $this->getPageData( $page ); //needs the article, to get the class.
		$page['class'] = $this->getPageClasses( $page );
		$page['header']['main'] = $this->getHeader( $page );
		$page['article-title'] = $this->getArticleTitle( $page['article'] );
		$page['page-title'] = $this-> getPageTitle( $page );
		$page['aside']= $this->getAside( $page );
		$page['footer']= $this-> getFooter();

		return $page;
	}

	/**
	 * Get the filtered URI, ensuring it is safe, without the query string.
	 *
	 * Available: REQUEST_URI, QUERY_STRING and parse_url(); Some work needs to
	 * be done here to be absolutely sure that the URI is safe.
	 *
	 * @return boolean|string
	 */
	private function getUri()
	{
		$uri = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
		$uri = substr( $uri, 0, 65 );

		if ( empty ( $uri ) || $uri == '/' )
		{
			$page['front-page'] = true;
			$page['uri'] = '';
		}
		else
		{
			$page['front-page'] = false;
			$page['uri'] = $uri;
		}
		return $page;
	}

	/**
	 * Get the page slug.
	 *
	 * The page slug is the URI, with the following slash removed.
	 *
	 * @param array $page
	 *
	 * @return string
	 */
	private function getPageSlug( $page )
	{
		$slug = rtrim( $page['uri'], '/' );
		return $slug;
	}

	/**
	 * Get the page directory.
	 *
	 * The page directory is the name of the directory containing the page.
	 * It is only one level deep (no nested directories).
	 *
	 * @param array $page
	 *
	 * @return string|bool
	 */
	private function getPageDir( $page )
	{
		$regex = '/\/([a-z]{3,25})$/';
		preg_match( $regex, $page['slug'], $match );
		if ( isset( $match[1] ) )
		{
			$dir = '/' . $match[1];
			return $dir;
		}
		else {
			return false;
		}
	}

	/**
	 * Get the Verfied Article Path and File Name.
	 *
	 * We need to do quite a bit of work here because we want to ensure that
	 * natural ways of saving a file are taken into account. Also, it is possible
	 * that the page is already saved as a complete HTML page with a DOCTYPE. If
	 * this is the case then we don't want to wrap a complete page inside a another
	 * page.
	 *
	 * @param array $page
	 *
	 * @return array
	 */
	private function getArticlePathandFileName( $page )
	{
		if ( $page['front-page'] )
		{
			if ( file_exists( SITE_PATH . SITE_ARTICLE_FILE ) )
			{
				$file['name'] = SITE_PATH . SITE_ARTICLE_FILE;
				$file['page'] = false;
			}
			elseif ( file_exists( SITE_PATH . SITE_DEFAULT_FILE ) )
			{
				$file['name'] = SITE_PATH . SITE_DEFAULT_FILE;
				$file['page'] = true;
			}
		}
		elseif ( isset( $page['slug'] ) )
		{
			if ( file_exists( SITE_HTML_PATH . $page['slug'] . SITE_ARTICLE_FILE ) )
			{
				$file['name'] = SITE_HTML_PATH . $page['slug'] . SITE_ARTICLE_FILE;
				$file['page'] = false;
			}
			elseif( file_exists( SITE_HTML_PATH . $page['slug'] . $page['dir'] . SITE_HTML_EXT ) )
			{
				$file['name'] = SITE_HTML_PATH . $page['slug'] . $page['dir'] . SITE_HTML_EXT;
				$file['page'] = false;
			}
			elseif ( file_exists( SITE_HTML_PATH . $page['slug'] . SITE_DEFAULT_FILE ) )
			{
				$file['name'] = SITE_HTML_PATH . $page['slug'] . SITE_DEFAULT_FILE;
				$file['page'] = true;
			}
			else
			{
				$file['name'] = false;
				$file['page'] = false;
			}
		}
		elseif ( file_exists( SITE_HTML_PATH . SITE_DEFAULT_FILE ) )
		{
			$file['name'] = SITE_HTML_PATH . SITE_DEFAULT_FILE;
			$file['page'] = true;
		}
		else {
				$file['name'] = false;
				$file['page'] = false;
		}

		return $file;
	}

	/**
	 * Get the Article Title
	 *
	 * @param array $page
	 *
	 * @return string
	 */
	private function getArticleTitle( $article )
	{
		$check = substr( $article, 0, 150 );
		$pattern = "/>(.*?)<\/h1>/";
		preg_match( $pattern, $check, $matches );
		if ( ! empty ( $matches[1] ) )
		{
			return ( $matches[1] );
		}
		else {
			return false;
		}
	}

	/**
	 * Get the Page Data (including the Classes) to Use in Construction the Page.
	 *
	 * There is a nod to more complex page functionality here that isn't included
	 * in the basic default version. Don't worry about this if using the basic
	 * version as the first section of this function isn't used if the files
	 * (tier.php and data.php) are not available.
	 *
	 * @param array $page
	 *
	 * @return array
	 */
	private function getPageData( $page )
	{
		if ( SITE_USE_TIERS )
		{
			$tiers = new EC01Tiers();

			/** Get the tiers data ('tiers') */
			$data = $tiers->getTiersData( $page );
			return $data;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Get the HTML Class.
	 *
	 */
	 private function getPageClasses( $page )
	 {
		$class['type'] = $this->isPageDynamic( $page );

 		$class['article'] = $this->getArticleClass( $page['article'] );

 		$class['html'] = $this->getHtmlClass( $page, $class['type'] );

		$class['body'] = $this->getBodyClass( $page['tiers'] );

		return $class;
	 }

	/**
	 * Build the HTML Class String From the Array.
	 *
	 * Do any other necessary processing.
	 *
	 * @param string $type
	 * @param array $tiers
	 *
	 * @return string
	 */
	private function getHtmlClass( $page, $type )
	{

		$tiers = $page['tiers'];

		/** Type of page (fixed-width or dynamic), with a trailing space. */
		$str = $type . ' ';

		/** Add an 'aside' class, but not on the front page or on Tier 1 pages. */
    if (
      SITE_USE_ASIDE && ! $page['front-page']
      && $page['tiers']['tier-1']['get']
      && $page['tiers']['tier-2']['get']
    )
    {
	    $str .= 'aside ';
    }
    else
    {
      $str .=  '';
    }

		if ( ! empty( $tiers ) )
		{
			/** Exclude these tiers in the html level element */
			$exclude = [ 'tier-2', 'tier-3' ];

			foreach ( $tiers as $tier )
			{

				if ( ! empty( $tier['tier'] ) && ! in_array( $tier['tier'], $exclude ) )
				{
					$str .= $tier['class'] . ' ';
				}
			}

			/** Remove the trailing space. */
			$str = trim( $str );

			/** Need a trailing space, but not a leading space. */
			$class = sprintf( 'class="%s" ', $str );

			/** Return the class. */
			return $class;
		}
		else
		{
			return null;
		}
	}

	/**
	 * Build the Body Class String.
	 *
	 * Do any other necessary processing.
	 *
	 * @param array $tiers
	 *
	 * @return string|bool
	 */
	private function getBodyClass( $tiers )
	{
		/** Nothing here (yet). */
		return null;
	}

	/**
	 * Get the class from the article element
	 *
	 * @param string $article
	 *
	 * @return string
	 */
	private function getArticleClass( $article )
	{
		$check = substr( $article, 0, 150 );
		$pattern = "/<article class=\"(.*?)\"/";
		preg_match( $pattern, $check, $matches );

		if ( ! empty ( $matches[1] ) )
		{
			return ( $matches[1] );
		}
		else
		{
			return false;
		}
	}

	/**
	 * Whether or not the Page is Dynamic or Fixed Width.
	 *
	 *
	 *
	 * @param array
	 *
	 * @return string
	 */
	private function isPageDynamic( $page )
	{
		/** The class for a fixed width page. */
		$fixed_width = 'fixed-width';

		/** The class for a dynamic (responsive) page. */
		$dynamic = 'dynamic';

		if ( SITE_IS_FIXED_WIDTH )
		{
			return $fixed_width;
		}
		elseif( $page['front-page'] )
		{
			return $fixed_width;
		}
		else
		{
			return $dynamic;
		}
	}

	/**
	 * Get the Page and Site Title.
	 *
	 * @param array $page
	 *
	 * @return string
	 */
	private function getPageTitle( $page )
	{
		$str = "Site Title N/A";

		if ( defined( 'SITE_TITLE' ) )
		{
			if( $page['front-page'] )
			{
				$description = str_replace( '<br />', ' ', SITE_DESCRIPTION );
				$str = sprintf( '%s%s%s', SITE_TITLE, ' | ', $description );
				return $str;
			}
			else if ( ! empty ( $page['article-title'] ) )
			{
				$str = sprintf( '%s%s%s', $page['article-title'], ' | ', SITE_TITLE );
				return $str;
			}
			else
			{
				return SITE_TITLE;
			}
		}
		else
		{
			return $str;
		}
	}

	/**
	 * 	Get the Header
	 *
	 * 	The SITE_TITLE and SITE_DESCRIPTION constants are used here.
	 *
	 * 	@param array $page
	 *
	 * 	@return string
	 */
	private function getHeader( $page )
	{
		$str = '<header class="site-header">' . PHP_EOL;
		$str .= '<div class="inner">' . PHP_EOL;

		/** The front page link needs to wrap around the logo and title, but nothing else. */
		$str .= sprintf( '<a href="/" class="color" title="%s">%s', SITE_TITLE, PHP_EOL);

			$str .= '<div class="site-logo">' . PHP_EOL;
			$str .= '<div class="inner">' . PHP_EOL;

      /** The site logo is hard coded to 75px by 75px. */
			$str .= '<img src="/0/theme/image/site-logo-75x75.png" alt="Site Logo" width="75" height="75" />' . PHP_EOL;

      $str .= '</div><!-- .inner -->' . PHP_EOL;
			$str .= '</div><!-- .site-logo -->' . PHP_EOL;

			/** The title wrap includes the title and description, but nothing else. */
			$str .= '<div class="title-wrap">' . PHP_EOL;
			$str .= '<div class="inner">' . PHP_EOL;

      /** The site title and descriptions are constants set in /c/config/ or the index file of this package. */
			$str .= sprintf( '<div class="site-title">%s</div>%s', SITE_TITLE, PHP_EOL );
			$str .= sprintf( '<div class="site-description">%s</div>%s', SITE_DESCRIPTION, PHP_EOL );

      $str .= '</div><!-- .inner -->' . PHP_EOL;
			$str .= '</div><!-- .title-wrap -->' . PHP_EOL;

    /** Close the front page link. */
		$str .= '</a><!-- .front-page-link -->' . PHP_EOL;

		/** The sub header needs to be self closing. Turn it on or off using the constant below. */
		$str .= SITE_USE_HEADER_SUB ? $this->getHeaderSub( $page ) : '';

		/** Close the inner wrap and the header element. */
		$str .= '</div><!-- .inner -->' . PHP_EOL;
		$str .= '</header>' . PHP_EOL;

		return $str;
	}

	/**
	 * Get the Sub Header.
	 *
	 * This is being used here to construct a rather complex three (or four) part
	 * header. This is so that it can be used to provide better visual cues
	 * as to where one is on the site. Color, blocking and icons are all used for
	 * maximum effect. If necessary, this can be replaced as needed. Note where the
	 * sub header is being placed with respect to the containing header and style
	 * accordingly. Since this is using the tiers concept, we can do another check
	 * for the file and then call it only if there. Checking if the class file_exist
	 * may not work.
	 *
	 * @param array $page
	 *
	 * @return string|bool
	 */
	 private function getHeaderSub( $page )
	 {
      /** The sub header is constructed in the `tiers.php` file. */
      $tiers = new EC01Tiers();
      $str = $tiers->getHeaderSubTiered( $page );
      return $str;
	 }

	 /**
		* Get the Aside
		*
		* The "aside" is also referred to as the "sidebar".
		*
		* @param array $page
		*
		* @return string|bool
		*/
		private function getAside( $page )
		{
				$str = '';

				if (
          SITE_USE_ASIDE && ! $page['front-page']
          && $page['tiers']['tier-1']['get']
          && $page['tiers']['tier-2']['get']
          )
				{
					$str .= $this->getAsideFile( $page );

					return $str;
				}
				else
				{
					return false;
				}
		}

	/**
	 * Get the Footer
	 *
	 * Adds the Site Title and Copyright information based on SITE_TITLE,
	 * SITE_YEAR_TO_NOW and SITE_TITLE.
	 *
	 * @return string
	 */
	private function getFooter()
	{
		$str = '<footer class="nav">' . PHP_EOL;
		$str .= '<nav class="align-center">' . PHP_EOL;
		$str .= '<a href="../../../../" class="icon-up-4" title="Up 4 Directories">^4</a>&nbsp;&nbsp;' . PHP_EOL;
		$str .= '<a href="../../../" class="icon-up-3" title="Up 3 Directories">^3</a>&nbsp;&nbsp;' . PHP_EOL;
		$str .= '<a href="../../" class="icon-up-2" title="Up 2 Directories">^2</a>&nbsp;&nbsp;' . PHP_EOL;
		$str .= '<a href="../" class="icon-up-1" title="Up 1 Directory">^1</a>' . PHP_EOL;
		$str .= '</nav>' . PHP_EOL;
		$str .= '</footer>' . PHP_EOL;
		$str .= '<footer class="site-footer">' . PHP_EOL;
		$str .= '<div class="inner">' . PHP_EOL;
		/** SITE_YEAR_TO_NOW is empty string if same as SITE_YEAR_START, else '&ndash' . date('Y'); */
		if ( SITE_USE_BASIC )
		{
			$str .= sprintf( '<span class="copyright">Copyright &copy; %s %s</span>', date('Y'), SITE_TITLE );
		}
		else
		{
			$str .= sprintf( '<span class="copyright">Copyright &copy; %s%s %s</span>', SITE_YEAR_START, SITE_YEAR_TO_NOW, SITE_TITLE );
		}
		$str .= '<nav class="hide">' . PHP_EOL;
		$str .= '<ul class="horizontal-menu">' . PHP_EOL;
		$str .= '<li><a href="/page/privacy/">Privacy</a></li>' . PHP_EOL;
		$str .= '<li><a href="/page/terms/">Terms</a></li>' . PHP_EOL;
		$str .= '</ul>' . PHP_EOL;
		$str .= '</nav>' . PHP_EOL;
		$str .= '</div>' . PHP_EOL;
		$str .= '</footer>' . PHP_EOL;

		return $str;
	}

	/**
	 * Sanitize HTML
	 *
	 * Not currently used (2018.09.0)
	 * Remove everything but valid HTML
	 *
	 * @todo Needs some work
	 */
	private function sanitizeHtml( $str = '' )
	{
		if ( ! empty( $str ) )
		{
			$allowed = '<section><article><header><div><img><a><p><h1><h2><h3><h4><h5><h6><ol><li>';
			$stripped = strip_tags( $str, $allowed );
			return $stripped;
		} else
		{
			return false;
		}
	}

	//**** GET HEADER, MENU, ARTICLE, SIDEBAR, FOOTER AND PAGE FILES *****/

	/**
	 * Get the Header File.
	 *
	 * This can be used if this template part is static and rarely changes.
	 * It just retrieves the template part saved to disk as an HTML file and
	 * may be faster than if constructing the template part dynamically for every
	 * page load.
	 *
	 * @param array $page
	 *
	 * @return string
	 */
	private function getHeaderFile( $page )
	{
		$str = 'Header N/A';

		$file = SITE_HEADER_PATH . SITE_HEADER_DIR . SITE_HTML_EXT;

		if ( file_exists( $file ) )
		{
			$str = file_get_contents( $file );
			return $str;
		}
		else
		{
			return $str;
		}
	}

	/**
	 * Get the Menu File
	 *
	 * This can be used if this template part is static and rarely changes.
	 * It just retrieves the template part saved to disk as an HTML file and
	 * may be faster than if constructing the template part dynamically for every
	 * page load.
	 *
	 * @param array $page
	 *
	 * @return string
	 */
	private function getMenuFile()
	{
		$str = 'Menu N/A';
		$file = SITE_MENU_PATH . SITE_MENU_DIR . SITE_HTML_EXT;
		if ( file_exists( $file ) )
		{
			$str = file_get_contents( $file );
			return $str;
		}
		else
		{
			return $str;
		}
	}

	/**
	 * Get the Article File.
	 *
	 * This can be used if this template part is static and rarely changes.
	 * It just retrieves the template part saved to disk as an HTML file and
	 * may be faster than if constructing the template part dynamically for every
	 * page load. Also performs a basic check on the file name length and the file
	 * itself, to make sure nothing squirrely is happening here and that the content
	 * is not trivial.
	 *
	 * @param array $page
	 *
	 * @return string|bool
	 */
	private function getArticleFile( $page )
	{
		$str = '<article>Article N/A.</article>';

		$file = $page['file']['name'];
		if ( ! empty( $file ) && strlen ( $file ) < 180 )
		{
			if ( file_exists( $file ) )
			{
				$str = file_get_contents( $file );
			}
		}
		return $str;
	}

	/**
	 * Get the Sidebar File.
	 *
	 * This can be used if this template part is static and rarely changes.
	 * It just retrieves the template part saved to disk as an HTML file and
	 * may be faster than if constructing the template part dynamically for every
	 * page load.
	 *
	 * @param array $page
	 *
	 * @return string
	 */
	private function getAsideFile( $page )
	{
		$str = 'Sidebar N/A';
		$file = SITE_SIDEBAR_PATH . SITE_SIDEBAR_DIR . SITE_HTML_EXT;

		if ( file_exists( $file ) )
		{
			$str = file_get_contents( $file );
			return $str;
		}
		else
		{
			return $str;
		}
	}

	/**
	 * Get the Footer File
	 *
	 * This can be used if this template part is static and rarely changes.
	 * It just retrieves the template part saved to disk as an HTML file and
	 * may be faster than if constructing the template part dynamically for every
	 * page load.
	 *
	 * @return string
	 */
	private function getFooterFile( $page )
	{
		$str = 'Footer N/A';
		$file = SITE_FOOTER_PATH . SITE_FOOTER_DIR . SITE_HTML_EXT;

		if ( file_exists( $file ) )
		{
			$str = file_get_contents( $file );
			return $str;
		} else
		{
			return $str;
		}
	}

	/**
	 * Get the Page File.
	 *
	 * The entire page exists. We are just being nice and delivering it as is.
	 * A basic check on the file length is done. Otherwise, nothing much here.
	 *
	 * @param array $page
	 *
	 * @return string
	 */
	private function getPageFile( $page )
	{
		$str = "This page doesn't exist.";
		$file = $page['file']['name'];
		if ( strlen ( $file ) < 120 )
		{
			$str = file_get_contents( $file );
			return $str;
		}
		else
		{
			return $str;
		}
	}
} //end class

function pre_dump( $arr )
{
	if ( 1 ) {
		echo "<pre>" . PHP_EOL;
		var_dump( $arr );
		echo "</pre>" . PHP_EOL;
	}
}
