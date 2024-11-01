<?php
/**
 * Class Doali_Action_After_Submit
 * @see https://developers.elementor.com/custom-form-action/
 * Custom elementor form action after submit to add a subsciber to
 * Doali listName via API 
 */
class Doali_Action_After_Submit extends \ElementorPro\Modules\Forms\Classes\Action_Base {
	/**
	 * Get Name
	 *
	 * Return the action name
	 *
	 * @access public
	 * @return string
	 */
	public function get_name() {
		return 'doali';
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
		return __( 'Doali', 'text-domain' );
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

		//  Make sure that there is a Doali installation url
		if ( empty( $settings['doali_url'] ) ) {
			return;
		}

		//  Make sure that there is a Doali listName ID
		if ( empty( $settings['doali_listName'] ) ) {
			return;
		}
		
		//  Make sure that there is a Doali apikey
		if ( empty( $settings['publicAccountID'] ) ) {
			return;
		}

		// Make sure that there is a Doali Email field ID
		// which is required by Doali's API to subsribe a user
		if ( empty( $settings['doali_email_field'] ) ) {
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
		// which is required by Doali's API to subsribe a user
		if ( empty( $fields[ $settings['doali_email_field'] ] ) ) {
			return;
		}

		$firstname = isset($_REQUEST['form_fields']['firstname']) && !empty($_REQUEST['form_fields']['firstname']) ? sanitize_text_field($_REQUEST['form_fields']['firstname']) : '';
	    $lastname = isset($_REQUEST['form_fields']['lastname']) && !empty($_REQUEST['form_fields']['lastname']) ? sanitize_text_field($_REQUEST['form_fields']['lastname']) : '';
	    $phone = isset($_REQUEST['form_fields']['phone']) && !empty($_REQUEST['form_fields']['phone']) ? sanitize_text_field($_REQUEST['form_fields']['phone']) : '';
	    $company = isset($_REQUEST['form_fields']['company']) && !empty($_REQUEST['form_fields']['company']) ? sanitize_text_field($_REQUEST['form_fields']['company']) : '';
	    $city = isset($_REQUEST['form_fields']['city']) && !empty($_REQUEST['form_fields']['city']) ? sanitize_text_field($_REQUEST['form_fields']['city']) : '';
	    $mobile = isset($_REQUEST['form_fields']['mobile']) && !empty($_REQUEST['form_fields']['mobile']) ? sanitize_text_field($_REQUEST['form_fields']['mobile']) : '';
	    $birthday = isset($_REQUEST['form_fields']['birthday']) && !empty($_REQUEST['form_fields']['birthday']) ? sanitize_text_field($_REQUEST['form_fields']['birthday']) : '';
		// If we got this far we can start building our request data
		// Based on the param listName at https://doali.com api
		$doali_data = [
			'email' => $fields[ $settings['doali_email_field'] ],
			'listName' => $settings['doali_listName'],
			'publicAccountID' => $settings['publicAccountID'],
			'consentIP' => \ElementorPro\Classes\Utils::get_client_ip(),
			'sourceUrl' => isset( $_POST['referrer'] ) ? $_POST['referrer'] : '',
			'sendActivation' => 'false',
			'firstName' => $firstname,
			'lastName' => $lastname,
			'field_phone' => $phone,
			'field_company' => $company,
			'field_city' => $city,
			'field_mobile' => $mobile,
			'field_birthday' => $birthday,
		];

		// add firstName if field is mapped
		//if (!empty( $fields[ $settings['doali_name_field'] ] ) ) {
		//	$doali_data['firstName'] = $fields[ $settings['doali_name_field'] ];
		//}

		// Send the request
		wp_remote_post( $settings['doali_url'], [
			'body' => $doali_data,
		] );
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
			'section_doali',
			[
				'label' => __( 'Doali', 'text-domain' ),
				'condition' => [
					'submit_actions' => $this->get_name(),
				],
			]
		);

		$widget->add_control(
			'doali_url',
			[
				'label' => __( 'Doali URL', 'text-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => 'https://api.doali.com/v2/contact/add',
				'label_block' => true,
				'separator' => 'before',
				'description' => __( 'Enter the URL to the API action: https://api.doali.com/v2/contact/add', 'text-domain' ),
			]
		);

		$widget->add_control(
			'doali_listName',
			[
				'label' => __( 'Doali List Name', 'text-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'separator' => 'before',
				'description' => __( 'the listName name you want to subscribe a user to.', 'text-domain' ),
			]
		);

		$widget->add_control(
			'publicAccountID',
			[
				'label' => __( 'Doali apikey', 'text-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'separator' => 'before',
				'description' => __( 'the apikey you have in smtp-api setting section in doali', 'text-domain' ),
			]
		);

		$widget->add_control(
			'doali_email_field',
			[
				'label' => __( 'Email Field ID', 'text-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			]
		);

		$widget->add_control(
			'doali_name_field',
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
			$element['doali_url'],
			$element['doali_listName'],
			$element['publicAccountID'],
			$element['doali_name_field'],
			$element['doali_email_field']
		);
	}
}