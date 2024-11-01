<?php
/*
Plugin Name: Stylish Smilies
Plugin URI: http://JoeAnzalone.com/plugins/stylish-smilies/
Description: Wraps text-based emoticons with <span> elements for easy styling. ex: &lt;span class="smiley biggrin"&gt;:D&lt;/span&gt;
Author: Joe Anzalone
Version: 1.0
Author URI: http://JoeAnzalone.com
*/

class shmit_stylish_smilies {
	
	function smilies_init() {
	// Most of this function's code is a copy of smilies_init() in /wp-includes/functions.php
	global $wpsmiliestrans, $wp_smiliessearch;

	/*
	// don't bother setting up smilies if they are disabled
	if ( !get_option( 'use_smilies' ) )
		return;
	*/

	if ( !isset( $wpsmiliestrans ) ) {
		$wpsmiliestrans = array(
		':mrgreen:' => 'icon_mrgreen.gif',
		':neutral:' => 'icon_neutral.gif',
		':twisted:' => 'icon_twisted.gif',
		  ':arrow:' => 'icon_arrow.gif',
		  ':shock:' => 'icon_eek.gif',
		  ':smile:' => 'icon_smile.gif',
		    ':???:' => 'icon_confused.gif',
		   ':cool:' => 'icon_cool.gif',
		   ':evil:' => 'icon_evil.gif',
		   ':grin:' => 'icon_biggrin.gif',
		   ':idea:' => 'icon_idea.gif',
		   ':oops:' => 'icon_redface.gif',
		   ':razz:' => 'icon_razz.gif',
		   ':roll:' => 'icon_rolleyes.gif',
		   ':wink:' => 'icon_wink.gif',
		    ':cry:' => 'icon_cry.gif',
		    ':eek:' => 'icon_surprised.gif',
		    ':lol:' => 'icon_lol.gif',
		    ':mad:' => 'icon_mad.gif',
		    ':sad:' => 'icon_sad.gif',
		      '8-)' => 'icon_cool.gif',
		      '8-O' => 'icon_eek.gif',
		      ':-(' => 'icon_sad.gif',
		      ':-)' => 'icon_smile.gif',
		      ':-?' => 'icon_confused.gif',
		      ':-D' => 'icon_biggrin.gif',
		      ':-P' => 'icon_razz.gif',
		      ':-o' => 'icon_surprised.gif',
		      ':-x' => 'icon_mad.gif',
		      ':-|' => 'icon_neutral.gif',
		      ';-)' => 'icon_wink.gif',
		// This one transformation breaks regular text with frequency.
		//     '8)' => 'icon_cool.gif',
		       '8O' => 'icon_eek.gif',
		       ':(' => 'icon_sad.gif',
		       ':)' => 'icon_smile.gif',
		       ':?' => 'icon_confused.gif',
		       ':D' => 'icon_biggrin.gif',
		       ':P' => 'icon_razz.gif',
		       ':o' => 'icon_surprised.gif',
		       ':x' => 'icon_mad.gif',
		       ':|' => 'icon_neutral.gif',
		       ';)' => 'icon_wink.gif',
		      ':!:' => 'icon_exclaim.gif',
		      ':?:' => 'icon_question.gif',
		);
	}

	if (count($wpsmiliestrans) == 0) {
		return;
	}

	/*
	 * NOTE: we sort the smilies in reverse key order. This is to make sure
	 * we match the longest possible smilie (:???: vs :?) as the regular
	 * expression used below is first-match
	 */
	krsort($wpsmiliestrans);

	$wp_smiliessearch = '/(?:\s|^)';

	$subchar = '';
	foreach ( (array) $wpsmiliestrans as $smiley => $img ) {
		$firstchar = substr($smiley, 0, 1);
		$rest = substr($smiley, 1);

		// new subpattern?
		if ($firstchar != $subchar) {
			if ($subchar != '') {
				$wp_smiliessearch .= ')|(?:\s|^)';
			}
			$subchar = $firstchar;
			$wp_smiliessearch .= preg_quote($firstchar, '/') . '(?:';
		} else {
			$wp_smiliessearch .= '|';
		}
		$wp_smiliessearch .= preg_quote($rest, '/');
	}

	$wp_smiliessearch .= ')(?:\s|$)/m';
}

