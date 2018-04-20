<?php

    if (!class_exists('KEYSPECS_Plugin')) {

        class KEYSPECS_Plugin{
            public static $plugin_file = '';
            public $plugin_name;
            public $plugin_basename;
            public $plugin_path;
            public $plugin_url;
            public $options;

            public $currencies = array(
                'USD' => array('symbol' => '$', 'code' => 'USD'),
                'EUR' => array('symbol' => '€', 'code' => 'EUR'),
                'GBP' => array('symbol' => '£', 'code' => 'GBP'),
                'AUD' => array('symbol' => 'A$', 'code' => 'AUD'),
                'CAD' => array('symbol' => 'C$', 'code' => 'CAD'),
                'JPY' => array('symbol' => '¥', 'code' => 'JPY'),
                'INR' => array('symbol' => '₹', 'code' => 'INR'),
                'TRY' => array('symbol' => '₺', 'code' => 'TRY'),
                'ZAR' => array('symbol' => 'R', 'code' => 'ZAR'),
                'IDR' => array('symbol' => 'Rp', 'code' => 'IDR'),
                'KRW' => array('symbol' => '₩', 'code' => 'KRW')
            );

            public $embedTheme = array(
                'theme001' => 'Light',
                'theme002' => 'Dark',
                'theme003' => 'Light',
                'theme004' => 'Dark'
            );

            /**
             * Construct the plugin object
             */
            public function __construct(){
                // Initialize Settings
                require_once(sprintf("%s/settings.php", dirname(__FILE__)));
                $KEYSPECS_Settings = new KEYSPECS_Settings($this);

                // Get options
                $this->options = get_option('KEYSPECS_settings');

                // Register styles
                add_action('wp_enqueue_scripts', array($this, 'register_public_styles'));

                // Register special pages
                add_action( 'init', array($this, 'crypto_url_vars') );
                add_action( 'template_redirect', array($this, 'crypto_redirect') );
                add_filter( 'wp_title', array($this, 'crypto_title'), 15, 3 );
                add_action( 'wp_ajax_nopriv_keyspecs_ajax', array($this, 'keyspecs_ajax_request'));

                // Register Settings
                self::$plugin_file = KEYSPECS_Plugin_MAIN_FILE_PATH;
                $this->plugin_name = strtolower(plugin_basename(dirname(self::$plugin_file)));
                $this->plugin_basename = plugin_basename(self::$plugin_file);
                $this->plugin_path = plugin_dir_path(self::$plugin_file);
                $this->plugin_url  = plugin_dir_url(self::$plugin_file);
            }

            /**
             * Activate the plugin
             */
            public static function activate(){

            }

            /**
             * Deactivate the plugin
             */
            public static function deactivate()
            {
                
            }

            /**
             * Uninstall the plugin
             */
            public static function uninstall(){
                delete_option('KEYSPECS_settings');
            }

            public static function setCache($request, $data){
                if (!isset($data) || empty($data))
                    return;
                set_transient('keyspecs_' . $request, json_encode($data), 0);
            }

            public static function getCache($request){
                return get_transient('keyspecs_' . $request);
            }

            public function keyspecs_ajax_request(){
                
            }

        }
    }

    function KEYSPECS_Plugin_init(){
        if (class_exists('KEYSPECS_Plugin')) {

            $KEYSPECS = new KEYSPECS_Plugin();

            // Add a link to the settings page onto the plugin page
            if (isset($KEYSPECS)) {

                // Add the settings link to the plugins page
                function KEYSPECS_Plugin_settings_link($links){
                    $settings_link = '<a href="options-general.php?page=' . 'KEYSPECS' . '">Settings</a>';
                    array_unshift($links, $settings_link);
                    return $links;
                }

                add_filter("plugin_action_links_" . plugin_basename(KEYSPECS_Plugin_MAIN_FILE_PATH), 'KEYSPECS_Plugin_settings_link');
            }
        }
    }
?>