<?php
namespace fbpw;

class SettingsPages
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
    public function sanitize($input)
    {
        $newInput = array();
        if( isset( $input['facebook_app_key'] ) )
            $newInput['facebook_app_key'] = absint( $input['facebook_app_key'] );

        if( isset( $input['facebook_app_secret'] ) )
            $newInput['facebook_app_secret'] = sanitize_text_field( $input['facebook_app_secret'] );

        if( isset( $input['facebook_page'] ) )
            $newInput['facebook_page'] = sanitize_text_field( $input['facebook_page'] );

        if( isset( $input['num_posts'] ) )
            $newInput['num_posts'] = sanitize_text_field( $input['num_posts'] );

        return $newInput;
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

class FbFeed
{
    private $appId;
    private $appSecret;
    private $fbPages;
    private $numPost;
    private $feed;

    public function __construct($appId, $appSecret, $fbPages, $numPosts) {
        if (!$appId) throw new \Exception("Invalid app App ID");
        if (!$appSecret) throw new \Exception("Invalid App Secret");
        if (!$fbPages) throw new \Exception("Undefined pages for feed");
        if (!$numPosts || $numPosts < 0) throw new \Exception("Numpost must be greater then 0");

        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->fbPages = $fbPages;
        $this->numPosts = $numPosts;
        $this->feed = $this->feedData();
    }

    public function getHtml() {
        return $this->htmlFromFeed();
    }

    /**
     * Calls the Facebook API, generates an acces token, gets image feeds foreach profile id
     * Loops throw images, creates thumnails for new images and creates an array of all images
     *
     * @return array
     */
    private function feedData() {
    	//FB Pages for feed
    	$profileIds = explode(",", $this->fbPages);

    	//retrieve auth token
    	$authToken = @file_get_contents("https://graph.facebook.com/oauth/access_token?type=client_cred&client_id={$this->appId}&client_secret={$this->appSecret}");
        if ($authToken === false) {
            throw new Exception("Could not create auth token. APP_ID or APP_SECRET might not be valid");
        }

    	$feed = array();

    	foreach ($profileIds as $profileId) {
    		//retrive data
    		$data = @file_get_contents("https://graph.facebook.com/{$profileId}/photos/uploaded?{$authToken}&limit=".$this->numPosts);
            if ($data === false) {
                trigger_error("Could not fetch data. Invalid profile Id: {$profileId}", E_USER_NOTICE);
            } else {
                $images = json_decode($data);
        		foreach ($images->data as $image) {

        			$wp_upload_dir = wp_upload_dir('fbpw');

        			//Create a custom Thumbnail
        			$thumb = 'thumb_'.md5($image->images[0]->source).".".fbpw_get_extension_from_url($image->images[0]->source);
        			if (!file_exists($wp_upload_dir['path']."/".$thumb)) {
        				fbpw_make_thumb($image->images[0]->source, $wp_upload_dir['path']."/".$thumb, 250);
        			}

        			$feed[] = array(
        				'title' => $image->from->name,
        				'thumb' => $wp_upload_dir['url']."/".$thumb,
        				'src' => $image->images[0]->source,
        				'created' => strtotime($image->created_time),
        			);
        		}
            }
    	}

    	return $feed;
    }

    /**
     * Converts the feed to HTML
     */
    private function htmlFromFeed() {

    	$output = "<div class='fbpw'>";

    	foreach ($this->feed as $data) {
    		$output .= "<a href='{$data['src']}' class='fbpw-image'>";
    		$output .= "<img src='{$data['thumb']}'>";
    		$output .= "</a>";
    	}

    	$output .= "</div> <div style='clear:both;'></div>";

    	return $output;
    }
}
