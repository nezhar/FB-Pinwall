<?php
defined('ABSPATH') or die("No script kiddies please!");

class MySettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'FB Pinwall', 
            'manage_options', 
            'fb-pinwall-settings', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'fb-pinwall-options' );
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2>FB Pinwall</h2>           
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'fb-pinwall-option' );   
                do_settings_sections( 'fb-pinwall-settings' );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'fb-pinwall-option', // Option group
            'fb-pinwall-options', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'fb-api-settings', // ID
            'FB API v2.x Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'fb-pinwall-settings' // Page
        );  

        add_settings_field(
            'facebook_app_key', // ID
            'Facebook App Key', // Title 
            array( $this, 'facebook_app_key_callback' ), // Callback
            'fb-pinwall-settings', // Page
            'fb-api-settings' // Section           
        );      

        add_settings_field(
            'facebook_app_secret', 
            'Facebook App Secret', 
            array( $this, 'facebook_app_secret_callback' ), 
            'fb-pinwall-settings', 
            'fb-api-settings'
        );

        add_settings_field(
            'facebook_page', 
            'Facebook Page for Feed', 
            array( $this, 'facebook_page_callback' ), 
            'fb-pinwall-settings', 
            'fb-api-settings'
        );

        add_settings_field(
            'num_posts', 
            'Number of Posts from Feed', 
            array( $this, 'num_posts_callback' ), 
            'fb-pinwall-settings', 
            'fb-api-settings'
        );    
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['facebook_app_key'] ) )
            $new_input['facebook_app_key'] = absint( $input['facebook_app_key'] );

        if( isset( $input['facebook_app_secret'] ) )
            $new_input['facebook_app_secret'] = sanitize_text_field( $input['facebook_app_secret'] );

        if( isset( $input['facebook_page'] ) )
            $new_input['facebook_page'] = sanitize_text_field( $input['facebook_page'] );

        if( isset( $input['num_posts'] ) )
            $new_input['num_posts'] = sanitize_text_field( $input['num_posts'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        echo 'This Setting are used to define the connection to the facebook API in order to access a facebook page feed.';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function facebook_app_key_callback()
    {
        printf(
            '<input type="text" id="facebook_app_key" name="fb-pinwall-options[facebook_app_key]" value="%s" />',
            isset( $this->options['facebook_app_key'] ) ? esc_attr( $this->options['facebook_app_key']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function facebook_app_secret_callback()
    {
        printf(
            '<input type="text" id="facebook_app_secret" name="fb-pinwall-options[facebook_app_secret]" value="%s" />',
            isset( $this->options['facebook_app_secret'] ) ? esc_attr( $this->options['facebook_app_secret']) : ''
        );
    }

    public function facebook_page_callback()
    {
        printf(
            '<input type="text" id="facebook_page" name="fb-pinwall-options[facebook_page]" value="%s" />',
            isset( $this->options['facebook_page'] ) ? esc_attr( $this->options['facebook_page']) : ''
        );
    }

    public function num_posts_callback()
    {
        printf(
            '<input type="text" id="num_posts" name="fb-pinwall-options[num_posts]" value="%s" />',
            isset( $this->options['num_posts'] ) ? esc_attr( $this->options['num_posts']) : ''
        );
    }
}

if( is_admin() )
    $my_settings_page = new MySettingsPage();