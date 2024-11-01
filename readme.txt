=== Plugin Name ===
Contributors: Joe Anzalone
Donate link: http://JoeAnzalone.com
Tags: smiley, emoticon, ascii, css
Requires at least: 2.0.2
Tested up to: 3.4.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Wraps text-based emoticons with &lt;span&gt; elements for easy styling. ex: &lt;span class="smiley biggrin"&gt;:D&lt;/span&gt;

== Description ==

Stylish Smilies adds CSS classes to text-based emoticons.
You can then reference these classes in your theme to style each emoticon differently:

`.smiley {
/* Applies to all emoticons */
  color: black;
  font-weight: bold;
}

.biggrin {
/* Applies to :D only */
  background: green;
}`

This plugin affects the following smilies:

`8-)	cool
 8O	eek
8-O	eek
 :(	sad
:-(	sad
 :)	smile
:-)	smile
:-?	confused
 :D	biggrin
:-D	biggrin
 :P	razz
:-P	razz
 :o	surprised
:-o	surprised
 :x	mad
:-x	mad
 :|	neutral
:-|	neutral
 ;)	wink
;-)	wink`

== Installation ==

1. Extract the zip file and drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the plugin from the "Plugins" page.
1. Add some custom CSS in your theme's style.css file to style your emoticons
3. Start using emoticons in your posts and pages!

== Frequently Asked Questions ==

= How do I get my old smiley icons back? =

Disable the "Stylish Smilies" plugin via the "Plugin" panel (/wp-admin/plugins.php)

== Changelog ==

= 1.0 =
* First version ever!
