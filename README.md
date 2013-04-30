# Twig Plugin for Minibase

Enables Twig based templates. Uses the same interface as Minibase offers for 
rendering normal PHP views. Also includes custom blocks, filters and functions to take the full out of Minibase. Easily do `Fragment caching`. 


This plugin makes it possible to render `.twig` and `.html` files with the Twig templating engine. `.php` files will still get rendered by the default Minibase view rendering engine.


## Install

```json
{
  "require":{
	     "pkj/minibase-plugin-twig": "dev-master"
	}
}

```

## Setup

Setup global view path, we need to know where twig templates are located.

```php
$mb->setConfig(MB::CFG_VIEWPATH, __DIR__ . '/views');
```

Init the plugin

```php
$mb->initPlugins(array(
	'Pkj\Minibase\Plugin\TwigPlugin\TwigPlugin' => array(
		// Where to store the compiled php templates.		
		'cache' => __DIR__ . '/template_compilation_cache'
	)
));
```

Start using twig templates.


## Functions

#### route

Reverse routing is easy.

```twig
Reverse routing:


{{"News.show"|route(32)}} 
Prints forexample: /news/32

{{"News.index"|route()}}
Prints forexample: /news

```

```twig
Check if a route is active:

{% if "News.index"|route().isActive %}
Currently on homepage.
{% endif %}
```

## Filters

#### asset

Easily add assets, the assets will have the correct base path included.

```twig
<link rel="stylesheet" type="text/css" href="{{"css/style.css"|asset}}" />

Or from a variable
<img src="{{news.img_path|asset}}" />

```

## Tags

#### cache

Easily cache bits of your view, VERY good if you use a ORM that have lazy loading such as Doctrine!

```twig
{% cache "uniqueKey1" %}
	
	Hello I am cached forever!
	
{% endcache %}


{% cache "uniqueKey2" 3600 %}

	I am cached for 1 hour.

{% endcache %}

```







