=== Plugin Name ===
Contributors: DvanKooten
Donate link: http://dannyvankooten.com/donate/
Tags: scroll triggered box, cta, social, newsletter, call to action, mailchimp
Requires at least: 3.5
Tested up to: 3.7.1
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The best non-obtrusive call-to-action: Scroll Triggered Boxes. Show social media sharing options or sign-up forms in conversion boosting boxes.

== Description ==

= Scroll Triggered Boxes =

Scroll Triggered Boxes are boxes that fade in when a user reach certain points on your pages. 

By then you can be sure those visitors are interested in your content, making this the perfect place to ask them something. A box that fades in at a corner of your visitors screen is a non obtrusive way to do so. 

Scroll triggered boxes are the best non-obtrusive way to ask your visitors to do something. Whether you want to ask them to subscribe to your newsletter, share the post they just read or do something entirely different: this plugin makes it easy.

**What to show in the box?**

You can use anything as the box its content: from social media sharing options to sign-up or contact forms. The plugin is designed to work with *any* other plugin that uses shortcodes.

**Features**

- Create an unlimited amount of boxes
- Control where and when a box should show-up
- Designing your boxes is simple yet effective.
- Use anything as the box content. Text, images, forms, shortcodes, you decide!
- Set the number of days for which a box should stay hidden for users who closed it
- Set the animation to use when a box is triggered

**Demo**
I'm using the plugin myself on [the website for one of my other plugins: MailChimp for WordPress](http://dannyvankooten.com/mailchimp-for-wordpress/). Scroll down a bit and you'll see a box slide in in the bottom right corner, asking you to sign-up to the plugin newsletter.

**More information**

Check out more information about the [Scroll Triggered Boxes plugin for WordPress](http://dannyvankooten.com/wordpress-plugins/scroll-triggered-boxes/) or have a look at the other [WordPress plugins](http://dannyvankooten.com/wordpress-plugins/) by [Danny van Kooten](http://dannyvankooten.com/).

== Frequently Asked Questions ==

= How to display a form in the box? =

The plugin is tested with the plugins below but will work with any plugin that uses shortcodes.

- [MailChimp for WordPress](http://wordpress.org/plugins/mailchimp-for-wp/)
- [Contact Form 7](http://wordpress.org/plugins/contact-form-7/)
- [Newsletter Sign-Up](http://wordpress.org/plugins/newsletter-sign-up/)

= How to display sharing options in the box? =

The plugin is tested with the plugins below but will work with any plugin that uses shortcodes.

- [Shareaholic](http://wordpress.org/plugins/shareaholic/)
- [Social Media Feather](http://wordpress.org/plugins/social-media-feather/)
- [WP Socializer](http://wordpress.org/plugins/wp-socializer/)
- [Tweet, Like, Google +1 and Share](http://wordpress.org/plugins/only-tweet-like-share-and-google-1/)

= How to set more advanced styling rules =

If you want more advanced styling, you can use CSS to further style the boxes. Every box gets its own unique #id as well as various CSS classes.

`
#box-{id} { } /* 1 particular box */
.stb { } /* all boxes */
.stb-content { } /* the contents of the box */
.stb-close{ } /* the close button of the box */
`

= I want to disable auto-paragraphs in the box content =

All default WordPress filters are added to the `stb_content` filter hook. If you want to remove any of them, add the respectable line to your theme its `functions.php` file.

`
remove_filter( 'stb_content', 'wptexturize') ;
remove_filter( 'stb_content', 'convert_smilies' );
remove_filter( 'stb_content', 'convert_chars' );
remove_filter( 'stb_content', 'wpautop' );
remove_filter( 'stb_content', 'do_shortcode' );
remove_filter( 'stb_content', 'shortcode_unautop' );
`

= Will a box be shown on mobile devices or small screens? =

If the box fits, it will. If the box width does not fit on the screen, it will be automatically hidden.

== Installation ==

= Installing the plugin =

1. In your WordPress admin panel, go to *Plugins > New Plugin*, search for *Scroll Triggered Boxes* and click "Install now"
1. Alternatively, download the plugin and upload the contents of `scroll-triggered-boxes.zip` to your plugins directory, which usually is `/wp-content/plugins/`.
1. Activate the plugin. 

= Creating a Scroll Triggered Box =

1. Go to *Scroll Triggered Boxes > Add New*
1. Add some content to the box
1. (Optional) customize the appearance of the box by changing the *Appearance Settings*

== Screenshots ==

1. A scroll triggered box with a newsletter sign-up form.
2. Another scroll triggered box, this time with social media sharing options.
3. A differently styled social triggered box.
4. Configuring and customizing your boxes is easy.

Some more screenshots can be found at the [Scroll Triggered Boxes plugin page on my website](http://dannyvankooten.com/wordpress-plugins/scroll-triggered-boxes/).

== Changelog ==

= 1.0.2 - November 12, 2013 =

- Fixed: Script now checks trigger criteria for multiple boxes at once.
- Improved: Script performance.
- Improved: All the default WordPress filters that run on posts do now run on the box content as well, meaning you can use smileys etc. in the box content. Filters are added to the `stb_content` hook, you can remove them from your theme its `functions.php` if you want.
- Added: Option to choose which animation to use: slide or fade.
- Added: Box now automatically shows when an element inside the box is referenced in the browser hash. This is especially useful for forms that do not use AJAX.
- Added: Menu icon in WP Admin

= 1.0.1 - November 11, 2013 =

- Improved: fix that removes unwanted linebreaks from shortcode output

= 1.0 - November 10, 2013 =

- Added: custom trigger points

= 1.0-beta2 - November 8, 2013 =

- Fixed: Box position bottom right is now selectable
- Fixed: Post type filter now works.
- Improved: Box settings on small screens

= 1.0-beta1 - November 6, 2013 =

- Initial release, things like settings might still change without backwards compatibility.

