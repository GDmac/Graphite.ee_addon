<?php if ( ! defined('EXT')) exit('No direct script access allowed');


// include config file
include PATH_THIRD.'graphite/config'.EXT;

/**
 * Graphite Extension Class
 *
 * @package         graphite_ee_addon
 * @version         2.1
 * @author          Joel Bradbury ~ <joel@squarebit.co.uk>
 * @link            http://squarebit.co.uk/graphite
 * @copyright       Copyright (c) 2012, Joel 
 */

class Graphite_ext {

		// --------------------------------------------------------------------
	// PROPERTIES
	// --------------------------------------------------------------------

	/**
	 * Extension settings
	 *
	 * @access      public
	 * @var         array
	 */
	public $name 			= GRAPHITE_NAME;
	public $version 		= GRAPHITE_VERSION;
	public $description 	= 'Graphite extension';
	public $settings_exist 	= FALSE;
	public $docs_url	 	= GRAPHITE_DOCS;
	public $class_name 		= GRAPHITE_CLASS_NAME;

	private $EE;


	/**
	 * Hooks used
	 *
	 * @access      private
	 * @var         array
	 */
	private $hooks = array(
		'template_post_parse'
	);

	// --------------------------------------------------------------------
	// PUBLIC METHODS
	// --------------------------------------------------------------------

	/**
	 * PHP4 Constructor
	 *
	 * @see         __construct()
	 */
	public function Graphite_ext($settings = FALSE)
	{
		$this->__construct($settings);
	}

	// --------------------------------------------------------------------

	/**
	 * Constructor
	 *
	 * @access      public
	 * @param       mixed     Array with settings or FALSE
	 * @return      null
	 */
	public function __construct($settings = FALSE)
	{
		// Get global instance
		$this->EE =& get_instance();

		// Set Class name
		$this->class_name = ucfirst(get_class($this));
	}


	// --------------------------------------------------------------------
	// EXTENSION ACTIVATION, UPDATE AND DISABLING
	// --------------------------------------------------------------------

	/**
	 * Activate extension
	 *
	 * @access      public
	 * @return      null
	 */
	public function activate_extension()
	{
		// Loop through hooks and insert them in the DB
		foreach ($this->hooks AS $hook)
		{
			$this->EE->db->insert('extensions', array(
				'class'     => $this->class_name,
				'method'    => $hook,
				'hook'      => $hook,
				'priority'  => 10,
				'version'   => GRAPHITE_VERSION,
				'enabled'   => 'y'
			));
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Update extension
	 *
	 * @access      public
	 * @param       string    Saved extension version
	 * @return      null
	 */
	function update_extension($current = '')
	{
		if ($current == '' OR version_compare($current, GRAPHITE_VERSION) === 0)
		{
			return FALSE;
		}

		// init data array
		$data = array();

		// Add version to data array
		$data['version'] = GRAPHITE_VERSION;

		// Update records using data array
		$this->EE->db->where('class', $this->class_name);
		$this->EE->db->update('extensions', $data);
	}

	// --------------------------------------------------------------------

	/**
	 * Disable extension
	 *
	 * @access      public
	 * @return      null
	 */
	function disable_extension()
	{
		// Delete records
		$this->EE->db->where('class', $this->class_name);
		$this->EE->db->delete('extensions');
	}

	/**
	 * Run extra processing on a successful cart
	 *
	 * @access      public
	 * @param       int
	 * @param       array
	 * @param       array
	 * @return      void
	 */
	public function template_post_parse( $final_template, $sub, $site_id )
	{	
		if( !$sub )
		{
			// Is logging enabled and the current user in group 1?
			// if not, do nothing

			
			if (isset($this->EE->TMPL) && 
				is_object($this->EE->TMPL) && 
				isset($this->EE->TMPL->debugging) && 
				$this->EE->TMPL->debugging === TRUE && 
				$this->EE->TMPL->template_type != 'js')
			{
				if ($this->EE->session->userdata['group_id'] == 1)
				{		

					$theme_url = $this->EE->config->item('theme_folder_url').'third_party/graphite/js/';

					$append = "<div class='graphite_template_logging'>";
					$append .= '<script type="text/javascript" src="'. $theme_url . 'raphael-min.js"></script>';
					$append .= '<script type="text/javascript" src="'. $theme_url . 'popup.js"></script>';
					$append .= '<script type="text/javascript" src="'. $theme_url . 'jquery.js"></script>';
					$append .= '<script type="text/javascript" src="'. $theme_url . 'graphite.js"></script>';
					$append .= '<script type="text/javascript" src="'. $theme_url . 'analytics.js"></script>';
					$append .= "</div>";
					$append .= '<div id="graphite_log_graph_holder" style="background:#000; margin-bottom : 50px;"></div>';

					$final_template .= $append;

				}
			}
		}

		return $final_template;
	}	


}
