<?php

class ThemeSuite_Status_Report {
  public $page_url_slug = 'automotive_system_status_report';

  public function __construct(){
    add_action( 'admin_menu', array($this, 'register_system_status_page') );
    add_action( 'admin_notices', array($this, 'outdated_files_message') );

    if(isset($_GET['page']) && $_GET['page'] == $this->page_url_slug){
      add_action( 'admin_head', array($this, 'status_js'), 11);
    }
  }

  public function register_system_status_page(){
    add_submenu_page( 'edit.php?post_type=listings', __( "System Status", "listings" ), __( "System Status", "listings" ), "manage_options", $this->page_url_slug, array($this, 'automotive_system_status_report') );
  }

  public function get_system_status_report_data(){
    global $wpdb;

    // Test POST requests.
  	$post_response            = wp_safe_remote_post(
  		'https://www.paypal.com/cgi-bin/webscr',
  		array(
  			'timeout'     => 10,
  			'user-agent'  => 'ThemeSuite/1.0.0',
  			'httpversion' => '1.1',
  			'body'        => array(
  				'cmd' => '_notify-validate',
  			),
  		)
  	);
  	$post_response_successful = false;
  	if ( ! is_wp_error( $post_response ) && $post_response['response']['code'] >= 200 && $post_response['response']['code'] < 300 ) {
  		$post_response_successful = true;
  	}

    // Test GET requests.
    $get_response            = wp_safe_remote_get( 'https://google.com/' );
    $get_response_successful = false;
    if ( ! is_wp_error( $post_response ) && $post_response['response']['code'] >= 200 && $post_response['response']['code'] < 300 ) {
    	$get_response_successful = true;
    }

    $active_theme = wp_get_theme();

    $status_report_information = array(
      "wordpress" => array(
        "title"  => __("WordPress Environment"),
        "fields" => array(
          array(
            "title" => __("Home URL"),
            "value" => home_url()
          ),
          array(
            "title" => __("Site URL"),
            "value" => site_url()
          ),
          array(
            "title" => __("WP Content URL"),
            "value" => WP_CONTENT_URL
          ),
          array(
            "title" => __("WP Version"),
            "value" => get_bloginfo( 'version' )
          ),
          array(
            "title" => __("WP Multisite"),
            "value" => $this->bool_icon(is_multisite()),
            "type"  => "html"
          ),
          array(
            "title" => __("Permalink Structure"),
            "value" => (get_option( 'permalink_structure' ) ? get_option( 'permalink_structure' ) : 'Default')
          ),
          array(
            "title" => __("WP Memory Limit"),
            "value" => WP_MEMORY_LIMIT
          ),
          array(
            "title" => __("Database Table Prefix"),
            "value" => 'Length: ' . strlen( $wpdb->prefix ) . ' - Status: ' . ( strlen( $wpdb->prefix ) > 16 ? 'ERROR: Too long' : 'Acceptable' )
          ),
          array(
            "title" => __("WP Debug Mode"),
            "value" => $this->bool_icon(defined("WP_DEBUG") && WP_DEBUG),
            "type"  => "html"
          ),
          array(
            "title" => __("Language"),
            "value" => get_locale()
          )
        )
      ),
      "browser" => array(
        "title"  => __("Browser Information"),
        "fields" => array(
          array(
            "title" => __("Info"),
            "value" => $this->status_browser_info(),
            "type"  => 'html'
          )
        )
      ),
      "server" => array(
        "title"  => __("Server Information"),
        "fields" => array(
          array(
            "title" => __("Server Info"),
            "value" => esc_html( $_SERVER['SERVER_SOFTWARE'] )
          ),
          array(
            "title" => __("Localhost Environment"),
            "value" => $this->bool_icon( $_SERVER['REMOTE_ADDR'] === '127.0.0.1' || $_SERVER['REMOTE_ADDR'] === 'localhost' ),
            "type"  => 'html'
          ),
          array(
            "title" => __("PHP Version"),
            "value" => function_exists( 'phpversion' ) ? esc_html( phpversion() ) : 'phpversion() function does not exist.'
          ),
          array(
            "title" => __("ABSPATH"),
            "value" => ABSPATH
          ),
          array(
            "title" => __("PHP Memory Limit"),
            "value" => $this->ini_status_get('memory_limit'),
            "type"  => 'html'
          ),
          array(
            "title" => __("PHP Post Max Size"),
            "value" => $this->ini_status_get('post_max_size'),
            "type"  => 'html'
          ),
          array(
            "title" => __("PHP Time Limit"),
            "value" => $this->ini_status_get('max_execution_time'),
            "type"  => 'html'
          ),
          array(
            "title" => __("PHP Max Input Vars"),
            "value" => $this->ini_status_get('max_input_vars'),
            "type"  => 'html'
          ),
          array(
            "title" => __("PHP Display Errors"),
            "value" => $this->ini_status_get('display_errors'),
            "type"  => 'html'
          ),
          array(
            "title" => __("MySQL Version"),
            "value" => $wpdb->db_version()
          ),
          array(
            "title" => __("Max Upload Size"),
            "value" => size_format( wp_max_upload_size() )
          ),
          array(
            "title" => __("fsockopen/cURL"),
            "value" => $this->bool_icon(function_exists('fsockopen') || function_exists('curl_init')),
            "type"  => 'html'
          ),
          array(
            "title" => __("Remote Post"),
            "value" => $this->bool_icon($post_response_successful),
            "type"  => 'html'
          ),
          array(
            "title" => __("Remote Get"),
            "value" => $this->bool_icon($get_response_successful),
            "type"  => 'html'
          ),
          array(
            "title" => __("Multibyte Module"),
            "value" => $this->bool_icon(function_exists( "mb_strtolower" )),
            "type"  => 'html'
          ),
          array(
            "title" => __("iconv Module"),
            "value" => $this->bool_icon(function_exists( "iconv" )),
            "type"  => 'html'
          )
        )
      ),
      "theme" => array(
        "title"  => __("Theme"),
        "fields" => array(
          array(
            "title" => __("Name"),
            "value" => $active_theme->Name
          ),
          array(
            "title" => __("Version"),
            "value" => $active_theme->Version
          ),
          array(
            "title" => __("Author URL"),
            "value" => $active_theme->{'Author URI'}
          ),
          array(
            "title" => __("Child Theme"),
            "value" => $this->bool_icon(is_child_theme()),
            "type"  => "html"
          )
        )
      ),
      "post_type_counts" => array(
        "title"  => __("Post Type Counts"),
        "fields" => array( )
      ),
      "plugins" => array(
        "title"  => __("Plugins"),
        "fields" => array( )
      ),
      "template_files" => array(
        "title"  => __("Template Files"),
        "fields" => array( )
      )
    );

    $post_type_counts = $this->get_post_type_counts();

    if(!empty($post_type_counts)){
      foreach($post_type_counts as $post_type){
        $status_report_information['post_type_counts']['fields'][] = array(
          "title" => $post_type->type,
          "value" => $post_type->count
        );
      }
    }

    // lets add the plugins now
    $active_plugins = (array) get_option( 'active_plugins', array() );

    if ( is_multisite() ) {
        $active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
    }

    $status_report_information['plugins']['fields'] = array();

    foreach ( $active_plugins as $plugin ) {
        $plugin_data    = @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
        $plugin_name    = esc_html( $plugin_data['Name'] );
        $version_string = '';
        $network_string = '';

        $status_report_information['plugins']['fields'][] = array(
          "title" => $plugin_name,
          "url"   => (!empty($plugin_data['PluginURI']) ? $plugin_data['PluginURI'] : false),
          "type"  => 'html',
          "value" => sprintf( _x( 'by %s', 'by author', 'redux-framework' ), $plugin_data['Author'] ) . ' &ndash; ' . esc_html( $plugin_data['Version'] ) . $version_string . $network_string
        );
    }

    // get all the template files being used.
    $template_files            = array_flip(list_files(LISTING_HOME.'auto_templates/')); // flip this so it's quicker to search
    $custom_template_files_dir = trailingslashit(get_stylesheet_directory()).'auto_templates/';
    $custom_template_files     = (file_exists($custom_template_files_dir) ? list_files($custom_template_files_dir) : []);
    $plugin_template_file_html = '';

    if(!empty($custom_template_files)){

      foreach($custom_template_files as $custom_template_file){
        $plugin_template_file = str_replace(trailingslashit(get_stylesheet_directory()), LISTING_HOME, $custom_template_file);

        if(isset($template_files[$plugin_template_file])){
          $custom_version_data = get_file_data($custom_template_file, array('Version' => 'Version'));
          $core_version_data   = get_file_data($plugin_template_file, array('Version' => 'Version'));

          if(empty($custom_version_data['Version'])){
            $custom_version_data['Version'] = '14.0'; // 14.0 was last version to not have the version in template files
          }

          if(empty($core_version_data['Version'])){
            $core_version_data['Version'] = '14.0';
          }

          $outdated_file = version_compare($custom_version_data['Version'], $core_version_data['Version'], "<");

          $version_text = ($outdated_file ? '<span class="outdated-version">' : '') . 'Version: ' . esc_html($custom_version_data['Version']) . ($outdated_file ? '</span>' : '');
          $core_text    = ($outdated_file ? '<span class="outdated-core">' : '') . 'Current: ' . esc_html($core_version_data['Version']) . ($outdated_file ? '</span>' : '');

          $plugin_template_file_html .= ($outdated_file ? "<strong>" : "");
          $plugin_template_file_html .= esc_html($custom_template_file) . ' (' . $version_text  .' | ' . $core_text . ')';
          $plugin_template_file_html .= ($outdated_file ? "</strong>" : "");
          $plugin_template_file_html .= ' <br>';
        }
      }

      if(!empty($plugin_template_file_html)){
        $status_report_information['template_files']['fields'][] = array(
          "title" => __("Overrides"),
          "value" => $plugin_template_file_html,
          "type"  => "html"
        );
      }

    } else {
      $status_report_information['template_files']['fields'][] = array(
          "title" => __("Overrides"),
          "value" => __("None")
      );
    }

    if(is_child_theme()){
      $parent_theme = wp_get_theme( $active_theme->Template );

      $status_report_information['theme']['fields'][] = array(
        "title" => __("Parent Name"),
        "value" => $parent_theme->Name
      );

      $status_report_information['theme']['fields'][] = array(
        "title" => __("Parent Version"),
        "value" => $parent_theme->Version
      );

      $status_report_information['theme']['fields'][] = array(
        "title" => __("Parent Author URL"),
        "value" => $parent_theme->{'Author URI'}
      );
    }

    return $status_report_information;
  }

