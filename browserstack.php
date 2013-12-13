<?php
/*
Plugin Name: Browserstack
Plugin URI: http://wordpress.org/plugins/browserstack/
Description: Open the current page in BrowserStack.
Author: Jeroen Schmit, Slim & Dapper
Version: 0.1
Author URI: http://slimndap.com/
Text Domain: browserstack
*/

class Browserstack {

	function __construct() {

		$this->current_url = $this->get_current_url();
		$this->options = get_option('browserstack');
		$this->stack = $this->get_stack();

		add_action( 'admin_bar_menu', array($this,'admin_bar_menu'), 999 );
		
		add_action('plugins_loaded', function(){
			load_plugin_textdomain('browserstack', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		});

		add_action( 'wp_enqueue_scripts', function() {
			wp_enqueue_style( 'browserstack_css', plugins_url( 'style.css', __FILE__ ) );
		});



	}

	function admin_bar_menu( $wp_admin_bar ) {
		$args = array(
			'id'    => 'browserstack',
			'title' => '<span class="ab-label">Browserstack</span>',
			'href'  => false,
			'meta'  => array( 'class' => 'my-toolbar-page' )
		);
		$wp_admin_bar->add_node( $args );

		$i = 0;		
		foreach ($this->stack as $os=>$browsers) {
			$args = array(
				'id'    => 'browserstack_os_'.$i,
				'title' => $os,
				'href'  => false,
				'meta'  => array( 'class' => 'my-toolbar-page' ),
				'parent' => 'browserstack'
			);
			$wp_admin_bar->add_node( $args );
			$j=0;
			foreach ($browsers as $key => $val) {
				$args = array(
					'id'    => 'browserstack_os_'.$i.'_'.$j,
					'title' => $val,
					'href'  => 'http://www.browserstack.com/start#'.$key.'&url='.$this->current_url.'&zoom_to_fit=true&resolution=undefined&speed=1&start=true',
					'meta'  => array( 'class' => 'my-toolbar-page' ),
					'parent' => 'browserstack_os_'.$i
				);
				$wp_admin_bar->add_node( $args );
					
				$j++;
			}
			$i++;
		}
	}

	function get_current_url() {
		$pageURL = 'http';
		if( isset($_SERVER["HTTPS"]) ) {
			if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		}
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
	
	function get_stack() {
		return array(
			'iOS' => array(
				'os=ios&os_version=3.0&device=iPhone+3GS' => '3.0 (iPhone 3GS)',
				'os=ios&os_version=4.3.2&device=iPad+2' => '4.3.2 (iPad 2)',
				'os=ios&os_version=5.1&device=iPhone+4S' => '5.1 (iPhone 4S)',
				'os=ios&os_version=6.0&device=iPhone+5' => '6 (iPhone 5)'
			),
			'Android' => array(
				'os=android&os_version=2.2&device=Samsung+Galaxy+S' => '2.3 (Galaxy S)',
				'os=android&os_version=4.0&device=HTC+Evo+3D' => '4.0 (Evo 3D)',
				'os=android&os_version=4.1&device=Google+Nexus+7' => '4.1 (Nexus 7)'
			),
			'Opera Mobile' => array(
				'os=opera&device=HTC+Wildfire' => 'HTC Wildfire'
			),
			'Internet Explorer' => array(
				'os=Windows&os_version=XP&browser=IE&browser_version=6.0' => '6 (Win XP)',
				'os=Windows&os_version=XP&browser=IE&browser_version=7.0' => '7 (Win XP)',
				'os=Windows&os_version=7&browser=IE&browser_version=8.0' => '8 (Win 7)',
				'os=Windows&os_version=7&browser=IE&browser_version=9.0' => '9 (Win 7)',
				'os=Windows&os_version=8&browser=IE&browser_version=10.0' => '10 (Win 8)'
			),
			'Firefox' => array(
				'os=OS+X&os_version=Snow+Leopard&browser=Firefox&browser_version=5.0' => '5 (Mac 10.6)',
				'os=OS+X&os_version=Mountain+Lion&browser=Firefox&browser_version=19.0' => '19 (Mac 10.8)'
			),
			'Opera' => array(
				'os=OS+X&os_version=Snow+Leopard&browser=Opera&browser_version=11.1' => '11.1 (Mac 10.6)',
				'os=OS+X&os_version=Mountain+Lion&browser=Opera&browser_version=12.13' => '12.13 (Mac 10.8)'
			),
			'Safari' => array(
				'os=OS+X&os_version=Snow+Leopard&browser=Safari&browser_version=4.0' => '4 (Mac 10.6)',
				'os=OS+X&os_version=Mountain+Lion&browser=Safari&browser_version=6.0' => '6 (Mac 10.8)'
			),
			'Chrome' => array(
				'os=OS+X&os_version=Snow+Leopard&browser=Chrome&browser_version=14.0' => '14 (Mac 10.6)',
				'os=OS+X&os_version=Mountain+Lion&browser=Chrome&browser_version=25.0' => '25 (Mac 10.8)'
			),
		);
	}
}

if( !is_admin() )
	$browserstack = new Browserstack();
?>
