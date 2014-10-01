<?php
/**
 * Mind Body
 *
 * @package   mind-body
 * @author    C.J.Churchill <churchill.c.j@gmail.com>
 * @license   GPL-2.0+
 * @link      http://accruemarketing.com/
 * @copyright 4-29-2014 Accrue
 */

/**
 * Mind Body class.
 *
 * @package MindBody
 * @author  C.J.Churchill <churchill.c.j@gmail.com>
 */
class MindBody{
	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $version = "1.0.0";

	/**
	 * Unique identifier for your plugin.
	 *
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = "mind-body";

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = "toplevel_page_mind-body";

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
		// Load plugin text domain
		add_action("init", array($this, "load_plugin_textdomain"));

		// Add the options page and menu item.
		add_action("admin_menu", array($this, "add_plugin_admin_menu"));

		// Load admin style sheet and JavaScript.
		add_action("admin_enqueue_scripts", array($this, "enqueue_admin_styles"));
		add_action("admin_enqueue_scripts", array($this, "enqueue_admin_scripts"));
		//check dependant plugins are active
		add_action( 'checkwoocommerce',array($this, "my_admin_notice"));

		// Load public-facing style sheet and JavaScript.
		add_action("wp_enqueue_scripts", array($this, "enqueue_styles"));
		add_action("wp_enqueue_scripts", array($this, "enqueue_scripts"));

		// Define custom functionality. Read more about actions and filters: http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		add_action("TODO", array($this, "action_method_name"));
		add_filter("TODO", array($this, "filter_method_name"));
		//register settings
		add_action( 'admin_init', array( $this, 'page_init' ) );
		//add shortcode
		add_shortcode( 'mindbodyclasses', array($this, 'mind_body_shrt'));
        add_shortcode( 'mindbodyeventscal', array($this, 'mind_body_shrt_cal'));
		add_shortcode( 'mindbodyeventscalwid', array($this, 'mind_body_shrt_cal_wid'));

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn"t been set, set it now.
		if (null == self::$instance) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public static function activate($network_wide) {
		// TODO: Define activation functionality here
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
	 */
	public static function deactivate($network_wide) {
		// TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters("plugin_locale", get_locale(), $domain);

		load_textdomain($domain, WP_LANG_DIR . "/" . $domain . "/" . $domain . "-" . $locale . ".mo");
		load_plugin_textdomain($domain, false, dirname(plugin_basename(__FILE__)) . "/lang/");
	}

