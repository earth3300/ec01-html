# EC01 HTML

Contributors: cbos
Tags: lightweight
Requires at least: 4.9
Tested up to: 4.9.6
Stable tag: 4.9
Requires PHP: 5.2.4
License: GPL-3.0+
License URI: https://www.gnu.org/licenses/gpl-3.0.en.html

Theme URI: http://wp.cbos.ca/themes/ec01-html/
Version: 2018.10.25
Author: Clarence Bos
Author URI: http://www.tnoep.ca/
Text Domain: ec01-html

A lightweight alternative to displaying HTML. Can be used as a WordPress theme
or on its own.

## Description

1. Upload the theme files to the `/{wp-content}/themes/ec01-html` directory.
2. Activate the theme through the 'Themes' screen in WordPress.

## Frequently Asked Questions

1. How does it work?

This theme uses a class that generates the HTML. It uses as set of generic
configuration files constants, that all begin with the prefix SITE_.
This provides the context for these files to work. Basic defaults are in
the `index.php` file. If present, the more extensive set of constants in
`/c/config/` are used.

## Background

A complex system can break. This provides a backup for that event. In addition,
this simpler system can be used to start a site, before a more complex framework
is needed. This simpler framework is built to work with WordPress. There are other
frameworks that may be more suitable for your needs. One of them is Concrete5 for
a system that could be viewed as a peer of WordPress. Others are MediaWiki
(which powers Wikipedia), Feng Office (for Projects), a Forum (such as phpBB) or
a Store (which could be WordPress optimized as a store).

WordPress is convenient because it is easy to use (relatively speaking) and because
it has a large user base. Its plugin architecture makes it easy to add functionality
and there are a lot of plugins available. However, due to its historical nature, it is
not yet fully implementing Object Orienting Design. It also appears to be slow in
accepting PHP 7. In many cases, using WordPress may be acceptable. However, depending on
your needs, how you anticipate your site will grow and your need for security, other options
may be better. At the time of this writing, the author can only recommend Concrete5 as
a strong option.

Regardless, the intent of this simple framework has been to move in the direction of a
platform agnosticism. As _all_ web frameworks MUST deliver content in HTML format,
it doesn't really matter which framework one uses, but it DOES matter how the clean is the
HTML that is delivered and if it validates without errors. Any WordPress theme and set of
plugins can do this, but it depends on how they are coded. In addition, wp_head() adds a
lot of (in the author's opinion) unnecessary script. Thus care must be taken to ensure
that the final product that is delivered is to the quality expected. The only way this
can be achieved is to have some familiarity with what clean, minimalist HTML looks like.
This simple framework attempts to do that.

## Screenshots

1. None.

## Changelog

None.