	function translate_smiley($smiley){
	// Most of this function's code is a copy of translate_smiley() in /wp-includes/formatting.php
		global $wpsmiliestrans;
	
		if (count($smiley) == 0) {
			return '';
		}
	
		$smiley = trim(reset($smiley));
		$img = $wpsmiliestrans[$smiley];
		$smiley_masked = esc_attr($smiley);
	
		$srcurl = apply_filters('smilies_src', includes_url("images/smilies/$img"), $img, site_url());
	
		// return " <img src='$srcurl' alt='$smiley_masked' class='wp-smiley' /> ";
		
		preg_match('#icon_(.+)\.gif#', $img, $smiley_name_matches);
		
		$smiley_name = $smiley_name_matches[1];
		
		$classes[] = 'smiley';
		$classes[] = $smiley_name;
		
		$classes = implode(' ', $classes);
		return ' <span class="'.$classes.'">'.$smiley.'</span>';
	}
	
	function the_content_filter($text){
		
		global $wp_smiliessearch;

		$output = '';
	//	if ( get_option('use_smilies') && !empty($wp_smiliessearch) ) {		
		if ( !empty($wp_smiliessearch) ) {
			// HTML loop taken from texturize function, could possible be consolidated
			$textarr = preg_split("/(<.*>)/U", $text, -1, PREG_SPLIT_DELIM_CAPTURE); // capture the tags as well as in between
			$stop = count($textarr);// loop stuff
			for ($i = 0; $i < $stop; $i++) {
				$content = $textarr[$i];
				if ((strlen($content) > 0) && ('<' != $content[0])) { // If it's not a tag
					$content = preg_replace_callback($wp_smiliessearch, array($this,'translate_smiley'), $content);
				}
				$output .= $content;
			}
		} else {
			// return default text.
			$output = $text;
		}
		return $output;

	}
	
	
		 
	function build_pre_match($match){
	/*
	Thanks to betterwp.net for preventing smilies from being marked up within <pre> tags!
	http://betterwp.net/wordpress-tips/no-convert_smilies-in-code-pre-tags/
	*/
		$this->bwp_pre_matchess[] = $match[2];
		return "<pre" . $match[1] . ">" . $this->bwp_hash . sprintf("%03d", sizeof($this->bwp_pre_matchess) - 1) . "</pre>\n";
	}
	 
	function build_code_match($match){
		$this->bwp_code_matches[] = $match[2];
		return "<code" . $match[1] . ">" . $this->bwp_hash . sprintf("%03d", sizeof($this->bwp_code_matches) - 1) . "</code>\n";
	}
		
		
	function bwp_put_pre($identifier){
	 
		$identifier = (int) $identifier[1];
		$content = (isset($this->bwp_pre_matchess[$identifier])) ? $this->bwp_pre_matchess[$identifier] : '';
		return '>' . $content . '</pre>';
	}
 
	function bwp_put_code($identifier){
		$identifier = (int) $identifier[1];
		$content = (isset($this->bwp_code_matches[$identifier])) ? $this->bwp_code_matches[$identifier] : '';
		return '>' . $content . '</code>';
	}	
		
		
	
	function before_format($content){
		
		   $content = preg_replace_callback(
        "/<pre([^>]+)?>(.*?)<\/pre>/siu",
        array($this, "build_pre_match"),
        $content
    );
    $content = preg_replace_callback(
        "/<code([^>]+)?>(.*?)<\/code>/siu",
        array($this, "build_code_match"),
        $content
    );
    return $content;
		
	}
	
	function after_format($content){
		$content = preg_replace_callback(
			"/>" . $this->bwp_hash . "(\d{3})<\/pre>/siu",
			array($this, "bwp_put_pre"),
			$content
		);
		$content = preg_replace_callback(
			"/>" . $this->bwp_hash . "(\d{3})<\/code>/siu",
			array($this, "bwp_put_code"),
			$content
		);
	 
		// Saving some memory ;)
		unset($this->bwp_pre_matches);
		unset($this->bwp_code_matches);
	 
		return $content;
    }
	
		
	
	function __construct(){
		$this->bwp_hash = md5(rand(0, 1000) . time() . 'totally random hash' . $_SERVER['REMOTE_ADDR'] );
		
		// Disable the default smiley behavior
		remove_filter( 'the_content', 'convert_smilies' );
		remove_filter( 'the_excerpt', 'convert_smilies' );
		remove_filter( 'comment_text', 'convert_smilies' );
		
		
		add_filter('the_content', array($this, 'before_format'), 7);
		add_filter('the_content', array($this, 'the_content_filter'), 10 );
		add_filter('the_content', array($this, 'after_format'), 11);
		
		
		add_action( 'init', array($this, 'smilies_init'));
		
	}
	
}

new shmit_stylish_smilies;

?>