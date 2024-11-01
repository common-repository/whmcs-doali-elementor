<?php
/**
 * Class WH_AddOrder_Action_After_Submit
 * @see https://developers.elementor.com/custom-form-action/
 * Custom elementor form action after submit to add a subsciber to
 * WHMCS apiIdentifier via API 
 */
class WH_AddOrder_Action_After_Submit extends \ElementorPro\Modules\Forms\Classes\Action_Base {
    /**
     * Get Name
     *
     * Return the action name
     *
     * @access public
     * @return string
     */
    public function get_name() {
        return 'whaddorder';
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
        return __( 'whaddorder', 'text-domain' );
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
        if ( empty( $settings['whaddorder_url'] ) ) {
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

        // which is required by WHMCS's API to subsribe a user
        if ( empty( $settings['whaddorder_email_field'] ) ) {
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
        if ( empty( $fields[ $settings['whaddorder_email_field'] ] ) ) {
            return;
        }

        // CONFIG # https://docs.whmcs.com/API_Authentication_Credentials
        $whmcsUrl = $settings['whaddorder_url']; // ENTER Your whmcs URL
        $apiIdentifier = $settings['whaddorder_apiIdentifier']; // ENTER Your apiIdentifier
        $apiSecret = $settings['whaddorder_apiSecret']; // ENTER Your apiSecret
        $pid = $settings['whaddorder_pid']; // ENTER Your pid
        $promocode = $settings['whaddorder_promocode']; // ENTER Your promocode
        $affid = $settings['whaddorder_affid']; // ENTER Your affid
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
        $subject = isset($_REQUEST['form_fields']['subject']) && !empty($_REQUEST['form_fields']['subject']) ? sanitize_text_field($_REQUEST['form_fields']['subject']) : 'Empty';
		$message = isset($_REQUEST['form_fields']['message']) && !empty($_REQUEST['form_fields']['message']) ? sanitize_text_field($_REQUEST['form_fields']['message']) : 'Empty';
		$product_group_id = 1;
		$product_id = 9;
		
		// Get the user id
		$results = wp_remote_post( $whmcsUrl . 'includes/api.php', [ 'body' => array(
		'action' => 'GetClientsDetails', 
		'username' => $apiIdentifier, 
		'password' => $apiSecret,
		'email' => $email,
		'responsetype' => 'json',
		), ]);

		$userid= -1;
		
		if ($results['body']){
			$body = json_decode($results['body']);
			if (isset($body->userid)){
				$userid = $body->userid;
			}	
		}

		// Add new order
        wp_remote_post( $whmcsUrl . 'includes/api.php', [
        'body' => array(
            'action' => 'AddOrder',
            'clientid' => $userid,
			//'email' => $email,
            'username' => $apiIdentifier,
            'password' => $apiSecret,
			'pid' => array($pid, ),
            'domain' => array($domain, ),
            'billingcycle' => array('monthly', ),
            'addons' => array('', ),
            'customfields' => array(base64_encode(serialize(array("1" => "Google"))), base64_encode(serialize(array("1" => "Google")))),
            'configoptions' => array(base64_encode(serialize(array("1" => 999))), base64_encode(serialize(array("1" => 999)))),
            'domaintype' => array('register',),
            'regperiod' => array(1,),
            'dnsmanagement' => array(1 => true,),
            'promocode' => $promocode,
            'affid' => $affid,
            'nameserver1' => 'ns1.emaillion.net',
            'nameserver2' => 'ns2.emaillion.net',
            'paymentmethod' => 'smartpayplus_redirect',
            'responsetype' => 'json',
        ),
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
            'section_whaddorder',
            [
                'label' => __( 'whaddorder', 'text-domain' ),
                'condition' => [
                    'submit_actions' => $this->get_name(),
                ],
            ]
        );

        $widget->add_control(
            'whaddorder_url',
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
            'whaddorder_apiIdentifier',
            [
                'label' => __( 'WHMCS Api Identifier', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'separator' => 'before',
                'description' => __( 'the apiIdentifier from WHMCS', 'text-domain' ),
            ]
        );

        $widget->add_control(
            'whaddorder_apiSecret',
            [
                'label' => __( 'WHMCS Api Sectet', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'separator' => 'before',
                'description' => __( 'the apiSectet from WHMCS', 'text-domain' ),
            ]
        );

        $widget->add_control(
            'whaddorder_pid',
            [
                'label' => __( 'WHMCS Pid', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'separator' => 'before',
                'description' => __( 'the pid', 'text-domain' ),
            ]
        );
		
		$widget->add_control(
            'whaddorder_promocode',
            [
                'label' => __( 'WHMCS promocode', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'separator' => 'before',
                'description' => __( 'the promocode', 'text-domain' ),
            ]
        );
		
		$widget->add_control(
            'whaddorder_affid',
            [
                'label' => __( 'WHMCS affid', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'separator' => 'before',
                'description' => __( 'the affid', 'text-domain' ),
            ]
        );

        $widget->add_control(
            'whaddorder_email_field',
            [
                'label' => __( 'Email Field ID', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::TEXT,
            ]
        );

        $widget->add_control(
            'whaddorder_name_field',
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
            $element['whaddorder_url'],
            $element['apiIdentifier'],
            $element['apiSecret'],
            $element['whaddorder_name_field'],
            $element['whaddorder_email_field']
        );
    }
}