=== PE Easy Slider ===
Contributors: pixelemu
Donate link: https://pixelemu.com
Tags: easy slider, thumbnails, widget
Requires at least: 3.9
Tested up to: 5.3
Stable tag: 1.0.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The simple plugin that allows you to display image slides with title linked to posts from selected category. 

== Description ==
The simple plugin that allows you to display image slides with title linked to posts from selected category. The slide title appears with slide-in animation effect.
The user may select the category or display items of all categories. 
Number of slides is unlimited and you may specify how many slides you want to be visible in raw.
Image sizes available to select from the list are determined at Media Settings of Wordpress. This way the plugin do not have to scale images by itself which is more site optimization friendly solution. 

**Configuration (see screenshot of backend):**

1. Widget title.
2. Styles - select one of the 2 available slider view styles (gallery grid, slider).
3. Source - display images from posts or folder.
4. Category - select the category to display items. You may select the specified category or display items from all categories. Empty categories are not displayed. 
5. Number of items in row.
6. Number of rows.
7. Number of all items - total number of slides displayed with widget.
8. Read more - show/hide read more link. 
9. Posts order direction (ascending, descending).
10. Posts ordering (date, title, most commented).
11. Navigation (arrows, bullets, none).
12. Image size from Wordpress settings (Settings -> Media). You can choose: thumbnail, medium, large
13. Image height in px - if uploaded images for posts have not the same height for all you may make them equal by entering the height in pixels. It's not recommended since Google does not accept such solutions like changing image height with CSS but if you have no other choice ypu may use this option.
14. Grid spacing - space between items.

== Installation ==
1. Upload the 'pe-easy-slider' folder to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to the 'Widgets' page found under the 'Appearance' menu item
4. Drag 'PE Easy Slider' to the target widget area and choose your options

== Screenshots ==
1. The backend interface.
2. Gallery grid view, 2 items per row.
3. Example: 4 thumbnails with bullets.
4. Example: 2 large images with arrow navigation.
5. Source from folder.
6. Gallery grid view, 3 items in row.

== Changelog ==

= 1.0.9 =
= fixed: =
* Fixed Consistent Identification (AA) - 'List item used to format text' related to the navigation bullets

= 1.0.8 =
= fixed: =
* Fixed warning about create_function() for PHP 7.2+

= 1.0.7 =
= added: =
* Improvements related to indicators (bullets) to allow easy change slide/item for keyboard users

= 1.0.6 =
= modified: =
* Added "data-no-retina" attribute for images if source is "Folder as source"

= 1.0.5 =
= added: =
* Option to change interval
* Option to disable autoplay
* Option for pause on hover
* Loading the plugin's translated strings

= modified: =
* Alternative text for image (source: posts) grabbed from image's alt or from post's title if image's alt is empty
* Alternative text for image (source: folder as a source) grabbed from image's name

= fixed: =
* Fixed WCAG errors

= 1.0.4 =
= fixed: =
* Fixed PHP Notice under PHP 7

= 1.0.3 =
= modified: =
* Removed unnecessary Bootstrap CSS

= fixed: =
* Fixed navigation "Bullets" when source: folder is chosen
* Hide widget heading when title is empty
* Fixed PHP Notice

= 1.0.2 =
= fixed: =
* Warning appeared when fields  "Number of items in row" and "Number of rows" were cleared
* Double bootstrap scripts loading. The plugin does not load bootstrap scripts if it is already loaded by the theme.
* Cleared space of grid for last items 

= added: =
* Better adjusting images to mobile devices - counting set number of images in row and dividing its number in row on small devices. If the set number of images in row is even, images are displayed as follows, for:  991px a 768px - 2 items in row, below 768px - 1 item in row. Otherwise images are decreasing in the row adjusting to the screen resolution but below 768px images are displayed 1 item in row.
* New option enable/disable loading sticky posts.
* Added separator for every row - useful when your images have different dimensions

= 1.0.1 =
= modified: =
* Extended possibilities for slides displaying. Now you may create a gallery grid by setting the number of slides per row.

= added: =
* Gallery grid view added.
* Option to display images from specified folder.
* Option to not display navigation at all.
* Option to show/hide readmore button.
* Option to set a space between slides.