  public function automotive_system_status_report() {
    $status_report_information = $this->get_system_status_report_data();

    ?>

    <div class="wrap">
      <h1><?php esc_html_e("System Status"); ?></h1>

      <div class="notice notice-success debug_copy_details">
        <p><?php esc_html_e("Please copy and paste this information in your ticket when contacting support"); ?></p>

        <textarea id="staus_text_debug" readonly><?php echo wp_json_encode($status_report_information); ?></textarea>

        <div>
          <button id="select-all-debug" class="button-primary"><?php esc_html_e("Select All"); ?></button>
          <button id="copy-all-debug" class="button-primary"><?php esc_html_e("Copy Code"); ?></button>
        </div>
      </div>

      <?php
      if(!empty($status_report_information)){
        foreach($status_report_information as $section_id => $section){ ?>

        <table class="themesuite_status_table widefat" cellspacing="0" id="<?php echo esc_attr($section_id); ?>">
            <thead>
            <tr>
                <th colspan="3" data-export-label="<?php echo esc_html($section['title']); ?>">
                  <?php echo esc_html($section['title']); ?>
                </th>
            </tr>
            </thead>
            <tbody>

            <?php if(!empty($section['fields'])){
              foreach($section['fields'] as $info){
                $type = (isset($info['type']) && !empty($info['type']) ? $info['type'] : false); ?>
                <tr>
                    <td data-export-label="<?php echo esc_attr($info['title']); ?>">
                        <?php
                        if(isset($info['url'])){
                          echo '<a href="' . esc_url($info['url']) . '">' . esc_html($info['title']) . '</a>';
                        } else {
                          echo esc_html($info['title']);
                        } ?>:
                    </td>
                    <td>
                      <?php
                      if($type){
                        if($type == 'html'){
                          echo $info['value'];
                        }
                      } else {
                        echo esc_html($info['value']);
                      }
                      ?>
                    </td>
                </tr>
              <?php }
            } ?>
            </tbody>
        </table><br>

      <?php }
    } ?>
    </div>

    <?php

  }

