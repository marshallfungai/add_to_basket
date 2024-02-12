<?php
/**
 * The metabox-specific functionality of the plugin.
 *
 * @link       https://marshallfungai.github.io
 * @since      1.0.0
 *
 * @package    Add_to_basket
 * @subpackage Add_to_basket/admin
 */


class Add_to_basket_Metaboxes
{

	/**
	 * The post meta data
	 *
	 * @since        1.0.0
	 * @access        private
	 * @var        string $meta The post meta data.
	 */
	private $meta;

	/**
	 * The ID of this plugin.
	 *
	 * @since        1.0.0
	 * @access        private
	 * @var        string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since        1.0.0
	 * @access        private
	 * @var        string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $Now_Hiring The name of this plugin.
	 * @param string $version The version of this plugin.
	 * @since        1.0.0
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->set_meta();

	}

	/**
	 * Registers metaboxes with WordPress
	 *
	 * @since    1.0.0
	 * @access    public
	 */
	public function add_metaboxes()
	{

		// add_meta_box( $id, $title, $callback, $screen, $context, $priority, $callback_args );
		//now_hiring_job_additional_info
		add_meta_box(
			'a2b_metabox_product_key',
			esc_html__('Product ID', 'add2basket'),
			array($this, 'metabox'),
			$this->plugin_name,
			'normal',
			'core',
			array(
				'file' => 'a2b-product-id'
			)
		);

		add_meta_box(
			'a2b_metabox_product_price',
			esc_html__('Product Price', 'add2basket'),
			array($this, 'metabox'),
			$this->plugin_name,
			'normal',
			'core',
			array(
				'file' => 'a2b-price'
			)
		);

		add_meta_box(
			'a2b_metabox_product_btn_label',
			esc_html__('Product Label', 'add2basket'),
			array($this, 'metabox'),
			$this->plugin_name,
			'normal',
			'core',
			array(
				'file' => 'a2b-btn-label'
			)
		);

//	add_meta_box(
//		'now_hiring_job_requirements',
//		apply_filters( $this->plugin_name . '-metabox-title-requirements', esc_html__( 'Job Requirements', 'add2basket' ) ),
//		array( $this, 'metabox' ),
//		$this->plugin_name,
//		'normal',
//		'default',
//		array(
//			'file' => 'job-requirements'
//		)
//	);
//
//	add_meta_box(
//		'now_hiring_job_files',
//		apply_filters( $this->plugin_name . '-metabox-title-requirements', esc_html__( 'Related Files', 'add2basket' ) ),
//		array( $this, 'metabox' ),
//		$this->plugin_name,
//		'side',
//		'default',
//		array(
//			'file' => 'job-files',
//			'classes' => 'equal'
//		)
//	);

	} // add_metaboxes()

	/**
	 * Check each nonce. If any don't verify, $nonce_check is increased.
	 * If all nonces verify, returns 0.
	 *
	 * @return        int        The value of $nonce_check
	 * @since        1.0.0
	 * @access        public
	 */
	private function check_nonces($posted)
	{

		$nonces = array();
		$nonce_check = 0;

		$nonces[] = 'a2b_metabox_product_key';
		$nonces[] = 'a2b_metabox_product_btn_label';
		$nonces[] = 'a2b_metabox_product_price';
//		$nonces[] 		= 'job_additional_info';
//		$nonces[] 		= 'job_files';

		foreach ($nonces as $nonce) {
			//print_r($posted);
			//wp_die( '<pre>' . print_r($posted[$nonce]) . '</pre>' );
			if (!isset($posted[$nonce])) {
				$nonce_check++;
			}
			if (isset($posted[$nonce]) && !wp_verify_nonce($posted[$nonce], $this->plugin_name)) {
				$nonce_check++;
			}

		}

		return $nonce_check;

	} // check_nonces()

	/**
	 * Returns an array of the all the metabox fields and their respective types
	 *
	 * @return        array        Metabox fields and types
	 * @since        1.0.0
	 * @access        public
	 */
	private function get_metabox_fields()
	{

		$fields = array();
		$fields[] = array('a2b_metabox_product_key_text', 'text');
		$fields[] = array('a2b_metabox_product_btn_label_text', 'text');
		$fields[] = array('a2b_metabox_product_price_text', 'text');
		return $fields;

	} // get_metabox_fields()

