<?php
/*
Plugin Name: Minepress
Plugin URI: https://github.com/mmelvin0/minepress
Description: Minecraft server monitor widget.
Version: 0.1
Author: Mike
Author URI: https://github.com/mmelvin0
License: none
*/

defined('DS') || define('DS', DIRECTORY_SEPARATOR);

class Minepress {

	static public function instance() {
		static $self;
		if (!$self) {
			$self = new self;
			add_action('init', function () {
				wp_register_script('minepress.js', Minepress::url() . 'minepress.js', array('jquery'));
				wp_enqueue_script('minepress.js');
			});
			add_action('widgets_init', function () {
				register_widget('MinepressWidget');
			});
		}
		return $self;
	}

	static public function url() {
		return plugin_dir_url( __FILE__ );
	}

}

class MinepressWidget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'minepress',
			'Minepress',
			array(
				'description' => 'Displays information about a Minecraft server.'
			)
		);
	}

	function form($props) {
		$props = wp_parse_args((array)$props, array(
			'title' => ''
		));
		$title = $props['title'];
		include __DIR__ . DS . 'widget.form.php';
	}

	function update($new, $old) {
		$props = $old;
		$props['title'] = $new['title'];
		return $props;
	}

	function widget($info, $props) {
		extract($info);
		$title = apply_filters('widget_title', empty($props['title']) ? 'Minecraft Server' : $props['title'], $props, $this->id_base);
		include __DIR__ . DS . 'widget.view.php';
	}

}

Minepress::instance();