    /**
     * Register and add settings
     */
    public function page_init(){ 
    	//Default Mindbody api settings       
        register_setting(
            'mind_body_group', // Option group
            'mind_body_options', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'MindBody API Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'mind-body-admin' // Page
        );  

        add_settings_field(
            'sourcename', // ID
            'Source Name', // Title 
            array( $this, 'sourcename_callback' ), // Callback
            'mind-body-admin', // Page
            'setting_section_id' // Section           
        );

        add_settings_field(
            'password', // ID
            'Password', // Title 
            array( $this, 'password_callback' ), // Callback
            'mind-body-admin', // Page
            'setting_section_id' // Section           
        );
        add_settings_field(
            'siteID', // ID
            'site ID', // Title 
            array( $this, 'siteID_callback' ), // Callback
            'mind-body-admin', // Page
            'setting_section_id' // Section           
        );
        add_settings_field(
            'Username', // ID
            'Username', // Title 
            array( $this, 'Username_callback' ), // Callback
            'mind-body-admin', // Page
            'setting_section_id' // Section           
        ); 
        add_settings_field(
            'Passworduser', // ID
            'Passworduser', // Title 
            array( $this, 'Passworduser_callback' ), // Callback
            'mind-body-admin', // Page
            'setting_section_id' // Section           
        );                       
        //notice setting
        register_setting(
            'mind_body_group_plugins', // Option group
            'mind_body_options_plugins', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'plugins_depnd', // ID
            '', // Title
            array( $this, 'print_section_info_hide' ), // Callback
            'mind-body-admin_plugins' // Page
        );  

        add_settings_field(
            'hidenotice', // ID
            'Hide this notice', // Title 
            array( $this, 'hidenotice_callback' ), // Callback
            'mind-body-admin_plugins', // Page
            'plugins_depnd' // Section           
        );
        //Import date settings
        register_setting(
            'mind_body_group_import', // Option group
            'mind_body_options_import', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'plugins_import', // ID
            '', // Title
            array( $this, 'print_section_info_dates' ), // Callback
            'mind-body-admin_import' // Page
        );  

        add_settings_field(
            'importfromdate', // ID
            'From date:', // Title 
            array( $this, 'importfromdate_callback' ), // Callback
            'mind-body-admin_import', // Page
            'plugins_import' // Section           
        );
        add_settings_field(
            'importtodate', // ID
            'To date:', // Title 
            array( $this, 'importtodate_callback' ), // Callback
            'mind-body-admin_import', // Page
            'plugins_import' // Section           
        );

    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input ){
        $new_input = array();
        if( isset( $input['sourcename'] ) )
            $new_input['sourcename'] =  $input['sourcename'];
        if( isset( $input['password'] ) )
            $new_input['password'] = $input['password'];
        if( isset( $input['Username'] ) )
            $new_input['Username'] = $input['Username'];
        if( isset( $input['Passworduser'] ) )
            $new_input['Passworduser'] = $input['Passworduser'];
        if( isset( $input['importtodate'] ) )
            $new_input['importtodate'] = $input['importtodate'];
        if( isset( $input['importfromdate'] ) )
            $new_input['importfromdate'] = $input['importfromdate'];                                
        if( isset( $input['siteID'] ) )
            $new_input['siteID'] = absint( $input['siteID'] );
        if( isset( $input['hidenotice'] ) )
            $new_input['hidenotice'] = absint( $input['hidenotice'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    
    public function print_section_info_hide(){
    }
    public function print_section_info_dates(){
    }    
    public function print_section_info(){
        print 'Enter your Mindbody API setting below :';
    }
    /** 
     * Get the settings option array and print one of its values
     */
    public function importtodate_callback(){
        printf(
            '<input type="date" id="importtodate" name="mind_body_options_import[importtodate]" value="%s" />',
            isset( $this->options['importtodate'] ) ? esc_attr( $this->options['importtodate']) : ''
        );
    }
    /** 
     * Get the settings option array and print one of its values
     */
    public function importfromdate_callback(){
        printf(
            '<input type="date" id="importfromdate" name="mind_body_options_import[importfromdate]" value="%s" />',
            isset( $this->options['importfromdate'] ) ? esc_attr( $this->options['importfromdate']) : ''
        );
    }    
    /** 
     * Get the settings option array and print one of its values
     */
    public function hidenotice_callback(){
        printf(
            '<input type="hidden" id="hidenotice" name="mind_body_options_plugins[hidenotice]" value="%s" />',
            isset( $this->options['hidenotice'] ) ? esc_attr( $this->options['hidenotice']) : ''
        );
    }
    /** 
     * Get the settings option array and print one of its values
     */
    public function sourcename_callback(){
        printf(
            '<input type="text" id="sourcename" name="mind_body_options[sourcename]" value="%s" />',
            isset( $this->options['sourcename'] ) ? esc_attr( $this->options['sourcename']) : ''
        );
    }
     /** 
     * Get the settings option array and print one of its values
     */
    public function password_callback(){
        printf(
            '<input type="text" id="password" name="mind_body_options[password]" value="%s" />',
            isset( $this->options['password'] ) ? esc_attr( $this->options['password']) : ''
        );
    }
    /** 
     * Get the settings option array and print one of its values
     */
    public function siteID_callback(){
        printf(
            '<input type="text" id="siteID" name="mind_body_options[siteID]" value="%s" />',
            isset( $this->options['siteID'] ) ? esc_attr( $this->options['siteID']) : ''
        );
    }
    /** 
     * Get the settings option array and print one of its values
     */
    public function Username_callback(){
        printf(
            '<input type="text" id="Username" name="mind_body_options[Username]" value="%s" />',
            isset( $this->options['Username'] ) ? esc_attr( $this->options['Username']) : ''
        );
    }
    /** 
     * Get the settings option array and print one of its values
     */
    public function Passworduser_callback(){
        printf(
            '<input type="text" id="Passworduser" name="mind_body_options[Passworduser]" value="%s" />',
            isset( $this->options['Passworduser'] ) ? esc_attr( $this->options['Passworduser']) : ''
        );
    }            



	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if (!isset($this->plugin_screen_hook_suffix)) {
			return;
		}

		$screen = get_current_screen();
		if ($screen->id == $this->plugin_screen_hook_suffix) {
			wp_enqueue_style($this->plugin_slug . "-admin-styles", plugins_url("css/admin.css", __FILE__), array(),
				$this->version);
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {
		if (!isset($this->plugin_screen_hook_suffix)) {
			return;
		}

		$screen = get_current_screen();
		if ($screen->id == $this->plugin_screen_hook_suffix) {
			wp_enqueue_script('jquery-ui-core');            // Enque jQuery UI Core
			wp_enqueue_script('jquery-ui-tabs');
			wp_enqueue_style( 'wp-date-picker' );
    		wp_enqueue_script( 'wp-date-picker-script', plugins_url('script.js', __FILE__ ), array( 'wp-date-picker' ), false, true );
            wp_enqueue_script( $this->plugin_slug . "-admin-script-angular", plugins_url( "js/angular.min.js", __FILE__ ), array("jquery"), $this->version);
			wp_enqueue_script( $this->plugin_slug . "-admin-script-angular-animate", plugins_url( "js/angular-animate.js", __FILE__ ), array("jquery"), $this->version);
			wp_enqueue_script($this->plugin_slug . "-admin-script", plugins_url("js/mind-body-admin.js", __FILE__),  array("jquery"), $this->version);
		}

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style($this->plugin_slug . "-plugin-styles", plugins_url("css/public.css", __FILE__), array(),
			$this->version);
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {    


	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {
		$this->plugin_screen_hook_suffix = add_menu_page(__("Mind Body - Administration", $this->plugin_slug),
			__("Mind Body", $this->plugin_slug), "read", $this->plugin_slug, array($this, "display_plugin_admin_page"));

	}
	/**
	 * Register the front end short code.
	 *
	 *  [bartag foo="foo-value"]
	 *
	 * @since    1.0.0
	 */ 
	public function mind_body_shrt( $atts ) {
			extract( shortcode_atts( array(
				'level' => ' ',
				'bar' => 'something else',
			), $atts ) );
			include_once("views/public.php");
	}
	/**
	 * Register the front end short code.
	 *
	 *  [bartag foo="foo-value"]
	 *
	 * @since    1.0.0
	 */ 
	public function mind_body_shrt_cal( $atts ) {
			extract( shortcode_atts( array(
				'level' => ' ',
				'bar' => 'something else',
			), $atts ) );
			include_once("views/cal.php");
	}
    /**
     * Register the front end short code.
     *
     *  [bartag foo="foo-value"]
     *
     * @since    1.0.0
     */ 
    public function mind_body_shrt_cal_wid( $atts ) {
            extract( shortcode_atts( array(
                'level' => ' ',
                'bar' => 'something else',
            ), $atts ) );
            include_once("views/calwidget.php");
    }
	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once("views/admin.php");
	}

	/**
	 * NOTE:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	 *
	 *        WordPress Actions: http://codex.wordpress.org/Plugin_API#Actions
	 *        Action Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function action_method_name() {
		// TODO: Define your action hook callback here
	}

	/**
	 * NOTE:  Filters are points of execution in which WordPress modifies data
	 *        before saving it or sending it to the browser.
	 *
	 *        WordPress Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *        Filter Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    1.0.0
	 */
	public function filter_method_name() {
		// TODO: Define your filter hook callback here
	}
	/**
	 * Tabbed Settings Page
	 */
	public function mindbody_admin_tabs( $current = 'Settings' ) { 
	    $tabs = array( 'settings' => 'Settings','courses' => 'Courses','products' => 'Products/Packages', 'classes' => 'Classes', 'staff' => 'staff' ); 
	    $links = array();
	    echo '<div id="icon-themes" class="icon32"><br></div>';
	    echo '<h2 class="nav-tab-wrapper">';
	    foreach( $tabs as $tab => $name ){
	        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
	        echo "<a class='nav-tab$class' href='?page=mind-body&tab=$tab'>$name</a>";
	        
	    }
	    echo '</h2>';
	}
	public function checkwoocommerce(){
		// check for plugin using plugin name
		$truefalse = '';
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) && is_plugin_active( 'woocommerce-bookings/woocommmerce-bookings.php' ) ) {
			$truefalse = true;
		}else{
			$truefalse = false;
		}
		return $truefalse;
	}
	public function multi_in_array($needle, $haystack, $key) {
	    foreach ($haystack as $h) {
	        if (array_key_exists($key, $h) && $h[$key]==$needle) {
	            return true;
	        }
	    }
	    return false;
	}
	public function csvToJSON($csv) {
	    $rows = explode("\n", $csv);

	    $i = 0;
	    $len = count($rows);
	    $json = "{\n" . '    "data" : [';
	    foreach ($rows as $row) {
	        $cols = explode(',', $row);
	        $json .= "\n        {\n";
	        $json .= '            "var0" : "' . $cols[0] . "\",\n";
	        $json .= '            "var1" : "' . $cols[1] . "\",\n";
	        $json .= '            "var2" : "' . $cols[2] . "\",\n";
	        $json .= '            "var3" : "' . $cols[3] . "\",\n";
	        $json .= '            "var4" : "' . $cols[4] . "\",\n";
	        $json .= '            "var5" : "' . $cols[5] . "\",\n";
	        $json .= '            "var6" : "' . $cols[6] . "\",\n";
	        $json .= '            "var7" : "' . $cols[7] . "\",\n";
	        $json .= '            "var8" : "' . $cols[8] . "\",\n";
	        $json .= '            "var9" : "' . $cols[9] . "\",\n";
	        $json .= '            "var10" : "' . $cols[10] . '"';
	        $json .= "\n        }";

	        if ($i !== $len - 1) {
	            $json .= ',';
	        }

	        $i++;
	    }
	    $json .= "\n    ]\n}";

	    return $json;
	}

}
