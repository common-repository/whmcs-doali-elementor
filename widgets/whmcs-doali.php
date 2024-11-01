<?php
namespace ElementorWhmcsDoali\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Whmcs Doali to Elementor
 *
 * Elementor widget for whmcs doali.
 *
 * @since 1.0.0
 */
class Whmcs_Doali extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'whmcs-doali';
	}

	/**
	 * Retrieve the widget whmcsurl.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget whmcsurl.
	 */
	public function get_title() {
		return __( 'Whmcs Doali', 'whmcs-doali-to-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-posts-ticker';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'general' ];
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'whmcs-doali-to-elementor' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'whmcs-doali-to-elementor' ),
			]
		);

		$this->add_control(
			'whmcsurl',
			[
				'label' => __( 'WhmcsUrl', 'whmcs-doali-to-elementor' ),
				'type' => Controls_Manager::TEXT,
			]
		);
		$this->add_control(
			'identifier',
			[
				'label' => __( 'identifier', 'whmcs-doali-to-elementor' ),
				'type' => Controls_Manager::TEXT,
			]
		);
		$this->add_control(
			'secret',
			[
				'label' => __( 'secret', 'whmcs-doali-to-elementor' ),
				'type' => Controls_Manager::TEXT,
			]
		);
		$this->add_control(
			'formname',
			[
				'label' => __( 'formname', 'whmcs-doali-to-elementor' ),
				'type' => Controls_Manager::TEXT,
			]
		);		

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Style', 'whmcs-doali-to-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'text_transform',
			[
				'label' => __( 'Text Transform', 'whmcs-doali-to-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => __( 'None', 'whmcs-doali-to-elementor' ),
					'uppercase' => __( 'UPPERCASE', 'whmcs-doali-to-elementor' ),
					'lowercase' => __( 'lowercase', 'whmcs-doali-to-elementor' ),
					'capitalize' => __( 'Capitalize', 'whmcs-doali-to-elementor' ),
				],
				'selectors' => [
					'{{WRAPPER}} .whmcsurl' => 'text-transform: {{VALUE}};',
					'{{WRAPPER}} .identifier' => 'text-transform: {{VALUE}};',
					'{{WRAPPER}} .secret' => 'text-transform: {{VALUE}};',
					'{{WRAPPER}} .formname' => 'text-transform: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		echo '<div class="whmcsurl">';
		echo $settings['whmcsurl'];
		echo '</div>';
		
		echo '<div class="identifier">';
		echo $settings['identifier'];
		echo '</div>';
		
		echo '<div class="secret">';
		echo $settings['secret'];
		echo '</div>';
		
		echo '<div class="formname">';
		echo $settings['formname'];
		echo '</div>';
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _content_template() {
		?>
		<div class="whmcsurl">
			{{{ settings.whmcsurl }}}
		</div>
		<div class="identifier">
			{{{ settings.identifier }}}
		</div>
		<div class="secret">
			{{{ settings.secret }}}
		</div>
		<div class="formname">
			{{{ settings.formname }}}
		</div>		
		
		<?php
	}
}
