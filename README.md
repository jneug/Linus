# Linus - A MediaWiki Skin

**Note: This readme is not up-to-date. It will be updated as soon as the skin reaches a stable version.**

This is a MediaWiki skin that uses [Bootstrap 3](http://www.getbootstrap.com) from Twitter. The skin is an adaption of the [Bootstrap Mediawiki](http://github.com/borkweb/bootstrap-mediawiki) skin by [Matthew Batchelder](http://borkweb.com).

The framework gives a boatload of features that play really nicely with a MediaWiki installation.  To get up and rolling, there's a few things that should be done.

## Changes to the original version

* Adapted skin to [Bootstrap 3.1.1](http://getbootstrap.com)
* Added Bootstrap themes from [Bootswatch](http://bootswatch.com)
* Removed Google Code Prettify (use [Extension:GoogeCodePrettify](http://www.mediawiki.org/wiki/Extension:GoogleCodePrettify) instead)
* Removed [FontAwesome](http://fortawesome.github.io/Font-Awesome/) (use [Bootstrap Glyphicons](http://getbootstrap.com/components/#glyphicons) instead)
	* Fix for Glyphicons in MediaWiki
* Added [Behave.js](http://jakiestfu.github.io/Behave.js/) support
* Added support for german wikis

## Installation
First, clone the repository into your `skins/` directory.

````shell
git clone https://github.com/jneug/bootstrap3-mediawiki.git
````

Next, in `LocalSettings.php` set:

````php
$wgDefaultSkin = 'bootstrap3mediawiki';
````

Then add at the bottom:

````php
require_once( "$IP/skins/bootstrap3-mediawiki/bootstrap3-mediawiki.php" );
````

## Setup
Once you've enabled the skin, you'll want to create a few pages.

### Skin options
You can customize the skin by setting any of these options in your `LocalSettings.php`. Note that these settings have to be made *before* you require the `bootstrap3-mediawiki.php`.

* **$wgTOCLocation**

	Use `$wgTOCLocation = 'sidebar';` to move the table of contents to the sidebar. On pages without a toc the content will take the complete area.

* **$wgBsTheme**

	Use `$wgBsTheme = 'themename'` to use one of the included Bootswatch themes.

	Set this to the theme you would like to use. Note that the darker themes do not work very well with this skin and need to be further tweaked by adding custom CSS styles. The skin was developed with the *yeti* theme in mind, so the `custom.css` contains some styles to tweak the theme for use with this skin.

	To use an other Bootstrap 3 theme, name the file `yourthemename.min.css` and place it in the `bootstrap3-mediawiki/css/bootswatch` folder. Then set `$wgBsTheme = 'yourthemename'`.

* **$wgSiteCSS**

	You can add your own custom CSS styles to the skin by setting `$wgSiteCSS = 'custom.css'` and adding the styles to `bootstrap3-mediawiki/css/custom.css`.

* **$wgSiteJS**

	You can add your own custom JavaScript to the skin by setting `$wgSiteJS = 'custom.js'` and adding the scripts to `bootstrap3-mediawiki/js/custom.js`.

* **$wgSitenameshort**

	If you want a shorter title to appear in your navbar, you can set `$wgSitenameshort = 'Short Name';`.

Here is what an example `LocalSettings.php` might look:

````php
$wgBsTheme = 'yeti';
$wgTOCLocation = 'sidebar';
$wgSitenameshort = 'MyWiki';
$wgSiteCSS = 'custom.css';
$wgSiteJS = 'custom.js';
require_once "$IP/skins/bootstrap3-mediawiki/bootstrap3-mediawiki.php";
````

### Create page: Bootstrap:Footer
This MediaWiki page will contain what appears in your footer. You can use most of the bootstrap styles. See the [official documentation](http://getbootstrap.com/components/) for more details.

Here is an example for a footer with two columns of equal with, using the Bootstrap grid sytem:

	<div class="row">
		<div class="col-md-6">
			=== Stuff ===
			* [[Link to some place]]
			* [[Another link]]
		</div>
		<div class="col-md-6">
			=== More Stuff ===
			* [http://external.resource.org Go here]
		</div>
	</div>


### Create page: Bootstrap:TitleBar
This MediaWiki page will control the links that appear in the Bootstrap navbar after the logo/site title. You can add as many additional menu items and dropdowns as you'd like.  The expected format is as follows:

	* Menu Item Title
	** [[Page 1]] (icon:user)
	** [[Page 2]]
	** ----
	** Some subheader
	** [[Page 3]]
	** [[Page 4]]
	* Another Menu (icon:search)
	** [[Whee]]
	** [[OMG hai]]
	* [[A Link]] (icon:cog)

Every list item on the first level will be displayed as a menu item in the navbar. List items on the second level will be displayed in a dropdown under their parent element.
Aside from links you can add dividers to the dropdowns by adding `** ----` and subheading as normal text items, e.g. `** Some text`. Additionally each menu item may have a [Glyphicon](http://getbootstrap.com/components/#glyphicons) by adding `(icon:name)` to the end of the line. (The icon names are the last part in the class names: *.glyphicon-**name***)

### Create page: Template:Panel
Use this template to easily generate panels on your pages.

	<div class="panel panel-{{{3|default}}}"><div class="panel-heading"><span class="panel-title">{{{1}}}</span></div><div class="panel-body">{{{2}}}</div></div>

Usage:

	{{panel|Panel Title|Panel Content|primary}}

### Create page: Template:Alert
This template is used to leverage Bootstrap's alert box:

	<div class="alert alert-{{{2}}}">{{{1}}}</div>

Usage:

	{{alert|Message you want to say|danger}}

### Create page: Template:Tip
This template is used to do Bootstrap tooltips.

	<span title="{{{2}}}" class="tip" rel="tooltip">{{{1}}}</span>

Usage:

	{{tip|Something|This is the tooltip!}}

	or

	{{tip|[[Bacon]]|Delicious snack}}

### Create page: Template:Pop
This template is used to do Bootstrap popovers.

	<span data-original-title="{{{2}}}" data-content="{{{3}}}" class="pop">{{{1}}}</span>

Usage:

	{{pop|Whatever triggers the popover|Popover Title|Popover Content}}

### Create page: Template:Jumbotron
YOu probably don't want to use too many Jumbotrons throughout your Wiki and then
this template might not be necessary. Just use the Jumbotron syntax directly on
the page.

	<div class="jumbotron">= {{{1}}} = {{{2}}}<div class="btn btn-primary btn-lg" role="button">{{{3}}}</div></div>

Usage:

	{{jumbotron|My large header|My Jumbotron text|My button label}}

### Create page: Template:Icon
This template is useful if you want to use Glyphicons on your pages.

	<span class="glyphicon glyphicon-{{{1}}}"></span>

Usage:

	{{icon|pencil}}

## Notes for further adaption

* **Glyphicons and relative paths**

	Using the MediaWiki ResourceLoader will break relative pathnames in css and javascript files. Therefore Glyphicons won't work when using Bootstrap in MediaWiki. To circumvent this, the skin overrides the `@font-face` definition of the Glyphicon font with an inline style that points to the correct skin folder. This should work in most cases, but you should be aware of this before further editing the skin.

* **Google Code Prettify**

	Google Code Prettify was removed to have one less dependency and leave the choice which syntax highlighter to use to the user. To integrate it back into your wiki have a look at [Extension:GoogeCodePrettify](http://www.mediawiki.org/wiki/Extension:GoogleCodePrettify).

* **behave.js**

	Behave.js adds tabstops for multiple lines, automatic completion for brackets and more to the default edit textarea. It was tested in conjunction with [Extension:WikiEditor](http://www.mediawiki.org/wiki/Extension:WikiEditor) and seems to work well.

## ToDo

* More consistent use of HTML 5
* Better accessibility (e.g. use 'role' attributes)
* Better responsiveness
* Better integration of SMW
* Cleanup *behavior.js* and *style.css*
* Optionally include FontAwesome for more icons
