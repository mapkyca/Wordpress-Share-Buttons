<?php
/*
Plugin Name: Wordpress Share Buttons
Plugin URI: https://github.com/mapkyca/Wordpress-Share-Buttons
Description: Provides share buttons and open graph headers.
Version: 0.1
Author: Marcus Povey
Author URI: http://www.marcus-povey.co.uk
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/


define('SHAREBUTTONS_VERSION', '0.1');

/**
 * Initialisation
 */
function wsbuttons_init()
{
	
}

/**
 * Extend page headers.
 */
function wsbuttons_add_headers()
{
    global $post;
    $meta_tags = "<!-- Added by Wordpress Share Buttons " . SHAREBUTTONS_VERSION . " -->\n";

    // Do we need to add meta
    if (($post) && ( (is_single()) || (is_page()) ))
    {
		foreach (array(
			'og:image',
			'og:title',
			'og:url',
			'og:type',
		) as $ogtag)
		{
			$content = get_post_meta($post->ID, $ogtag, true);

			switch ($ogtag)
			{
			case 'og:title' :
				if (!$content)
				$content = wp_specialchars($post->post_title);
			break;

			case 'og:type' :
				if (!$content)
				$content = 'article';
			break;

			case 'og:url' : 
				$content = get_permalink($post->ID);
			break;

			case 'og:image':	
				// Otherwise return og:image custom header
				$content = get_post_meta($post->ID, $ogtag, true);
			break;
			}

			if ($content)
				$meta_tags .= "<meta property=\"$ogtag\" contents=\"$content\" />\n";
		}
    }

	// Add some common values
	
	$meta_tags .= "<meta property=\"og:site_name\" content=\"".wp_specialchars(get_option('blogname'))."\" />\n";
	$meta_tags .= "<!-- -->\n";
	
    echo $meta_tags;
}

/**
 * Extend the footer, adding appropriate javascript.
 */
function wsbuttons_footer()
{
?>
	<!-- Google +1 button code -->
	<script type="text/javascript">
	  window.___gcfg = {lang: 'en-GB'};

	  (function() {
		var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
		po.src = 'https://apis.google.com/js/plusone.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
	  })();
    </script>

	<!-- FB Root -->
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) {return;}
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1";
			  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>

<?php	
}

/**
 * Add the share buttons.
 */
function wsbuttons_add_buttons($content)
{
	ob_start();
	?>
<div class="wsbuttons">
	<div class="shareblob facebook">
		<div class="fb-like" data-href="<?php echo get_permalink() ?>" data-send="false" data-layout="box_count" data-width="60" data-show-faces="false" data-colorscheme="light"></div>
	</div>

	<div class="shareblob google">
		<div class="g-plusone" data-size="tall" data-href="<?php echo get_permalink() ?>"></div>
	</div>

	<div class="shareblob twitter">
		<div class="twitter">
			<a href="https://twitter.com/share?url=<?php echo urlencode(get_permalink()); ?>&count=vertical" class="twitter-share-button" data-lang="en">Tweet</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		</div>
	</div>

</div>
	<?php
	
		return $content . ob_get_clean();
}

// Listen for init and header requests
add_action('init', 'wsbuttons_init');
add_action('wp_head', 'wsbuttons_add_headers');
add_action('wp_footer', 'wsbuttons_footer');
add_filter('the_content', 'wsbuttons_add_buttons');
