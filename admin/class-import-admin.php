<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/brutcha/
 * @since      1.0.0
 *
 * @package    Import
 * @subpackage Import/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Import
 * @subpackage Import/admin
 * @author     brutcha <bocek.vojtech@gmail.com>
 */
class Import_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Relative path to folder used for drivers
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $drivers_folder    Relative path to drivers folder.
	 */
    private $drivers_folder = '/../drivers';

	/**
	 * Base admin plugin URI
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $base_URI    Base admin plugin URI
	 */
    protected $base_URI;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        $this->base_URI = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . '?page=' . $plugin_name;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Import_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Import_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/import-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Import_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Import_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/import-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
     * Register admin menu
     *
     * @since    1.0.0
     */
	public function menu() {

        add_menu_page(
            'Import',          // The title to be displayed in the browser window for this page.
            'Import',          // The text to be displayed for this menu item
            'administrator',        // Which type of users can see this menu item
            'Import',  // The unique ID - that is, the slug - for this menu item
            [ $this, 'display' ],   // The name of the function to call when rendering the page for this menu
            'dashicons-text'        // Icon url or dashicon id
        );

	}

	/**
	 * Display admin page
	 *
	 * @since    1.0.0
	 */
    public function display() {

		$drivers = $this->get_drivers();

		include( __DIR__ . '/partials/import-admin-display.php' );

	}


	/**
	 * List available feed drivers
	 *
	 * @since    1.0.0
	 */
    protected function get_drivers() {

    	$drivers_folder = $this->get_drivers_folder();

    	$files = array_diff( scandir( $drivers_folder ), ['..', '.', '.DS_Store'] );

		return array_map( function($input) use ($drivers_folder)
		{
			return [
				'name' => str_replace('.php', '', $input),
				'filemtime' => filemtime($drivers_folder . '/' . $input)
			];
		}, array_filter( $files, function($input) use ($drivers_folder) {
		    // Do not count folders in
		    return !is_dir($drivers_folder . '/' . $input);
        } ) );

	}

	/**
	 * Get absolute path to drivers folder
	 *
	 * @since    1.0.0
	 */
	protected function get_drivers_folder() {

		return __DIR__ . $this->drivers_folder;

	}

	/**
	 * Return absolute path to specified driver
	 *
	 * @param $driver
	 * @return string
	 *
	 * @since    1.0.0
	 */
	protected function get_driver_path( $driver ) {

		return $this->get_drivers_folder() . '/' . $driver . '.php';

	}

    /**
	 * Run specific feed based on 'drive' post body property
	 *
	 * @since    1.0.0
	 */
    public function driver_run() {

        $driver = $_POST['driver'];
        $filepath = $this->get_driver_path($driver);

        if ( !file_exists($filepath) )
        {
            echo 'Driver does not exist (' . $filepath . ')';
            return;
        }

        include_once($filepath);
        wp_die();

    }

    /**
	 * Show controls for specific feed
	 *
     * @param $driver
	 * @return void
     * 
	 * @since    1.0.0
	 */
    protected function process( $driver ) {

        $filepath = $this->get_driver_path($driver);

        if ( !file_exists($filepath) )
        {
            echo 'Driver does not exist (' . $filepath . ')';

            return;
        }

        include(__DIR__ . '/partials/import-admin-feed.php');
    }

}