	/**
	 * Calls a metabox file specified in the add_meta_box args.
	 *
	 * @return    void
	 * @since    1.0.0
	 * @access    public
	 */
	public function metabox($post, $params)
	{

		if (!is_admin()) {
			return;
		}
		if ('add_to_basket' !== $post->post_type) {
			return;
		}

		if (!empty($params['args']['classes'])) {

			$classes = 'repeater ' . $params['args']['classes'];

		}

    	include(plugin_dir_path(__FILE__) . 'partials/add_to_basket-admin-metabox-' . $params['args']['file'] . '.php');

	} // metabox()

	private function sanitizer($type, $data)
	{

		if (empty($type)) {
			return;
		}
		if (empty($data)) {
			return;
		}

		$return = '';
		$sanitizer = new Add_to_basket_Sanitize();

		$sanitizer->set_data($data);
		$sanitizer->set_type($type);

		$return = $sanitizer->clean();

		unset($sanitizer);

		return $return;

	} // sanitizer()

	/**
	 * Saves button order when buttons are sorted.
	 */
	public function save_files_order()
	{

		check_ajax_referer('add_to_basket-file-order-nonce', 'fileordernonce');

		$order = $this->meta['file-order'];
		$new_order = implode(',', $_POST['file-order']);
		$this->meta['file-order'] = $new_order;
		$update = update_post_meta('file-order', $new_order);

		esc_html_e('File order saved.', 'add2basket');

		die();

	}  // save_files_order()

	/**
	 * Sets the class variable $options
	 */
	public function set_meta()
	{

		global $post;

		if (empty($post)) {
			return;
		}
		if ('add_to_basket' != $post->post_type) {
			return;
		}

		//wp_die( '<pre>' . print_r( $post->ID ) . '</pre>' );

		$this->meta = get_post_custom($post->ID);
		//wp_die(print_r($this->meta));

	} // set_meta()

	/**
	 * Saves metabox data
	 *
	 * Repeater section works like this:
	 *    Loops through meta fields
	 *        Loops through submitted data
	 *        Sanitizes each field into $clean array
	 *    Gets max of $clean to use in FOR loop
	 *    FOR loops through $clean, adding each value to $new_value as an array
	 *
	 * @param int $post_id The post ID
	 * @param object $object The post object
	 * @return    void
	 * @since    1.0.0
	 * @access    public
	 */
	public function validate_meta($post_id, $object)
	{

		//wp_die( '<pre>' . print_r( $_POST ) . '</pre>' );

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}
		if (!current_user_can('edit_post', $post_id)) {
			return $post_id;
		}
		if ($this->plugin_name !== $object->post_type) {
			return $post_id;
		}


		$nonce_check = $this->check_nonces($_POST);
	    if (0 < $nonce_check) {
			return $post_id;
		}

		$metas = $this->get_metabox_fields();

		foreach ($metas as $meta) {

			$name = $meta[0];
			$type = $meta[1];
			$new_value = $this->sanitizer($type, $_POST[$name]);
			update_post_meta($post_id, $name, $new_value);

//			if ('repeater' === $type && is_array($meta[2])) {
//
//				$clean = array();
//
//				foreach ($meta[2] as $field) {
//
//					foreach ($_POST[$field[0]] as $data) {
//
//						if (empty($data)) {
//							continue;
//						}
//
//						$clean[$field[0]][] = $this->sanitizer($field[1], $data);
//
//					} // foreach
//
//				} // foreach
//
//				$count = a2b_get_max($clean);
//				$new_value = array();
//
//				for ($i = 0; $i < $count; $i++) {
//
//					foreach ($clean as $field_name => $field) {
//
//						$new_value[$i][$field_name] = $field[$i];
//
//					} // foreach $clean
//
//				} // for
//
//			} else {
//
//
//
//			}



		} // foreach

	} // validate_meta()

} // class