  public function status_browser_info() {
    $return = '';

    if(!class_exists("Browser")){
      require_once(LISTING_HOME . "classes/class.browser.php");
    }

    $browser = new Browser();
    $browser_info = array(
      'agent'    => $browser->getUserAgent(),
      'browser'  => $browser->getBrowser(),
      'version'  => $browser->getVersion(),
      'platform' => $browser->getPlatform()
    );

    foreach($browser_info as $info_key => $info){
      $return .= "<strong>" . ucfirst($info_key) . ": </strong> " . esc_html($info) . '<br>';
    }

    return $return;
  }

  public function bool_icon($boolean){
    return '<i class="fa fa-' . ($boolean ? 'check' : 'times') . '"></i>';
  }

  public function ini_status_get($ini){
    if(!function_exists('ini_get'))
      return 'ini_get does not exist.';

    return ini_get($ini);
  }

  public function get_outdated_template_files(){
    $template_files        = array_flip(list_files(LISTING_HOME.'auto_templates/')); // flip this so it's quicker to search
    $custom_template_files_dir = trailingslashit(get_stylesheet_directory()).'auto_templates/';
    $custom_template_files = (file_exists($custom_template_files_dir) ? list_files($custom_template_files_dir) : []);
    $has_outdated_files    = false;

    if(!empty($custom_template_files)){

      foreach($custom_template_files as $custom_template_file){
        $plugin_template_file = str_replace(trailingslashit(get_stylesheet_directory()), LISTING_HOME, $custom_template_file);

        if(isset($template_files[$plugin_template_file])){
          $custom_version_data = get_file_data($custom_template_file, array('Version' => 'Version'));
          $core_version_data   = get_file_data($plugin_template_file, array('Version' => 'Version'));

          if(empty($custom_version_data['Version'])){
            $custom_version_data['Version'] = '14.0'; // 14.0 was last version to not have the version in template files
          }

          if(empty($core_version_data['Version'])){
            $core_version_data['Version'] = '14.0';
          }

          if(version_compare($custom_version_data['Version'], $core_version_data['Version'], "<")){
            $has_outdated_files = true;
            break;
          }
        }
      }
    }

    return $has_outdated_files;
  }

