# EC01 HTML

```
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
```

## Description

This theme can be used in one of two ways. First, it was developed and intended to
be used on its own as a minimal backup framework to WordPress. In its simplest format
it needs only three files, an `index.php`, and `engine.php` file and a `template.php`
file. It is kept to a bare minimum because that is really all that is needed to display
valid HTML on the web. There are many other more complex frameworks available. This isn't
one of them.

This framework can be used when developing a new idea. Often--in the beginning stages--
there is very little information, but a need to get that information out there as quickly
as possible. Rather than struggling with a framework that now exceeds 20 MB in size and
is going through constant updates, this framework requires only 23 kB for the PHP files and
19.5 kB for both parent and child stylesheets. Thus, for less than 50 kB of code (400 times
less than WordPress) a simple, robust and fast framework can be used to be able to get
up and running more quickly.

As it is built to work with WordPress, the goal is to have WordPress save posts in the same
way that this minimalist framework does, asn an `article.html` file in a directory that matches
the url given exactly. In this way, a viewer looking at `/my/awesome/idea/`, will be accessing
that file in a directory found at `/my/awesome/idea/`. In other words, it is possible to start
typing an article at `/my/awesome/idea/` in a documents folder, save it as `article.html` and
then install the framework found here, to display that article in valid HTML. This is a far cry
from having to go the other way around and install an entirely new WordPress site, just to
get "your awesome idea" out there.

In addition, it is possible for WordPress to take that article saved using a text editory
and then import it into the database. Process it and then export the entire page to `index.html`
in the same folder. In this way, the article will be cached in the appropriate spot. That means,
even if the site breaks, the article will still be there (saved as valid HTML), and can be viewed
--even if WordPress is no longer present. This, to the author, represents a leap in freedom.

1. Upload the theme files to the `/{wp-content}/themes/ec01-html` directory.
2. Activate the theme through the 'Themes' screen in WordPress.

## Terminology

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT",
"SHOULD", "SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this
document are to be interpreted as described in RFC 2119.

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
platform agnostism. As _all_ web frameworks MUST deliver content in HTML format,
it doesn't really matter which framework one uses, but it DOES matter how the clean is the
HTML that is delivered and if it validates without errors. Any WordPress theme and set of
plugins can do this, but it depends on how they are coded. In addition, wp_head() adds a
lot of (in the author's opinion) unnecessary script. Thus care must be taken to ensure
that the final product that is delivered is to the quality expected. The only way this
can be achieved is to have some familiarity with what clean, minimalist HTML looks like.
This simple framework attempts to do that.

## Stylesheet

The stylesheet for a WordPress theme MUST be included in the theme folder. This means it will
not work if it is not present. (The only other required file is the `index.php` file.) However,
the part of the stylesheet that is required is the _header_. Here is the required information,
given as an example (It is recommended to use GPLv3, not GPLv2, as given in this example):

```
/*
Theme Name*: Twenty Seventeen
Theme URI: https://wordpress.org/themes/twentyseventeen/
Author*: the WordPress team
Author URI: https://wordpress.org/
Description*: Twenty Seventeen brings your site to life with immersive featured images and subtle animations. With a focus on business sites, it features multiple sections on the front page as well as widgets, navigation and social menus, a logo, and more. Personalize its asymmetrical grid with a custom color scheme and showcase your multimedia content with post formats. Our default theme for 2017 works great in many languages, for any abilities, and on any device.
Version*: 1.0
License*: GNU General Public License v2 or later
License URI*: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain*: twentyseventeen
Tags: one-column, two-columns, right-sidebar, flexible-header, accessibility-ready, custom-colors, custom-header, custom-menu, custom-logo, editor-style, featured-images, footer-widgets, post-formats, rtl-language-support, sticky-post, theme-options, threaded-comments, translation-ready
This theme, like WordPress, is licensed under the GPL.
Use it to make something cool, have fun, and share what you've learned with others.
*/

* Indicates REQUIRED.
```

Given this header information, it is then permissible to put the remainder of the
style information elsewhere. That "elsewhere" in this context is a directory set
apart for static information. Thus all static content using this system MUST be
in this static directory. To denote the fact that this is static and not dynamic,
the integer `0` (zero) is used. All static information using this system is then
in this directory. Within the `0` directory are three default directories, `media`,
`script` and `theme`. The style information is found in the theme directory. Within
the `theme` directory are four further directories, `css`, `font`, `icon` and `image`.
The stylesheet `style.css` then goes in the `css` directory.

## Screenshots

1. None.

## Changelog

None.
