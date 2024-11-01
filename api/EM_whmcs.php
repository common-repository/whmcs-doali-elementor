<?php
/**
 * Class WHMCS_Action_After_Submit
 * @see https://developers.elementor.com/custom-form-action/
 * Custom elementor form action after submit to add a subsciber to
 * WHMCS apiIdentifier via API 
 */
class WHMCS_Action_After_Submit extends \ElementorPro\Modules\Forms\Classes\Action_Base {
	/**
	 * Get Name
	 *
	 * Return the action name
	 *
	 * @access public
	 * @return string
	 */
	public function get_name() {
		return 'whmcs';
	}

	/**
	 * Get Label
	 *
	 * Returns the action label
	 *
	 * @access public
	 * @return string
	 */
	public function get_label() {
		return __( 'WHMCS', 'text-domain' );
	}

	/**
	 * Run
	 *
	 * Runs the action after submit
	 *
	 * @access public
	 * @param \ElementorPro\Modules\Forms\Classes\Form_Record $record
	 * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler
	 */
	public function run( $record, $ajax_handler ) {
		$settings = $record->get( 'form_settings' );

		//  Make sure that there is a WHMCS installation url
		if ( empty( $settings['whmcs_url'] ) ) {
			return;
		}

		//  Make sure that there is a WHMCS apiIdentifier ID
		if ( empty( $settings['apiIdentifier'] ) ) {
			return;
		}
		
		//  Make sure that there is a WHMCS apikey
		if ( empty( $settings['apiSecret'] ) ) {
			return;
		}

		// Make sure that there is a WHMCS Email field ID
		// which is required by WHMCS's API to subsribe a user
		if ( empty( $settings['whmcs_email_field'] ) ) {
			return;
		}

		// Get sumitetd Form data
		$raw_fields = $record->get( 'fields' );

		// Normalize the Form Data
		$fields = [];
		foreach ( $raw_fields as $id => $field ) {
			$fields[ $id ] = $field['value'];
		}

		// Make sure that the user emtered an email
		// which is required by WHMCS's API to subsribe a user
		if ( empty( $fields[ $settings['whmcs_email_field'] ] ) ) {
			return;
		}

		// CONFIG # https://docs.whmcs.com/API_Authentication_Credentials
		$whmcsUrl = $settings['whmcs_url']; // ENTER Your whmcs URL
		$apiIdentifier = $settings['apiIdentifier']; // ENTER Your apiIdentifier
		$apiSecret = $settings['apiSecret']; // ENTER Your apiSecret
	    $domain = isset($_REQUEST['form_fields']['domain']) && !empty($_REQUEST['form_fields']['domain']) ? sanitize_text_field($_REQUEST['form_fields']['domain']) : 'Empty';
	    $domaintype = isset($_REQUEST['form_fields']['domaintype']) && !empty($_REQUEST['form_fields']['domaintype']) ? sanitize_text_field($_REQUEST['form_fields']['domaintype']) : 'Empty';
	    $billingcycle = isset($_REQUEST['form_fields']['billingcycle']) && !empty($_REQUEST['form_fields']['billingcycle']) ? sanitize_text_field($_REQUEST['form_fields']['billingcycle']) : 'Empty';
	    $firstname = isset($_REQUEST['form_fields']['firstname']) && !empty($_REQUEST['form_fields']['firstname']) ? sanitize_text_field($_REQUEST['form_fields']['firstname']) : 'Empty';
	    $lastname = isset($_REQUEST['form_fields']['lastname']) && !empty($_REQUEST['form_fields']['lastname']) ? sanitize_text_field($_REQUEST['form_fields']['lastname']) : 'Empty';
	    $companyname = isset($_REQUEST['form_fields']['companyname']) && !empty($_REQUEST['form_fields']['companyname']) ? sanitize_text_field($_REQUEST['form_fields']['companyname']) : 'Empty';
	    $email = isset($_REQUEST['form_fields']['email']) && !empty($_REQUEST['form_fields']['email']) ? sanitize_email($_REQUEST['form_fields']['email']) : 'Empty';
	    $address1 = isset($_REQUEST['form_fields']['address1']) && !empty($_REQUEST['form_fields']['address1']) ? sanitize_text_field($_REQUEST['form_fields']['address1']) : 'Empty';
	    $address2 = isset($_REQUEST['form_fields']['address2']) && !empty($_REQUEST['form_fields']['address2']) ? sanitize_text_field($_REQUEST['form_fields']['address2']) : 'Empty';
	    $city = isset($_REQUEST['form_fields']['city']) && !empty($_REQUEST['form_fields']['city']) ? sanitize_text_field($_REQUEST['form_fields']['city']) : 'Empty';
	    $state = isset($_REQUEST['form_fields']['state']) && !empty($_REQUEST['form_fields']['state']) ? sanitize_text_field($_REQUEST['form_fields']['state']) : 'Empty';
	    $postcode = isset($_REQUEST['form_fields']['postcode']) && !empty($_REQUEST['form_fields']['postcode']) ? sanitize_text_field($_REQUEST['form_fields']['postcode']) : 'Empty';
	    $country = isset($_REQUEST['form_fields']['country']) && !empty($_REQUEST['form_fields']['country']) ? sanitize_text_field($_REQUEST['form_fields']['country']) : 'Empty';
	    $phonenumber = isset($_REQUEST['form_fields']['phonenumber']) && !empty($_REQUEST['form_fields']['phonenumber']) ? sanitize_text_field($_REQUEST['form_fields']['phonenumber']) : 'Empty';
	    $password = isset($_REQUEST['form_fields']['password']) && !empty($_REQUEST['form_fields']['password']) ? sanitize_text_field($_REQUEST['form_fields']['password']) : 'Empty';
	    $password2 = isset($_REQUEST['form_fields']['password2']) && !empty($_REQUEST['form_fields']['password2']) ? sanitize_text_field($_REQUEST['form_fields']['password2']) : 'Empty';
	    $promocode = isset($_REQUEST['form_fields']['promocode']) && !empty($_REQUEST['form_fields']['promocode']) ? sanitize_text_field($_REQUEST['form_fields']['promocode']) : 'Empty';
		
		$postfields = array(
		'identifier' => $apiIdentifier, 
		'secret' => $apiSecret, 
		'action' => 'AddClient',
		'firstname' => $firstname,
		'lastname' => $lastname,
		'email' => $email,
		'companyname' => $companyname,
		'address1' => $address1,
		'address2' => $address2,
		'city' => $city,
		'state' => $state,
		'postcode' => $postcode,
		'country' => $country,
		'phonenumber' => $phonenumber,
		'password' => $password,
		'password2' => $password2,
		'clientip' => \ElementorPro\Classes\Utils::get_client_ip(),
		'responsetype' => 'json', 
		); 

		wp_remote_post( $whmcsUrl . 'includes/api.php', [
		'body' => $postfields,
		 ]);
	}
	/**
	 * Register Settings Section
	 *
	 * Registers the Action controls
	 *
	 * @access public
	 * @param \Elementor\Widget_Base $widget
	 */
	public function register_settings_section( $widget ) {
		$widget->start_controls_section(
			'section_whmcs',
			[
				'label' => __( 'WHMCS', 'text-domain' ),
				'condition' => [
					'submit_actions' => $this->get_name(),
				],
			]
		);

		$widget->add_control(
			'whmcs_url',
			[
				'label' => __( 'WHMCS URL', 'text-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => 'http://your_whmcs_path/',
				'label_block' => true,
				'separator' => 'before',
				'description' => __( 'Enter the URL where you have WHMCS installed', 'text-domain' ),
			]
		);

		$widget->add_control(
			'apiIdentifier',
			[
				'label' => __( 'WHMCS Api Identifier', 'text-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'separator' => 'before',
				'description' => __( 'the apiIdentifier from WHMCS', 'text-domain' ),
			]
		);

		$widget->add_control(
			'apiSecret',
			[
				'label' => __( 'WHMCS Api Sectet', 'text-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'separator' => 'before',
				'description' => __( 'the apiSectet from WHMCS', 'text-domain' ),
			]
		);

		$widget->add_control(
			'whmcs_email_field',
			[
				'label' => __( 'Email Field ID', 'text-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			]
		);

		$widget->add_control(
			'whmcs_name_field',
			[
				'label' => __( 'Name Field ID', 'text-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			]
		);

		$widget->end_controls_section();

	}

	/**
	 * On Export
	 *
	 * Clears form settings on export
	 * @access Public
	 * @param array $element
	 */
	public function on_export( $element ) {
		unset(
			$element['whmcs_url'],
			$element['apiIdentifier'],
			$element['apiSecret'],
			$element['whmcs_name_field'],
			$element['whmcs_email_field']
		);
	}
}