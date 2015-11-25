# Templates

## Simple templates
These templates can be added to your wiki to leverage Bootstraps various
HTML/CSS components.

### Template:Panel
Use this template to easily generate panels on your pages.

    <div class="panel panel-{{{3|default}}}"><div class="panel-heading"><span class="panel-title">{{{1}}}</span></div><div class="panel-body">{{{2}}}</div></div>

Usage:

    {{panel
        |Panel Title
        |Panel Content
        |primary
    }}

### Template:Alert
This template is used to leverage Bootstrap's alert box:

    <div class="alert alert-{{{2}}}">{{{1}}}</div>

Usage:

    {{alert|Message you want to say|danger}}

### Template:Tip
This template is used to add Bootstrap tooltips.

    <span title="{{{2}}}" class="tip" rel="tooltip">{{{1}}}</span>

Usage:

    {{tip|Something|This is the tooltip!}}

Note: Since the tooltip text is written into the `title` attribute,
double-qoutes need to be escaped with `&quot;`.

### Template:Pop
This template is used to add Bootstrap popovers.

    <span data-original-title="{{{2}}}" data-content="{{{3}}}" class="pop">{{{1}}}</span>

Usage:

    {{pop|Whatever triggers the popover|Popover Title|Popover Content}}

Note: Since the popover title and content are written into `data` attributes,
double-qoutes need to be escaped with `&quot;`.

### Template:Jumbotron
You probably don't want to use too many Jumbotrons throughout your Wiki and
would not need this template. Just use the Jumbotron syntax directly on
the page.

    <div class="jumbotron">= {{{1}}} = {{{2}}}<div class="btn btn-primary btn-lg" role="button">{{{3}}}</div></div>

Usage:

    {{jumbotron|My large header|My Jumbotron text|My button label}}

### Template:Glyph
This template is useful if you want to use Glyphicons on your pages.

    <span class="glyphicon glyphicon-{{{1}}}"></span>

Usage:

    {{glyph|pencil}}

### Template:Fa
This template is useful if you want to use FontAwesome icons on your pages.

    <span class="fa fa-{{{1}}}"></span>

Usage:

    {{fa|pencil}}

## Advanced templates
These templates require the [Extension:ParserFunctions](http://mediawiki.org/wiki/Extension:ParserFunctions)
to be installed in your wiki, to allow for more complex templates.

### Template:Icon
This template is combines the `Glyph` and `Fa` template into one template, that
switches between them based on the icon parameter. To compare strings
[Extension:StringFunctions](http://mediawiki.org/wiki/Extension:StringFunctions)
is also required.

    {{#ifeq: {{#sub: {{{1}}}|0|3}}|fa-|<span class="fa {{{1}}}"></span>|<span class="glyphicon glyphicon-{{{1}}}"></span>}}

Usage:

    {{icon|pencil}}

or

    {{icon|fa-pencil}}
