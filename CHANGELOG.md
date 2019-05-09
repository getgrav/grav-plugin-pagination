# v1.4.2
## 05/09/2019

1. [](#new)
    * Add active class for active page [#37](https://github.com/getgrav/grav-plugin-pagination/pull/37)
    * Added `ru` and `uk` translations [#38](https://github.com/getgrav/grav-plugin-pagination/pull/38)

# v1.4.1
## 08/28/2018

1. [](#bugfix)
    * Reverted Twig fix to address broken `url` in Twig [#34](https://github.com/getgrav/grav-plugin-pagination/issues/34)
    * Removed duplicate README text [#30](https://github.com/getgrav/grav-plugin-pagination/issues/30)

# v1.4.0
## 08/20/2018

1. [](#new)
    * Added Twig pagination function [#22](https://github.com/getgrav/grav-plugin-pagination/pull/22)
1. [](#improved)
    * Removed Grav trait in favor of `Grav::instance()`
    * Changed delta blueprint type from `text` to `number`
    * Code cleanup
    
# v1.3.2
## 05/03/2016

1. [](#new)
    * Added default pagination to `page.collection.params.pagination` and `base_url` to `page.url`
1. [](#improved)
    * Use common lang strings in blueprints    
1. [](#bugfix)
    * Improved and fixed README.md
    
# v1.3.1
## 08/29/2015

1. [](#bugfix)
    * Fixed pagination URLs in certain situations

# v1.3.0
## 08/25/2015

1. [](#improved)
    * Added blueprints for Grav Admin plugin

# v1.2.7
## 07/13/2015

1. [](#new)
	* Added ability to provide `/tmpl:template_name` parameter to pagination URL
2. [](#improved)
    * Allow checking of `header.content.pagination` **or** `header.pagination` to activate

# v1.2.6
## 07/12/2015

2. [](#improved)
    * no `/page:1` for first page in pagination set

# v1.2.5
## 02/19/2015

2. [](#improved)
    * Implemented new `param_sep` variable from Grav 0.9.18

# v1.2.4
## 02/05/2015

2. [](#improved)
    * Added support for HHVM

# v1.2.3
## 01/23/2015

2. [](#improved)
    * Added microdata information for links

# v1.2.2
## 01/09/2015

2. [](#improved)
    * NOTE: BREAKING CHANGE: Moved templates into `partials/` subfolder for consistency.

# v1.2.1
## 11/30/2014

1. [](#new)
    * ChangeLog started...
