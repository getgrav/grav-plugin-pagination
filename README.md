# Grav Pagination Plugin


`Pagination` is a [Grav][grav] Plugin that allows to divide articles into discrete pages. 


# Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `pagination`.

You should now have all the plugin files under

	/your/site/grav/user/plugins/pagination

>> NOTE: This plugin is a modular component for Grav which requires [Grav](http://github.com/getgrav/grav), the [Error](https://github.com/getgrav/grav-plugin-error) and [Problems](https://github.com/getgrav/grav-plugin-problems) plugins, and a theme to be installed in order to operate.

# Config Defaults

```
enabled: true
path: /blog
built_in_css: true
delta: 0
```

The 'delta' value controls how many pages left and right of the current page are visible. If set to 0 all pages will be shown. 

If you need to change any value, then the best process is to copy the [pagination.yaml](pagination.yaml) file into your `users/config/plugins/` folder (create it if it doesn't exist), and then modify there.  This will override the default settings.

# Usage

To use `pagination`, you need a blog-like structured page. Then, at the header of the main page, you will add the `pagination: true` setting.

```
---
title: Blog
blog_url: blog
pagination: true
---

# My Gravtastic Blog
## A tale of **awesomazing** adventures
```

If you want to override the look and feel of the pagination, copy the template file [pagination.html.twig][pagination] into the templates folder of your custom theme and that is it.

```
/your/site/grav/user/themes/custom-theme/templates/pagination.html.twig
```

You can now edit the override and tweak it to meet your needs.

[pagination]: templates/pagination.html.twig
[grav]: http://github.com/getgrav/grav
