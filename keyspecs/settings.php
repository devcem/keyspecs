<?php
if (!class_exists('CRYPTOWP_Settings')) {
    class CRYPTOWP_Settings
    {
        protected $parent = null;
        protected $plugin_name;
        protected $plugin_basename;
        protected $plugin_path;
        protected $plugin_url;
        private $options;
        private $available_styles = array(
            'transparent' => 'No Additional CSS',
            'theme001' => 'Light',
            'theme002' => 'Dark',
            'theme004' => 'Dark Blue',
            'theme003' => 'Border style'
        );

        private $available_services = array(
            'all' => 'Use all services',
            'coinmarketcap' => 'Coinmarketcap',
            'cryptocompare' => 'Cryptocompare'
        );

        private $graph_services = array(
            'binance'  => 'Binance',
            'coinbase' => 'Coinbase'
        );

        private $charts = array(
            'tradingview' => 'TradingView',
            'chartjs'    => 'ChartJS'
        );

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

        /**
         * Construct the plugin object
         */
        public function __construct($parent)
        {
            $this->parent = &$parent;
            $this->plugin_name = $parent->plugin_name;
            $this->plugin_basename = $parent->plugin_basename;
            $this->plugin_path = $parent->plugin_path;
            $this->plugin_url = $parent->plugin_url;
            $this->options = get_option('CRYPTOWP_settings');

            $this->actions();
        }

        function actions () {
            add_action('admin_menu', array($this, 'add_admin_menu'));
            add_action('admin_init', array($this, 'settings_init'));
        }

        function add_admin_menu() {

            add_options_page(
                CRYPTOWP_CONFIG_MENU_TEXT,
                CRYPTOWP_CONFIG_MENU_TEXT,
                'manage_options',
                'CRYPTOWP',
                array($this, 'options_page')
            );

        }

        /*
         * Setting Sections & Fields
         */
        function settings_init()
        {

            register_setting(
                'CRYPTOWP_settings', // Settings page
                'CRYPTOWP_settings', // Option name
                array($this, 'settings_callback') // callback
            );

            add_settings_section(
                'CRYPTOWP_settings_section_configuration',
                __('CryptoWP Settings', 'CRYPTOWP'),
                array($this, 'settings_section_configuration_callback'),
                'CRYPTOWP_settings'
            );

            // Currency
            add_settings_field(
                'CRYPTOWP_currency',
                __('Currency', 'CRYPTOWP'),
                array($this, 'currency'),
                'CRYPTOWP_settings',
                'CRYPTOWP_settings_section_configuration'
            );

            // Service
            add_settings_field(
                'CRYPTOWP_service',
                __('Data Services', 'CRYPTOWP'),
                array($this, 'services'),
                'CRYPTOWP_settings',
                'CRYPTOWP_settings_section_configuration'
            );

            // Graph Service
            /*
            add_settings_field(
                'CRYPTOWP_graph_service',
                __('Information Service', 'CRYPTOWP'),
                array($this, 'graph_services'),
                'CRYPTOWP_settings',
                'CRYPTOWP_settings_section_configuration'
            );
            */

            // Display Components
            add_settings_field(
                'CRYPTOWP_display',
                __('Show/Hide Components', 'CRYPTOWP'),
                array($this, 'display'),
                'CRYPTOWP_settings',
                'CRYPTOWP_settings_section_configuration'
            );

            // Display Components
            add_settings_field(
                'CRYPTOWP_table',
                __('Show/Hide Table Components', 'CRYPTOWP'),
                array($this, 'table'),
                'CRYPTOWP_settings',
                'CRYPTOWP_settings_section_configuration'
            );

            // Graph Service
            add_settings_field(
                'CRYPTOWP_custom_page',
                __('Custom Page For Coin Views', 'CRYPTOWP'),
                array($this, 'custom_page'),
                'CRYPTOWP_settings',
                'CRYPTOWP_settings_section_configuration'
            );

            // Basic functions
            add_settings_field(
                'CRYPTOWP_preferences',
                __('Preferences', 'CRYPTOWP'),
                array($this, 'preferences'),
                'CRYPTOWP_settings',
                'CRYPTOWP_settings_section_configuration'
            );

            add_settings_field(
                'CRYPTOWP_timeout',
                __('Tick Timeout', 'CRYPTOWP'),
                array($this, 'timeout'),
                'CRYPTOWP_settings',
                'CRYPTOWP_settings_section_configuration'
            );

            add_settings_field(
                'CRYPTOWP_optional_link',
                __('Optional Link', 'CRYPTOWP'),
                array($this, 'optional_link'),
                'CRYPTOWP_settings',
                'CRYPTOWP_settings_section_configuration'
            );

            // Theme
            add_settings_field(
                'CRYPTOWP_box_style',
                __('Theme', 'CRYPTOWP'),
                array($this, 'box_style'),
                'CRYPTOWP_settings',
                'CRYPTOWP_settings_section_configuration'
            );

            // Border Color
            add_settings_field(
                'CRYPTOWP_colors',
                __('Colors', 'CRYPTOWP'),
                array($this, 'colors'),
                'CRYPTOWP_settings',
                'CRYPTOWP_settings_section_configuration'
            );

            // Border Color
            add_settings_field(
                'CRYPTOWP_language',
                __('Language', 'CRYPTOWP'),
                array($this, 'language'),
                'CRYPTOWP_settings',
                'CRYPTOWP_settings_section_configuration'
            );

            //Custom CSS
            add_settings_field(
                'CRYPTOWP_custom_css',
                __('Custom CSS', 'CRYPTOWP'),
                array($this, 'custom_css'),
                'CRYPTOWP_settings',
                'CRYPTOWP_settings_section_configuration'
            );
        }

        function services()
        {
            $service = isset($this->options['CRYPTOWP_service']) ? $this->options['CRYPTOWP_service'] : CRYPTOWP_DEFAULT_SERVICE;
            ?>
            <select name="CRYPTOWP_settings[CRYPTOWP_service]">
                <?php foreach ($this->available_services as $value => $name) { ?>
                    <option value="<?php echo $value; ?>" <?php selected($service, $value); ?>><?php _e($name, 'CRYPTOWP') ?></option>
                <?php } ?>
            </select>
        <?php

        }

        function graph_services()
        {
            $service = isset($this->options['CRYPTOWP_graph_service']) ? $this->options['CRYPTOWP_graph_service'] : CRYPTOWP_DEFAULT_FONT_STYLE;
            ?>
            <select name="CRYPTOWP_settings[CRYPTOWP_graph_service]">
                <?php foreach ($this->graph_services as $value => $name) { ?>
                    <option value="<?php echo $value; ?>" <?php selected($service, $value); ?>><?php _e($name, 'CRYPTOWP') ?></option>
                <?php } ?>
            </select>
        <?php

        }

        function custom_page()
        {
            $url_prefix = isset($this->options['CRYPTOWP_url_prefix']) ? $this->options['CRYPTOWP_url_prefix'] : 'currency';
            ?>
                <p>
                    <input type="text" name="CRYPTOWP_settings[CRYPTOWP_url_prefix]" value="<?php echo $url_prefix; ?>" placeholder="currency">
                    <label>It will your coin view page's URL prefix. E.g. <b>currency</b></label>
                </p>
                <br>
            <?php

            $custom_page = isset($this->options['CRYPTOWP_custom_page']) ? $this->options['CRYPTOWP_custom_page'] : 0;

            $pages = get_pages( array( 'sort_column' => 'post_date', 'sort_order' => 'desc' ) );

            ?>
            <select name="CRYPTOWP_settings[CRYPTOWP_custom_page]">
                <?php foreach ($pages as $value => $page) { ?>
                    <option value="<?php echo $page->ID; ?>" <?php selected($custom_page, $page->ID); ?>><?php _e($page->post_title, 'CRYPTOWP') ?></option>
                <?php } ?>
            </select><label class="cryptowp-label">Please select a page from your Wordpress for coin view and write in that page <b>[cryptowp][/cryptowp]</b></label>
        	<?php
        }


        function box_style()
        {
            $box_style = isset($this->options['CRYPTOWP_box_style']) ? $this->options['CRYPTOWP_box_style'] : CRYPTOWP_DEFAULT_BOX_STYLE;
            ?>
            <select name="CRYPTOWP_settings[CRYPTOWP_box_style]">
                <?php foreach ($this->available_styles as $value => $name) { ?>
                    <option value="<?php echo $value; ?>" <?php selected($box_style, $value); ?>><?php _e($name, 'CRYPTOWP') ?></option>
                <?php } ?>
            </select>
        <?php

        }

        function currency()
        {
            $currency = isset($this->options['CRYPTOWP_currency']) ? $this->options['CRYPTOWP_currency'] : CRYPTOWP_DEFAULT_CURRENCY;
            ?>
            <select name="CRYPTOWP_settings[CRYPTOWP_currency]">
                <?php foreach ($this->currencies as $key => $value) { ?>
                    <option value="<?php echo $value['code']; ?>" <?php selected($currency, $value['code']); ?>><?php _e($value['code'].' '.$value['symbol'], 'CRYPTOWP') ?></option>
                <?php } ?>
            </select>
        <?php

        }

        function layout_styles()
        {
            $layout_style = isset($this->options['CRYPTOWP_layout_style']) ? $this->options['CRYPTOWP_layout_style'] : CRYPTOWP_DEFAULT_BOX_STYLE;
            ?>
            <select name="CRYPTOWP_settings[CRYPTOWP_layout_style]">
                <?php foreach ($this->layout_styles as $value => $name) { ?>
                    <option value="<?php echo $value; ?>" <?php selected($layout_style, $value); ?>><?php _e($name, 'CRYPTOWP') ?></option>
                <?php } ?>
            </select>
            <?php
        }

        function colors()
        {
            $colorArray = array();
            $colorArray['border_color']     = 'Border Color';
            $colorArray['bg_color']         = 'Background Color (Replaces Theme Color)';
            $colorArray['red_color']        = 'Red Color (Danger)';
            $colorArray['green_color']      = 'Green Color (Success)';
            $colorArray['gray_color']       = 'Gray Color (Default)';

            $colorArray['title_color']      = 'Title Color';
            $colorArray['rank_bg_color']    = 'Rank background color';
            $colorArray['rank_tx_color']    = 'Rank text color';

            //$functionArray['save_image']     = 'Save Images From Chart';

            foreach ($colorArray as $key => $value) {
                $thisValue = isset($this->options['CRYPTOWP_color_'.$key]) ? 1 : 0;
                $thisID    = __('CRYPTOWP_color_'.$key);
                ?>
                <p>
                    <input id="<?php echo $thisID; ?>" type="color"
                           name="CRYPTOWP_settings[<?php echo $thisID; ?>]" <?php checked($thisValue, 1); ?>
                           value="#0000000" />
                    <label
                        for="<?php echo $thisID; ?>"><?php _e( $value, 'CRYPTOWP' ); ?>
                    </label>
                </p>
                <?php
            }
            ?>
            <?php
        }

        function language()
        {
            $languageArray = array();
            $languageArray['coin']     = 'Coin';
            $languageArray['name']     = 'Name';
            $languageArray['price']     = 'Price';
            $languageArray['change']     = 'Change';
            $languageArray['24h']     = '24h';
            $languageArray['7d']     = '7d';
            $languageArray['1d']     = '1d';
            $languageArray['a_supply']     = 'A. Supply';
            $languageArray['volume']     = 'Volume';
            $languageArray['price_graph']     = 'Price Graph';
            $languageArray['search']     = 'Search';
            $languageArray['convert_currency']     = 'Convert Currency';
            $languageArray['show_entries']     = 'Show Entries';
            $languageArray['time_range']     = 'Time Range';
            $languageArray['realtime_chart']     = 'Realtime Historical Chart';
            $languageArray['alarms']     = 'Alarms';
            $languageArray['marketcap']     = 'Market Cap.';
            $languageArray['vol_24h']     = 'Vol. 24H';
            $languageArray['open_24h']     = 'Open 24H';
            $languageArray['lowhigh_24h']     = 'Low/High 24H';
            $languageArray['rank']     = 'Rank';
            $languageArray['set_alarm']     = 'Set Alarm';
            $languageArray['currency']     = 'Currency';
            $languageArray['will_check_in']     = 'Will check in';

            foreach ($languageArray as $key => $value) {
                $thisValue = $this->options['CRYPTOWP_language_'.$key] ? $this->options['CRYPTOWP_language_'.$key] : $value;
                $thisID    = __('CRYPTOWP_language_'.$key);
                ?>
                <p>
                    <input id="<?php echo $thisID; ?>" type="text"
                           name="CRYPTOWP_settings[<?php echo $thisID; ?>]"
                           value="<?php echo $thisValue; ?>" />
                    <label
                        for="<?php echo $thisID; ?>"><?php _e( $value, 'CRYPTOWP' ); ?>
                    </label>
                </p>
                <?php
            }
            ?>
            <?php
        }

        function display(){
            $functionArray = array();
            $functionArray['market_cap']     = 'Show Market Cap.';
            $functionArray['volume']         = 'Show Volume';
            $functionArray['open']           = 'Show Open Value';
            $functionArray['low_high']       = 'Show Low/High Value';

            $functionArray['icon']           = 'Show Coin Icon';
            $functionArray['currency_sym']   = 'Show Coin Symbol';
            $functionArray['change']         = 'Show Current Change';
            $functionArray['rank']           = 'Show Rank';

            $functionArray['chart']          = 'Realtime Historical Chart';
            //$functionArray['save_image']     = 'Save Images From Chart';

            foreach ($functionArray as $key => $value) {
                $thisValue = isset($this->options['CRYPTOWP_func_'.$key]) ? 1 : 0;
                $thisID    = __('CRYPTOWP_func_'.$key);
                ?>
                <p>
                    <input id="<?php echo $thisID; ?>" type="checkbox"
                           name="CRYPTOWP_settings[<?php echo $thisID; ?>]" <?php checked($thisValue, 1); ?>
                           value="1" />
                    <label
                        for="<?php echo $thisID; ?>"><?php _e( $value, 'CRYPTOWP' ); ?>
                    </label>
                </p>
                <?php
            }
            ?>
            <?php

        }

        function table(){
            $functionArray = array();
            $functionArray['market_cap']       = 'Show Market Cap.';
            $functionArray['price']            = 'Show Current Price';
            $functionArray['change']           = 'Show Current Change (24h)';
            $functionArray['volume']           = 'Show Volume';
            $functionArray['available_supply'] = 'Show Available Supply';
            $functionArray['mini_graph']       = 'Show Mini Graph (7d)';
            $functionArray['symbol']           = 'Show Currency Symbol';
            $functionArray['favorite']         = 'Show Favorite Coin Feature';

            $functionArray['search']           = 'Show Table Header and pagination';
            $functionArray['mobile_price_only'] = '<b>Mobile :</b> Show only prices on mobile';

            foreach ($functionArray as $key => $value) {
                $thisValue = isset($this->options['CRYPTOWP_table_'.$key]) ? 1 : 0;
                $thisID    = __('CRYPTOWP_table_'.$key);
                ?>
                <p>
                    <input id="<?php echo $thisID; ?>" type="checkbox"
                           name="CRYPTOWP_settings[<?php echo $thisID; ?>]" <?php checked($thisValue, 1); ?>
                           value="1" />
                    <label
                        for="<?php echo $thisID; ?>"><?php _e( $value, 'CRYPTOWP' ); ?>
                    </label>
                </p>
                <?php
            }
            ?>
            <?php

        }

        function preferences(){
            $chart_library = isset($this->options['CRYPTOWP_chart_library']) ? $this->options['CRYPTOWP_chart_library'] : CRYPTOWP_DEFAULT_CHART;
            ?>
            <select name="CRYPTOWP_settings[CRYPTOWP_chart_library]">
                <?php foreach ($this->charts as $value => $name) { ?>
                    <option value="<?php echo $value; ?>" <?php selected($chart_library, $value); ?>><?php _e($name, 'CRYPTOWP') ?></option>
                <?php } ?>
            </select>
            <label>Display with graph chart library or service</label>
            <?php
        }

        function custom_css(){
            $custom_css = isset($this->options['CRYPTOWP_custom_css']) ? $this->options['CRYPTOWP_custom_css'] : '';
            ?>
                <p>
                    <textarea style="width: 300px; height: 200px" name="CRYPTOWP_settings[CRYPTOWP_custom_css]"><?php echo $custom_css; ?></textarea>
                    <br>
                    <label>You can write your custom CSS codes here, please see our <a target="_blank" href="http://imagets.com/wordpress/cryptowp">documention</a> for properly codding.</label>
                </p>
            <?php
        }

        function optional_link(){
            $optional_link = isset($this->options['CRYPTOWP_optional_link']) ? $this->options['CRYPTOWP_optional_link'] : '';
            ?>
                <p>
                    <input type="text" name="CRYPTOWP_settings[CRYPTOWP_optional_link]" value="<?php echo $optional_link; ?>" placeholder="http://...">
                    <label>Add optional link in embed boxes.</label>
                </p>
            <?php

            $optional_link_text = isset($this->options['CRYPTOWP_optional_link_text']) ? $this->options['CRYPTOWP_optional_link_text'] : '';
            ?>
                <p>
                    <input type="text" name="CRYPTOWP_settings[CRYPTOWP_optional_link_text]" value="<?php echo $optional_link_text; ?>" placeholder="Click here...">
                    <label>Define text for optional link</label>
                </p>
            <?php
        }

        function timeout(){
            $timeout = isset($this->options['CRYPTOWP_timeout']) ? $this->options['CRYPTOWP_timeout'] : '';
            ?>
                <p>
                    <input type="text" name="CRYPTOWP_settings[CRYPTOWP_timeout]" value="<?php echo $timeout; ?>" placeholder="60">
                    <label>Enter in second format, it should be like 60.</label>
                </p>
            <?php
        }

        function settings_section_configuration_callback()
        {

        }

        function settings_callback( $input ) {
            return $input;
        }

        function messages() {

            $messages = array();

            /*
            if ( !$this->compatibilities ) {
                $messages[] = array(
                    'type' => 'error',
                    'text' => __('Your webhosting does not fit all plugin requirements. Please see debug and upgrade your hosting.', 'CRYPTOWP')
                );
            }
            */

            if ( count( $messages ) != 0 ) {
                foreach ( $messages as $message ) {

                    if ( !isset ( $message['type'] ) || !isset ( $message['text'] ) )
                        continue;
                    ?>
                    <div class="<?php echo $message['type']; ?>">
                        <p><?php echo $message['text']; ?></p>
                    </div>
                <?php }
            }
        }

        function options_page()
        {
            ?>

            <div class="wrap">
                <?php screen_icon(); ?>
                <h2><?php _e('CryptoWP Settings', 'CRYPTOWP') ?></h2>

                <?php $this->messages(); ?>

                <div id="poststuff">
                    <div id="post-body" class="metabox-holder columns-2">
                        <div id="post-body-content">
                            <div class="meta-box-sortables ui-sortable">
                                <form action="options.php" method="post">

                                    <div class="postbox">
                                          <div class="inside">

                                            <?php //$this->button_handler(); ?>

                                            <?php
                                            //Tabs
                                            settings_fields('CRYPTOWP_settings');
                                            do_settings_sections('CRYPTOWP_settings');
                                            ?>

                                            <p class="submit">
                                                <?php
                                                // Actions
                                                submit_button('', 'primary', 'save', false);
                                                ?>
                                            </p>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div id="postbox-container-1" class="postbox-container">
                            <div class="meta-box-sortables">
                                <div class="postbox">
                                    <h3>Example Usages : </h3>
                                    <div class="inside">
                                        <p>You can display any cryptocurrency with this embed code : </p>
                                        <code>[cryptowp]<b>bitcoin</b>[/cryptowp]</code>
                                        <code>[cryptowp]<b>ripple</b>[/cryptowp]</code>
                                        <p>If you want to display currencies with table, just write this snippet : </p>
                                        <code>[cryptowp_table]<b>all</b>[/cryptowp_table]</code>
                                        <p>If you want to display trade alert form : </p>
                                        <code>[cryptowp_alarm][/cryptowp_alarm]</code>
                                    </div>
                                    <h3><span>Resources & Support</span></h3>
                                    <div class="inside">
                                        <ul>
                                            <li><a href="http://cryptowp.org/" target="_blank">Documantion</a></li>
                                            <li><a href="https://codecanyon.net/downloads" target="_blank">Rate our plugin</a></li>
                                            <li><a href="https://codecanyon.net/user/imagets" target="_blank">Follow me on Codecanyon</a></li>
                                        </ul>
                                        <p>Copyright 2018 <a target="_blank" href="http://imagets.com">imagets.com</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
    }
}