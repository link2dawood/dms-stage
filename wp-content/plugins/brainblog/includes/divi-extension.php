<?php

class BRBL_DiviExtension extends DiviExtension {

	/**
	 * The gettext domain for the extension's translations.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
    public $gettext_domain = 'brain-divi-blog';

	/**
	 * The extension's WP Plugin name.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $name = 'brainblog';

	/**
	 * The extension's version
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $version = BRAIN_BLOG_VERSION;

	/**
	 * BRBL_DiviExtension constructor.
	 *
	 * @param string $name
	 * @param array  $args
	 */
	public function __construct( $name = 'brainblog', $args = array() ) {
		$this->plugin_dir     = plugin_dir_path( __FILE__ );
		$this->plugin_dir_url = plugin_dir_url( $this->plugin_dir );

		parent::__construct( $name, $args );
	}
}

new BRBL_DiviExtension;
