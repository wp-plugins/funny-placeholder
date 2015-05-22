<?php
/**
 * Plugin Name: Funny placeholder
 * Plugin URI: http://melanger.cz/aplikace/wordpress-pluginy/vtipne-reklamy/
 * Description: If you do not have advertisements to show on your website, try some funny ads!
 * Version: 1.1
 * Author: melangercz
 * Author URI: http://melanger.cz/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
/*  Copyright 2014  Mélanger.cz  (email : plugins@melanger.cz)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('FUNNY_PLACEHOLDER_NAMES_LEN_IMG', 3);
define('FUNNY_PLACEHOLDER_NAMES_LEN_SIZE', 2);

add_action('init', 'funny_placeholder_init', 1);
function funny_placeholder_init(){
$locale = apply_filters('plugin_locale', get_locale(), 'funny-placeholder');
load_textdomain('funny-placeholder', WP_LANG_DIR.'/funny-placeholder/'.'funny-placeholder'.'-'.$locale.'.mo');
load_plugin_textdomain('funny-placeholder', FALSE, dirname(plugin_basename(__FILE__)).'/languages/');
}

class funny_placeholder extends WP_Widget {
	function __construct() {
		parent::__construct(false, __('Funny placeholder', 'funny-placeholder'), array( 'description' => __( 'Funny placeholders by Mélanger.cz', 'funny-placeholder' )) );
	}
	
	function form($instance) {
		if( $instance) {
			$select = esc_textarea($instance['select']);
		} else {
			$select = '';
		}
	?>
	<p>
	<label for="<?php echo $this->get_field_id('select'); ?>"><?php _e('Size','funny-placeholder'); ?>:</label>
	<select id="<?php echo $this->get_field_id('select'); ?>" name="<?php echo $this->get_field_name('select'); ?>">
		<option value="160x600"<?php echo ($select=="160x600")?" selected":"";?>>wide skyscraper (160x600)</option>
		<option value="300x250"<?php echo ($select=="300x250")?" selected":"";?>>medium rectangle (300x250)</option>
		<option value="728x90"<?php echo ($select=="728x90")?" selected":"";?>>leaderboard (728x90)</option>
	</select>
	</p>
	<?php
	}
	
	function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['select'] = strip_tags($new_instance['select']);
    return $instance;
	}
	
	function widget($args, $instance) {
		$ad_sizes = array("01"=>"160x600","02"=>"300x250","03"=>"728x90");
		if(isset($instance["select"]) && ($index=array_search($instance["select"], $ad_sizes))!==false)
			{
			$ad_size = $index;
			}
		else
			{
			$ad_size = "01";
			}
		
		$links = array(
			"cz"=>array(
				/*1=>"",*/
				),
			"en"=>array(
				
				)
			);
		
		switch(get_locale())
			{
			case "cs_CZ":
				$locale = "cz";
			break;
			default:
				$locale = "en";
			break;
			}
		
		$ads = array_values(array_diff(scandir(plugin_dir_path(__FILE__).'img/'.$ad_size.'/'.$locale), array('..', '.')));
		$index = mt_rand(0,count($ads)-1);
		$ad = str_pad(preg_replace("/\..*$/", "", $ads[$index]), FUNNY_PLACEHOLDER_NAMES_LEN_IMG, '0', STR_PAD_LEFT);
		
		if(isset($links[$locale][(int)$ad]))
			{
			$link = $links[$locale][(int)$ad];
			}
		elseif($locale == "cz")
			{
			$link = "http://melanger.cz/";
			}
		else
			{
			$link = "http://wordpress.org/plugins/funny-placeholder/";
			}
		?>
		<div class="widget funny_placeholder">
			<?php
			$file = 'img/'.$ad_size.'/'.$locale.'/'.$ad.'.png';
			$dimensions = explode("x", $ad_sizes[$ad_size]);
			echo "<a href='".esc_html($link)."' class='funny-placeholder-a'><img src='".esc_html(plugin_dir_url(__FILE__).$file)."' width='".$dimensions[0]."' height='".$dimensions[1]."' style='border:0' class='funny-placeholder-img' alt='".__('Funny placeholder served by Mélanger.cz','funny-placeholder')."' /></a>";
			?>
		<script type="text/javascript">
		jQuery("a.funny-placeholder-a img").on('error', function(){
			jQuery("a.funny-placeholder-a").html("<?php _e('You are blocking funny placeholders, that\'s a pity :(','funny-placeholder') ?>");
		});
		</script>
		</div>
		<?php
	}
}
function register_funny_placeholder()
{
    register_widget( 'funny_placeholder' );
}
add_action( 'widgets_init', 'register_funny_placeholder');
?>