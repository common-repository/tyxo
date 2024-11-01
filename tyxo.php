<?php
/*
Plugin Name: Tyxo
Plugin Script: tyxo.php
Plugin URI: http://marto.lazarov.org/plugins/tyxo
Description: Track page views by authors
Version: 1.0.2
Author: mlazarov
Author URI: http://marto.lazarov.org/

*/

if (!class_exists('tyxo')) {
	class tyxo {
		function __construct() {
			$stored_options = get_option('tyxo_options');

			$this->options = (array)(is_serialized($stored_options)) ? unserialize($stored_options) : $stored_options;

			// Setting filters, actions, hooks....
			add_action("admin_menu", array (&$this, "admin_menu_link"));

			// Add script to footer
			add_action('wp_footer', array(&$this, 'tyxo_script'));

			//Additional links on the plugin page
			add_filter('plugin_row_meta', array('tyxo', 'filter_plugin_actions'),10,2);

		}

		// -----------------------------------------------------------------------------------------------------------
		/**
		* @desc Adds the options subpanel
		*/
		function admin_menu_link() {
			add_options_page('Tyxo', 'Tyxo', 8, basename(__FILE__), array (&$this, 'admin_options_page'));
			//add_filter('plugin_action_links_' . plugin_basename(__FILE__), array (&$this, 'filter_plugin_actions'), 10, 2);
		}

		// -----------------------------------------------------------------------------------------------------------
		/**
		* Adds the Settings link to the plugin activate/deactivate page
		*/
		function filter_plugin_actions($links, $file) {
			if ($file != 'tyxo/tyxo.php')  return $links;

			$settings_link = '<a href="options-general.php?page=' . basename(__FILE__) . '">' . __('Settings') . '</a>';
			//array_unshift($links, $settings_link); // before other links
			$links[] = $settings_link; //after other links
			return $links;
		}

		function tyxo_script(){
			if($this->options['tyxo_id']){
				$tyxo_id = $this->options['tyxo_id'];
				?>

				<!-- NACHALO NA TYXO.BG BROYACH -->
				<script type="text/javascript">
				<!--
				d=document;d.write('<a href="https://www.tyxo.bg/?<?php echo $tyxo_id;?>" title="Tyxo.bg counter"><img width="1" height="1" border="0" alt="Tyxo.bg counter" src="'+location.protocol+'//cnt.tyxo.bg/<?php echo $tyxo_id;?>?rnd='+Math.round(Math.random()*2147483647));
				d.write('&sp='+screen.width+'x'+screen.height+'&r='+escape(d.referrer)+'"></a>');
				//-->
				</script><noscript><a href="http://www.tyxo.bg/?<?php echo $tyxo_id;?>" title="Tyxo.bg counter"><img src="https://cnt.tyxo.bg/<?php echo $tyxo_id;?>" width="1" height="1" border="0" alt="Tyxo.bg counter" /></a></noscript>
				<!-- KRAI NA TYXO.BG BROYACH -->

				<?php
			}
		}

		// -----------------------------------------------------------------------------------------------------------
		/**
		* Administration options page
		*/
		function admin_options_page() {
			global $wpdb;

			if ($_POST['tyxo-save']) {
				$this->options['tyxo_id'] = $_POST['tyxo_id'];
				update_option('tyxo_options', serialize($this->options));

			}

			?>
			<div class="wrap">
				<div id="dashboard" style="width:250px;padding:10px;">
					<h3>Tyxo Counter Config</h3>
					<form method="post">
						<div  style="">
							Tyxo ID (number):<br/>
							<input type="text" name="tyxo_id" value="<?=$this->options['tyxo_id'];?>" size="20"/>
							<input type="submit" name="tyxo-save" class="button-primary" value="Save" />
						</div>
					</form>
					<br/><br/>
					<h3>From where to get your Tyxo ID?</h3>
					- From your tyxo counter page.<br/><br/><br/>
					For example:<br/>https://www.tyxo.bg/?<b>131562</b><br/><br/>
					<b>131562</b> is your Tyxo ID

					<br/><br/>
					<h3>Tyxo plugin problem?</h3>
					For any problems with Tyxo Counter Plugin contact me at:
					<a href="http://marto.lazarov.org/plugins/tyxo/">marto.lazarov.org/plugins/tyxo</a>
				</div>



			</div>
			<?php
		}

	} //End Class
}

if (class_exists('tyxo')) {
	$wp_tyxo_var = new tyxo();
}
?>
