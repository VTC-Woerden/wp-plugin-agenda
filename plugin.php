<?php

/*
 * Plugin Name: VTC Agenda
 */

require_once(dirname(__FILE__) . '/agenda.php');
require_once(dirname(__FILE__) . '/agenda-fullscreen.php');
require_once(dirname(__FILE__) . '/agenda-yodeck.php');

function agenda_styles() {
	// When css compression is enabled this will not update
	wp_enqueue_style('events', get_stylesheet_directory_uri() . '/agenda-style.css');
}

add_action('wp_enqueue_scripts', 'agenda_styles');