  public function get_post_type_counts(){
    global $wpdb;
  	$post_type_counts = $wpdb->get_results( "SELECT post_type AS 'type', count(1) AS 'count' FROM {$wpdb->posts} GROUP BY post_type;" );
  	return is_array( $post_type_counts ) ? $post_type_counts : array();
  }

  public function outdated_files_message() {
    $force_check = false;

    // lets force the check if we are on the status page
    if(isset($_GET['page']) && $_GET['page'] == "automotive_system_status_report"){
      $force_check = true;
    }

    if ($force_check || (false === ( $automotive_has_outdated_files = get_transient( 'automotive_outdated_template_files' ) )) ) {
         $automotive_has_outdated_files = $this->get_outdated_template_files();

         set_transient( 'special_query_results', $automotive_has_outdated_files, 4 * HOUR_IN_SECONDS );
    }

    if($automotive_has_outdated_files){ ?>
      <div class="notice notice-error outdated_files_message">
        <h3><?php esc_html_e("Outdated Automotive Template Files"); ?></h3>

        <p><?php echo esc_html_e( 'Your theme is currently using outdated automotive template files, if you don\'t update the files you may notice issues on your website.' ); ?></p>

        <p><?php echo esc_html_e( 'Please compare your custom files with the correspeonding core files and make any necessary updates.' ); ?></p>

        <a href="<?php echo esc_url(admin_url('edit.php?post_type=listings&page=automotive_system_status_report#template_files')); ?>" class="button-primary"><?php esc_html_e("View Outdated Files"); ?></a>
      </div><?php
    }
  }

  public function status_js(){ ?>

    <script>
    window.onload = function(){
      var textBox   = document.getElementById("staus_text_debug");
      var selectAll = document.getElementById("select-all-debug");
      var copyAll   = document.getElementById("copy-all-debug");

      selectAll.onclick = function() {
          textBox.select();
      };

      copyAll.onclick = function() {
         textBox.select();
         document.execCommand("copy");
      }
  };
    </script>
    <?php
  }
}

new ThemeSuite_Status_Report();