=== Zoom OpenSeadragon ===
Contributors: altert
Tags: gallery, zoom, pyramid, highres, tiles, tiled, image, images
Donate link: http://altert.net/demo/donate/
Requires at least: 4.0.0
Tested up to: 4.4.1
Stable tag: 1.0
License: New BSD License
License URI: http://altert.net/BSD-LICENSE.txt

Create zoomable galleries from standart wordpress images as well as from deepzoom images.

== Description ==
Zoom OpenSeadragon is an implementation of [OpenSeadragon](http://openseadragon.github.io//), an open-source, web-based viewer for high-resolution zoomable images, implemented in pure JavaScript, for desktop and mobile.

It allows to create zoomable galleries from standart wordpress images as well as from deepzoom images.

You can see [live demo here](http://altert.net/demo/openseadragon-zoom)

Zoom OpenSeadragon is released under the New BSD license  

== Installation ==
Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the Plugin from Plugins page. 

To replace wordpress gallery with Zoom OpenSeadragon gallery you need to add `openseadragon="true"` to gallery shortcode.  You can also use Media options to replace all galleries with Zoom OpenSeadragon gallery. 

To show zoomable images, e.g. Deep Zoom images, you need to add paths of them to zoomimages attribute of shortcode, for example

`[gallery openseadragon="true" zoomimages="/example.com/zoom/zoom1.dzi,/example.com/zoom/zoom2.dzi"]`

Other attributes:

* `width` - width of Seadragon Zoom, 600px by default
* `height` - height of Seadragon Zoom, 600px by default
* `columns` - number of columns in grid view
* `noattachments` - do not use images, attached to post (for example, when you need to display only DeepZoom images)
* `captions` - display captions

Several OpenSeadragon parameters are also supported, see http://openseadragon.github.io/docs/OpenSeadragon.html#Options for description of these parameters.

* `shownavigationcontrol` - show zoom in/out/home/full buttons, true by default
* `showzoomcontrol` - show zoom buttons, true by default
* `showhomecontrol` - show home button, true by default
* `showfullpagecontrol` - show fullscreen button, true by default
* `showrotationcontrol` - show rotation buttons, false by default
* `sequencemode` - display pictures in sequence mode, one by one instead of grid
* `showsequencecontrol` - show next/prev buttons for sequence mode, true by default
* `shownavigator` - show navigator minimap
* `navigatorid` - id of navigator div, autocreated if left empty
* `navigatorposition` - position of navigator minimap, TOP_RIGHT is default
* `showreferencestrip` - show thumbnails of images, false by default
* `referencestripsizeratio` - ratio of thumbnails sizes to deepzoom size, 0.2 by default
* `referencestripposition` - position of reference strip, BOTTOM_LEFT by default
* `referencestripscroll` - type of reference scroll, 'horizontal' by default



== Screenshots ==

1. Options for Zoom OpenSeadragon galleries. First gallery links to precreated DeepZoom image, second and third use standart wordpress media files
2. Single zoomable image
3. Tiled gallery, collection mode
4. Sequence mode


== Frequently Asked Questions ==

= How to replace specific gallery with Zoom OpenSeadragon gallery? =

To replace wordpress gallery with Zoom OpenSeadragon you need to add openseadragon="true" to gallery shortcode.

= How to replace all galleries with reel gallery? =

Use Zoom OpenSeadragon Options section in Settings => Media.

= How to create really big zoomable image? =

See http://openseadragon.github.io/examples/creating-zooming-images/




== Changelog ==


= 1.0 =
* Initial release

