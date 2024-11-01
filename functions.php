<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

// add action add client to DOALI
add_action( 'elementor_pro/init', function() {
$path = dirname( __FILE__ );
include_once( $path . '/api/EM_doali.php' );
$doali_action = new Doali_Action_After_Submit();
\ElementorPro\Plugin::instance()->modules_manager->get_modules( 'forms' )->add_form_action( $doali_action->get_name(), $doali_action );
});

// add action to add new client
add_action( 'elementor_pro/init', function() {
$path = dirname( __FILE__ );
include_once( $path . '/api/EM_whmcs.php' );
$whmcs_action = new WHMCS_Action_After_Submit();
\ElementorPro\Plugin::instance()->modules_manager->get_modules( 'forms' )->add_form_action( $whmcs_action->get_name(), $whmcs_action );
});

// add action to open ticket
add_action( 'elementor_pro/init', function() {
$path = dirname( __FILE__ );
include_once( $path . '/api/EM_wh_Tickets.php' );
$whtickets_action = new WH_Tickets_Action_After_Submit();
\ElementorPro\Plugin::instance()->modules_manager->get_modules( 'forms' )->add_form_action( $whtickets_action->get_name(), $whtickets_action );
});

// add action to add new order
add_action( 'elementor_pro/init', function() {
$path = dirname( __FILE__ );
include_once( $path . '/api/EM_add_Order.php' );
$whaddorder_action = new WH_AddOrder_Action_After_Submit();
\ElementorPro\Plugin::instance()->modules_manager->get_modules( 'forms' )->add_form_action( $whaddorder_action->get_name(), $whaddorder_action );
});

// add action to open amount
add_action( 'elementor_pro/init', function() {
$path = dirname( __FILE__ );
include_once( $path . '/api/EM_add_Open_Amount.php' );
$whaddopenamount_action = new WH_AddOpenAmount_Action_After_Submit();
\ElementorPro\Plugin::instance()->modules_manager->get_modules( 'forms' )->add_form_action( $whaddopenamount_action->get_name(), $whaddopenamount_action );
});
