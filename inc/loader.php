<?php

define('INC_DIR', get_stylesheet_directory() . '/inc');
require_once INC_DIR . '/user/user.php';

/*
 * Load classes
 */

require_once INC_DIR . '/classes/Invoice.php';
require_once INC_DIR . '/classes/Apartment.php';
require_once INC_DIR . '/classes/BookingDiscount.php';
require_once INC_DIR . '/classes/BookingInvoice.php';
require_once INC_DIR . '/classes/BookingMail.php';
require_once INC_DIR . '/classes/BookingOrder.php';


/*
 * Load shortcodes
 */

require_once INC_DIR . '/shortcodes/apartment-archive.php';
require_once INC_DIR . '/shortcodes/advanced-price-apt-card.php';
require_once INC_DIR . '/shortcodes/booking-table.php';
require_once INC_DIR . '/shortcodes/filters-link.php';
require_once INC_DIR . '/shortcodes/icon-list.php';
require_once INC_DIR . '/shortcodes/season-popup.php';
require_once INC_DIR . '/shortcodes/thank-you-booking.php';


/*
 * Load ajax
 */

require_once INC_DIR . '/ajax/get-apartments.php';
require_once INC_DIR . '/ajax/get-bookings.php';
require_once INC_DIR . '/ajax/send-booking-mail.php';

/*
 * Load functions
 */
require_once INC_DIR . '/functions/delete-booking-order.php';
require_once INC_DIR . '/functions/mail-cron-jobs.php';
require_once INC_DIR . '/functions/mail-status-for-booking-orders.php';
require_once INC_DIR . '/functions/send-booking-mail.php';
require_once INC_DIR . '/functions/update-booking-order.php';


/**
 * Admin scripts and styles
 */
add_action('admin_enqueue_scripts', function() {
	/* Booking admin scripts */
	if (get_current_screen()->post_type === 'booking-orders') {
		wp_enqueue_script('custom-save-button', get_stylesheet_directory_uri() . '/assets/scripts/admin-post-save-button.js', array('jquery'), null, true);
	}

	$user = wp_get_current_user();
	if(isset($_GET["post"])) {
		$postType = get_post_type($_GET["post"]);
		if ($user && in_array("booking_manager", $user->roles) && $postType == "booking-orders") {
			wp_enqueue_style('booking_orders_edit_style', get_stylesheet_directory_uri() . '/assets/styles/booking-orders.css');
		}
		if(get_post_type($_GET["post"]) == "booking-orders") {
			wp_enqueue_script('booking_orders_edit_script', get_stylesheet_directory_uri() . '/assets/scripts/booking-order.js', array('jquery'), null, true);
		}
	}
	if($user && in_array("booking_manager", $user->roles)) {
		wp_enqueue_style('admin_general_style', get_stylesheet_directory_uri() . '/assets/styles/admin.css' );
	}
});


/**
 * Scripts and styles
 */
add_action('wp_enqueue_scripts', function(){
	/* Booking styles */
	if (is_page(17346)) {
		wp_enqueue_style('bookingTableStyle', get_stylesheet_directory_uri() . '/assets/styles/booking-table.css' );
		wp_enqueue_script('bookingTableScript', get_stylesheet_directory_uri() . '/assets/scripts/booking-table.js', array('jquery'), null, true);
		wp_localize_script('bookingTableScript', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
	}

	/* Apartment styles */
	if(is_archive() && get_post_type() === "boliger"){
		wp_enqueue_script('apartmentsArchiveScript', get_stylesheet_directory_uri() . '/assets/scripts/archive.js', array('jquery'), null, true);
		wp_localize_script('apartmentsArchiveScript', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php'), "lang" => $_GET['lang'] ?? 'da'));
		wp_enqueue_style('apartmentArchiveStyle', get_stylesheet_directory_uri() . '/assets/styles/archive.css' );
	}
	else{
		wp_enqueue_script('apartmentsFilterScript', get_stylesheet_directory_uri() . '/assets/scripts/filters-link.js', array('jquery'), null, true);
		wp_localize_script('apartmentsFilterScript', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php'), "lang" => $_GET['lang'] ?? 'da'));
		wp_enqueue_style('apartmentFilterStyle', get_stylesheet_directory_uri() . '/assets/styles/filters-link.css' );
	}
});