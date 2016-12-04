# Linus - A MediaWiki Skin based on Bootstrap

*Linus* is a MediaWiki skin based on [Bootstrap 3](http://www.getbootstrap.com). It is a complete rewrite of my previous [Bootstrap3 Mediawiki](https://github.com/jneug/bootstrap3-mediawiki) skin, which in turn was an adaption of the [Bootstrap Mediawiki](http://github.com/borkweb/bootstrap-mediawiki) skin by [Matthew Batchelder](http://borkweb.com).

This version aims to comply to the Bootstrap best practices as much as possible.

The skin uses *Bootstrap 3.3.4* and *FontAwesome 4.3.0*.

## Installation
First, clone the repository into your `skins/` directory.

```Bash
git clone https://github.com/jneug/Linus.git
```

Next, in `LocalSettings.php` set:

```PHP
$wgDefaultSkin = 'Linus';
```

Then add at the bottom:

```PHP
require_once( "$IP/skins/Linus/Linus.php" );
```

## Customization
While the overall layout is fixed, there are many ways to customize the look and feel of your wiki.

### Settings
*Linus* comes with a bunch of settings, that can be set in your `LocalSettings.php` (usually after you requierd the skin file),

* **$wgLinusUseSidebar**

	Set `$wgLinusUseSidebar = true;` to use [`MediaWiki:Sidebar`](https://www.mediawiki.org/wiki/Manual:Interface/Sidebar). *Linus* adds some additional features to the styling of the sidebar that you can read about [further down](#sidebar).

* **$wgLinusTOCInSidebar**

	Set `$wgLinusTOCInSidebar = true;` to move the table of contents to the sidebar. On pages without a toc the content will take the complete area. This will overwrite any placement with `__TOC__`.
	If this option is enabled, the toc will scroll with the page via the [Bootstrap Affix plugin](http://getbootstrap.com/javascript/#affix).

* **$wgLinusEnableSmoothScroll**

	Enabled by default, the option will smoothly scroll to the targeted anchor of any toc link. Use `$wgLinusEnableSmoothScroll = false;` if this is not desired or you experience any issues with this feature.

* **$wgLinusNavbarInverted**

	By default *Linus* uses the default, fixed to top version of the [Bootstrap navbar](http://getbootstrap.com/components/#navbar-fixed-top). To use the [inverted colors](http://getbootstrap.com/components/#navbar-inverted) set `$wgLinusNavbarInverted = true;`.

* **$wgLinusNavbarFixed**

	By default *Linus* uses the default, fixed to top version of the Bootstrap navbar. To use the [non-fixed variant](http://getbootstrap.com/components/#navbar-default) set `$wgLinusNavbarFixed = false;`.

* **$wgSitenameshort**

	If you want a shorter title to appear in your navbar, you can set `$wgSitenameshort = 'Short Name';`.

* **$wgLinusUseFontAwesome**

	Set `$wgLinusUseFontAwesome = true;` to add [FontAwesome 4](http://fontawesome.io) icons to your wiki. To improve performance this option is disabled by default.

* **$wgLinusShowFooterLinks**

	Set `$wgLinusShowFooterLinks = true;` to show MediaWiki footer links.

* **$wgLinusUseFooterIcons**

	Set `$wgLinusUseFooterIcons = false;` to use plain tetx links instead of icons for the "powered by" links in the footer.

* **$wgLinusHideActionsForAnon**

	Set `$wgLinusHideActionsForAnon = false;` if you want to show page actions to guests. Note that this has no effect on userrights, but merely hides the menu items in the user interface.

* **$wgLinusResponsiveCategories**

	The category pages in *Linus* are redesigned to be responsive and lucid on mobile devices. If you prefer the default layout or use any extensions, that this feature might interfere with, set `$wgLinusResponsiveCategories = false;`

* **$wgLinusEnableNavTag**

	*Linus* comes with some new parser tags to use in your wiki. To disable the `<nav>` tag, set `$wgLinusEnableNavTag = false;`

* **$wgLinusEnableButtonsTag**

	*Linus* comes with some new parser tags to use in your wiki. To disable the `<buttons>` tag, set `$wgLinusEnableButtonsTag = false;`

Here is what an example `LocalSettings.php` might look:

```PHP
$wgDefaultSkin = 'Linus';

require_once( "$IP/skins/Linus/Linus.php" );

$wgLinusUseSidebar = false;
$wgLinusTOCInSidebar = true;
$wgLinusEnableSmoothScroll = true;
$wgLinusNavbarInverted = true;
$wgLinusNavbarFixed = true;
$wgLinusUseFontAwesome = true;
$wgLinusShowFooterLinks = false;
$wgLinusUseFooterIcons = true;
$wgLinusHideActionsForAnon = true;
$wgLinusEnableNavTag = true;
$wgLinusEnableButtonsTag = true;
$wgLinusResponsiveCategories = true;

$wgSitenameshort = 'Linus';
```

### Page sections
*Linus* allows you to customize some of the sections in the page layout. Namely the titlebar, footer, copyright information and sidebar.

#### Titlebar
The titlebar is loaded from `MediaWiki:Linus/Titlebar` and parsed into navigation elements in the navbar. The navigation will appear next to the logo/site name on the left of the navbar.

The titlebar uses [the same syntax](#tags) as the `<nav>` and `<buttons>` tags to generate links and dropdown menus. You can add as many additional menu items and dropdowns as you'd like, but note that with more than two elements on the root level, the navbar might break into two lines on smaller screens. Other elements than those allowed for the tags are not allowed.

Here is an example for a titlebar with one link and a dropdown:

	* mainpage|Main
	* Help (icon:book)
	** Help|Help (icon:book)
	** ----
	** Linus Skin
	*** MediaWiki:Linus/Titlebar|Titlebar
	*** MediaWiki:Linus/Footer|Footer
	*** MediaWiki:Linus/Copyright|Copyright

#### Footer
The footer is loaded from `MediaWiki:Linus/Footer` and can contain any markup you like. You can use most of the bootstrap styles, especially the grid classes to create a layout for your footer. See the [official documentation](http://getbootstrap.com/components/) for more details. Note that the footer is always wrapped in a `div.row`.

Here is an example for a footer with two columns of equal width, using the Bootstrap grid sytem:

	<div class="col-md-6">
		=== Stuff ===
		* [[Link to some place]]
		* [[Another link]]
	</div>
	<div class="col-md-6">
		=== More Stuff ===
		* [http://external.resource.org Go here]
	</div>

#### Copyright
The copyright information in the lower right of the page is loaded from `MediaWiki:Linus/Copyright`. It can contain the basic HTML tags allowed in normal wiki pages, but usually should just be a oneliner:

    <span class="fa fa-copyright"></span> 2015, J. Neugebauer

#### Sidebar
The sidebar is loaded from `MediaWiki:Sidebar` and parsed similar to the [titlebar](#titlebar) and the [parser tags](#tags).

### General styling
To adapt the look and feel of your wiki you can use the [usual styling possibilities of MediaWiki](https://www.mediawiki.org/wiki/Manual:Interface/Stylesheets), e.g. the `MediaWiki:Common.css` page.

To add advanced styling, you can edit the file `less/custom.less` in the `Linus` directory. It allows the use of [{less}](http://lesscss.org) syntax, but will also accept normal css. LESS allows you to reuse the colors and other variables from bootstrap to fit your wiki to the global styling bootstrap supplies.

Additionally custom javascript can be places in `MediaWiki:Common.js` and `js/custom.js`.

### Bootstrap themes
The overall look and feel of Bootstrap can be modified by using Bootstrap themes.  To use a theme for your wiki copy the content of the themes css file (e.g. `bootstrap-theme.css`) into your `custom.less`.

### Custom Bootstrap build
To make your wiki unique, you might want to replace Bootstrap with a [custom build](http://getbootstrap.com/css/#less-bootstrap) version. There are many sites like [Bootswatch](http://bootswatch.com) that provide alternatives to the default Bootstrap design. Or you can use the [Boostrap customization tool](http://getbootstrap.com/customize/).

To use a custom build, just replace the `css/bootstrap.min.css` with the new file. Please make sure the file was generated with Bootstrap version 3.3.4. Additionally you should replace the file `less/variables.less` with the one used to generate the custom build. Most Bootstrap resources provide this file with their downloads (at least Bootswatch does). This makes sure the custom styles applied to MediaWiki elements are also adapted to your new look and feel.

*Linus* was tested with the Bootswatch themes and should work fine with any of them. If you experience problems with your custom build, please give me a note.

Note that this replacement should work fine for any Bootstrap version that was generated by customizing the [`variables.less`](http://getbootstrap.com/css/#less-variables) file and using the usual build process. For versions with major changes to how Bootstrap generates its styles, the layout might break.

## Usage
To utilize *Linus* to its fullest, you can use most of the Bootstrap components when writing wiki pages. Additionally the skin comes with two parser tags to generate buttons and navigation elements.

For future releases, additional integrations with some extensions (e.g. Semantic MediaWiki) are planed.

### Tags
*Linus* adds two tags: `<buttons>` and `<nav>`. Both take the same arguments, but produce slightly different output. The first tag will generate [button groups](http://getbootstrap.com/components/#btn-groups), the other [navs](http://getbootstrap.com/components/#nav).

Both tags take a string as argument, that has to be formatted with a [special syntax](#menu-syntax), similar to [the way the Vector skin expects the sidebar to be defined](http://www.mediawiki.org/wiki/Manual:Interface/Sidebar#Customize_the_sidebar), but with some additions and changes to the syntax.

#### Menu syntax


	* mainpage|Main
	* Help (icon:book)
	** Help|Help (icon:book)
	** ----
	** Linus Skin
	*** MediaWiki:Linus/Titlebar|Titlebar
	*** MediaWiki:Linus/Footer|Footer
	*** MediaWiki:Linus/Copyright|Copyright

### Icons
In addition to Bootstraps [Glyphicons]() *Linus* comes bundled with [FontAwesome 4](). The skin has a large list build in, that maps icons to menu items. If FontAwesome is enabled, the skin automatically uses its icons for the build in MediaWiki menus. Otherwise Glyphicons are used.

Both can be used in the `<buttons>` and `<nav>` tags, as well as in the title- and sidebar. To do so, you only need to provide the name of the icon for Glyphicons (e.g. `lock` instead of `glyphicon glyphicon-lock`) and the full class name for FontAwesome (e.g. `fa-lock`) in the menu syntax. Additionally you can add additional css classes to style the icon, e.g. `fa-spin` to get a rotating icon.
The icons for the build in MediaWiki menus are read from [system messages](), this allows you to change, disable and add icons.

#### Change and adding menu icons
To change an icon, you just have to create a new system message with the `Icon:` prefix and the ID of the menu item as a name. The ID can be determined by looking up the `id` attribute of an menu item and removing the prefix.

For example the edit menu item has the `id` attribute `c-edit` removing The prefix leaves `edit` and the name of the system message page would be `MediaWiki:Icon:edit`. Often times the ID is the same as the system message key to translate the menu item label. Switching the language to  `qqx` will replace all messages with their key, making it easy to find the appropriate ID. You can temporarily switch the language by appending `?uselang=qqx` to the page URL.

After finding the required ID you can change the icon by creating the system message page and entering the icon as its content. E.g. setting `MediaWiki:Icon:edit` to `lock` will display a lock icon instead of the usual pencil. Setting the page content to `fa-lock fa-spin` will replace it with a spinning FontAwesome lock.

This way new icons get added as well. For example to display an icon next to your custom Namespace `Tool` in the "Page" menu, add the system message `MediaWiki:Icon:nstab-tool` to your wiki.

#### Disabling icons
Icons can also be disabled by either setting the system message to an empty page or to `-`. The first will completely remove the icon and thus might result in uneven indentions of menu items with and without icons. The second version will prevent this by adding an empty icon, that serves as a spacer to keep the indention levels the same.

### Components
Bootstrap comes with a variety of [components](), that can be added to your wiki pages. Most of them work with *Linus*. Since most of them work by adding css classes to `<div>` tags, they can easily be integrated into MediaWiki.

Have a look at the official Bootstrap docs and the [templates section](#templates) for more details.

In addition to the usual components, *Linus* adds a few new ones specifically designed to work with MediaWiki.

#### Infobox component
Many wikis use info boxes to summarize information in a standardized form. *Linus* comes with a mobile friendly infobox component that is adapted to your bootstrap theme of choice.

To use a info box, simply wrap a normal Bootstrap table inside a `<div>` with the `infobox` class:

```
<div class="infobox">
{| class="table table-bordered"
|+ Caption
|-
! colspan="2" class="infobox-header" | Zettelkasten
|-
| colspan="2" | [[File:Image.png|frameless]]
|-
! information
| Value
|-
|colspan="2" class="infobox-muted" | A muted text for secondary information.

Besides <code>.infobox-muted</code> you can use any of the usual Bootstrap table classes.
|-
! Info state
| class="info" | Info text
|-
! Warning state
| class="warning" | Warning text
|}
</div>
```

#### Long URLs

#### Multi-level dropdowns

## Extras

### Templates

### Docs


----

Every list item on the first level will be displayed as a menu item in the navbar. List items on the second level will be displayed in a dropdown under their parent element.
Aside from links you can add dividers to the dropdowns by adding `** ----` and subheading as normal text items, e.g. `** Some text`. Additionally each menu item may have a [Glyphicon](http://getbootstrap.com/components/#glyphicons) by adding `(icon:name)` to the end of the line. (The icon names are the last part in the class names: *.glyphicon-**name***)

## CSS hacks
You might want to use these "hacks" to change some aspects of the layout and look of components.

### General page styling

#### Mark interwiki links as external
Add the following to your `custom.less` to mirror the styling of external links
```css
a.extiw {
	&:after {
		font-family: 'Glyphicons Halflings';
		content: "\e164";
		font-size: 75%;
		padding-left: 3px;
	}
}
.fa-enabled {
	a.extiw {
		&:after {
			font-family: 'FontAwesome';
			content: " \f08e";
			font-size: 75%;
			padding-left: 0;
		}
	}
}
.footer {
	a.extiw {
		&:after {
			content: "";
			padding: 0;
		}
	}
}
```

### Infobox

#### Hide infobox on smartphones
Add this to your `custom.less`
```css
@media (max-width: @screen-sm-max) {
	.infobox {
		display: none;
	}
}
```

#### Make infobox full width on smartphones
Add this to your `custom.less`
```css
@media (max-width: @screen-sm-max) {
	.infobox {
		.table {
			width: 100%;
			max-width: none;
		}
	}
}
```
