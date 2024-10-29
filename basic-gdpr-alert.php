<?php
/**
 * Plugin Name: Basic GDPR Alert
 * Description: Basic GDPR Alert, adding GDPR alert to your site.
 * Version: 1.0.1
 * Author: J4
 * Author URI: https://j4cob.net
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: basic-gdpr-alert
 * Domain Path: /languages
 * 
 * Basic GDPR Alert is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 * 
 * Basic GDPR Alert is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'BASIC_GDPR_ALERT_VERSION', '1.0.1' );
define( 'BASIC_GDPR_ALERT_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'BASIC_GDPR_ALERT_DIR_PATH', plugin_dir_path( __FILE__ ) );


if ( class_exists( 'BasicGdprAlert' ) ){
    $basic_gdpr_alert = new BasicGdprAlert;
}

class BasicGdprAlert{

    public function __construct() {
        $this->includes();
        $this->_hooks();
    }

    function _hooks(){
        add_action( 'wp_enqueue_scripts', array( $this, 'add_theme_scripts' ) );
        add_action( 'wp_footer', array($this, 'basic_gdpr_alert' ) );
        add_action( 'wp_footer', array($this, 'basic_gdpr_alert_customize_css') );
        add_action( 'customize_register', array($this, 'basic_gdpr_alert_customize_register' ) );
        add_filter( 'plugin_action_links', array($this, 'basic_gdpr_alert_action_links' ), 10, 2 );
    } 
    
    function includes(){
        
    }

    // enqueue scripts
    function add_theme_scripts() {
        wp_enqueue_style( 'basic-gdpr-alert-style', BASIC_GDPR_ALERT_DIR_URL . 'inc/style.css', array(), BASIC_GDPR_ALERT_VERSION, 'all');
        wp_enqueue_script( 'basic-gdpr-alert-js', BASIC_GDPR_ALERT_DIR_URL . 'inc/main.js', array(), BASIC_GDPR_ALERT_VERSION, true);
    }

    // Basic GDPR Alert
    function basic_gdpr_alert() {
        ?>
        <div id="basic-gdpr-alert" <?php if (is_customize_preview()) echo 'class="basic-gdpr-alert-preview"'; ?>>

            <?php echo get_option('basic_gdpr_alert_content', 'We use the cookies.'); ?>

            <div class="basic-gdpr-alert-buttons">
                <button class="basic-gdpr-alert-button"><?php echo get_option( 'basic_gdpr_alert_button_text', 'Accept' ); ?></button>
            </div>
            
    
        </div>
    <?php       
        
    } 
    // customizer api
    function basic_gdpr_alert_customize_register( $wp_customize ) {

        if(!class_exists('TinyMCE_Custom_control')){
            require_once(BASIC_GDPR_ALERT_DIR_PATH . 'inc/tinymce/tinymce.php');
        }

        $wp_customize->add_section( 'basic_gdpr_alert', array(
            'title' => esc_html__( 'Basic GDPR Alert', 'basic-gdpr-alert' ),
            'priority' => 990,
        ));  

                $wp_customize->add_setting( 'basic_gdpr_alert_content', array(
                    'default' => 'We use the cookies.',
                    'transport' => 'refresh',
                    'sanitize_callback' => 'wp_kses_post',
                    'type' => 'option',
                ));

                $wp_customize->add_control( new TinyMCE_Custom_control( $wp_customize, 'basic_gdpr_alert_content', array(
                    'label' => esc_html__( 'Cookie Preview Text', 'basic-gdpr-alert' ),
                    'description' => __( 'Write cookie alert message.', 'basic-gdpr-alert' ),
                    'section' => 'basic_gdpr_alert',
                    'input_attrs' => array(
                        'toolbar1' => 'bold italic underline bullist numlist alignleft aligncenter alignright link',
                        'mediaButtons' => false,
                ))));    

                $wp_customize->add_setting( 'basic_gdpr_alert_button_text' , array(
                    'default' => 'Accept',
                    'type' => 'option',
                ));
                
                $wp_customize->add_control('basic_gdpr_alert_button_text', array(
                    'label' => esc_html__( 'Accept Button Text', 'basic_gdpr_alert' ), 
                    'section' => 'basic_gdpr_alert',
                    'settings' => 'basic_gdpr_alert_button_text',
                    'type' => 'text',
                ));                  

                $wp_customize->add_setting( 'basic_gdpr_alert_textColor' , array(
                    'default' => '#000000',
                    'type' => 'option',
                ));

                $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'basic_gdpr_alert_textColor', array(
                    'label' => esc_html__( 'Text Color', 'basic-gdpr-alert' ),
                    'section' => 'basic_gdpr_alert',
                    'settings' => 'basic_gdpr_alert_textColor',
                )));

                $wp_customize->add_setting( 'basic_gdpr_alert_bgColor' , array(
                    'default' => '#ffffff',
                    'type' => 'option',
                ));

                $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'basic_gdpr_alert_bgColor', array(
                    'label' => esc_html__( 'Background Color', 'basic-gdpr-alert' ),
                    'section' => 'basic_gdpr_alert',
                    'settings' => 'basic_gdpr_alert_bgColor',
                )));

                $wp_customize->add_setting( 'basic_gdpr_alert_fontSize' , array(
                    'default' => '12',
                    'type' => 'option',
                ));
                
                $wp_customize->add_control('basic_gdpr_alert_fontSize', array(
                    'label'    => esc_html__( 'Font Size (px)', 'basic-gdpr-alert' ), 
                    'section'  => 'basic_gdpr_alert',
                    'settings' => 'basic_gdpr_alert_fontSize',
                    'type' => 'number',
                ));  

                $wp_customize->add_setting( 'basic_gdpr_alert_width' , array(
                    'default' => '255',
                    'type' => 'option',
                ));
                
                $wp_customize->add_control('basic_gdpr_alert_width', array(
                    'label'    => esc_html__( 'Content Width (px)', 'basic-gdpr-alert' ), 
                    'section'  => 'basic_gdpr_alert',
                    'settings' => 'basic_gdpr_alert_width',
                    'type' => 'number',
                ));  

                $wp_customize->add_setting( 'basic_gdpr_alert_zindex' , array(
                    'default' => '9999',
                    'type' => 'option',
                ));
                
                $wp_customize->add_control('basic_gdpr_alert_zindex', array(
                    'label'    => __( 'Z-Index', 'basic-gdpr-alert' ), 
                    'description' => esc_html__( 'Recommended value: 9999', 'basic_gdpr_alert' ),
                    'section'  => 'basic_gdpr_alert',
                    'settings' => 'basic_gdpr_alert_zindex',
                    'type' => 'number',
                ));  
       

    }

    // plugin action links
    function basic_gdpr_alert_action_links( $links_array, $plugin_file_name ){
        if( strpos( $plugin_file_name, basename(__FILE__) ) ) {
            array_unshift( $links_array, '<a href="' . admin_url( 'customize.php?autofocus[section]=basic_gdpr_alert' ) . '">Settings</a>' );
        }
        return $links_array;
    }

    // customize css
    function basic_gdpr_alert_customize_css(){
        ?>
        <style type="text/css">

            :root{
                --basic-gdpr-alert-bg-color: <?php echo get_option( 'basic_gdpr_alert_bgColor', '#ffffff' );?>;
                --basic-gdpr-alert-text-color: <?php echo get_option( 'basic_gdpr_alert_textColor', '#000000' );?>;
                --basic-gdpr-alert-z-index: <?php echo get_option( 'basic_gdpr_alert_zindex', '9999' );?>;
                --basic-gdpr-alert-font-size: <?php echo get_option( 'basic_gdpr_alert_fontSize', '12' );?>px;
                --basic-gdpr-alert-width: <?php echo get_option( 'basic_gdpr_alert_width', '255' );?>px;
                --basic-gdpr-alert-radius: 4px;
            }

        </style>
        <?php
    }
    
} // class



function basicgdpralert_pluginprefix_activate() { 
    flush_rewrite_rules(); 
}
register_activation_hook( __FILE__, 'basicgdpralert_pluginprefix_activate' );

function basicgdpralert_pluginprefix_deactivate() {
    /* 
    delete_option(' basic_gdpr_alert_content' );
    delete_option(' basic_gdpr_alert_button_text' );
    delete_option(' basic_gdpr_alert_textColor' );
    delete_option(' basic_gdpr_alert_bgColor' );
    delete_option(' basic_gdpr_alert_fontSize' );
    delete_option(' basic_gdpr_alert_width' );
    delete_option(' basic_gdpr_alert_width' );
    delete_option(' basic_gdpr_alert_zindex' );
    */
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'basicgdpralert_pluginprefix_deactivate' );