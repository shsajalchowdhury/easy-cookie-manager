<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class ECM_Admin_Menu {
    
    public function initialize() {
        add_action( 'admin_menu', array( $this, 'add_menu_pages' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
        add_action( 'wp_ajax_ecm_get_stats', array( $this, 'get_realtime_stats' ) );
        
    }

    public function enqueue_scripts( $hook ) {
        if ( strpos( $hook, 'ecm' ) !== false ) {
            wp_enqueue_script( 'ecm-dashboard', ECM_PLUGIN_URL . 'assets/js/ecm-dashboard.js', array( 'jquery' ), ECM_VERSION, true );
            wp_enqueue_script( 'chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array(), '3.7.1', true );
            wp_localize_script( 'ecm-dashboard', 'ecm_ajax', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
        }
    }

    public function get_realtime_stats() {
        $stats = array(
            'total_consents' => $this->get_total_consents_logged(),
            'total_cookies'  => $this->get_total_cookies_scanned(),
        );
        wp_send_json_success( $stats );
    }
    
    public function add_menu_pages() {
        // Add main menu and submenus.
        add_menu_page(
            __( 'Easy Cookie Manager', 'easy-cookie-manager' ),
            __( 'Easy Cookie Manager', 'easy-cookie-manager' ),
            'manage_options',
            'ecm-dashboard',
            array( $this, 'render_dashboard' ),
            'dashicons-shield',
            20
        );

        // Dashboard (sub-menu under main menu).
        add_submenu_page(
            'ecm-dashboard',
            __( 'Dashboard', 'easy-cookie-manager' ),
            __( 'Dashboard', 'easy-cookie-manager' ),
            'manage_options',
            'ecm-dashboard',
            array( $this, 'render_dashboard' )
        );

        // Cookie Banner.
        add_submenu_page(
            'ecm-dashboard',
            __( 'Cookie Banner', 'easy-cookie-manager' ),
            __( 'Cookie Banner', 'easy-cookie-manager' ),
            'manage_options',
            'ecm-banner',
            array( $this, 'render_cookie_banner' )
        );

        // Cookie Scanning.
        add_submenu_page(
            'ecm-dashboard',
            __( 'Cookie Scanning', 'easy-cookie-manager' ),
            __( 'Cookie Scanning', 'easy-cookie-manager' ),
            'manage_options',
            'ecm-scanning',
            array( $this, 'render_cookie_scanning' )
        );

        // Logs & Reports.
        add_submenu_page(
            'ecm-dashboard',
            __( 'Logs & Reports', 'easy-cookie-manager' ),
            __( 'Logs & Reports', 'easy-cookie-manager' ),
            'manage_options',
            'ecm-logs',
            array( $this, 'render_logs' )
        );

        // Preference Center.
        add_submenu_page(
            'ecm-dashboard',
            __( 'Preference Center', 'easy-cookie-manager' ),
            __( 'Preference Center', 'easy-cookie-manager' ),
            'manage_options',
            'ecm-preference-center',
            array( $this, 'render_preference_center' )
        );

        // Compliance.
        add_submenu_page(
            'ecm-dashboard',
            __( 'Compliance', 'easy-cookie-manager' ),
            __( 'Compliance', 'easy-cookie-manager' ),
            'manage_options',
            'ecm-compliance',
            array( $this, 'render_compliance' )
        );

        // Integration.
        add_submenu_page(
            'ecm-dashboard',
            __( 'Integration', 'easy-cookie-manager' ),
            __( 'Integration', 'easy-cookie-manager' ),
            'manage_options',
            'ecm-integration',
            array( $this, 'render_integration' )
        );

        // Accessibility (Pro).
        add_submenu_page(
            'ecm-dashboard',
            __( 'Accessibility (Pro)', 'easy-cookie-manager' ),
            __( 'Accessibility (Pro)', 'easy-cookie-manager' ),
            'manage_options',
            'ecm-accessibility',
            array( $this, 'render_accessibility' )
        );

        // Banner Display Rules (Pro).
        add_submenu_page(
            'ecm-dashboard',
            __( 'Banner Display Rules (Pro)', 'easy-cookie-manager' ),
            __( 'Banner Display Rules (Pro)', 'easy-cookie-manager' ),
            'manage_options',
            'ecm-display-rules',
            array( $this, 'render_display_rules' )
        );

        // Settings (Pro).
        add_submenu_page(
            'ecm-dashboard',
            __( 'Settings (Pro)', 'easy-cookie-manager' ),
            __( 'Settings (Pro)', 'easy-cookie-manager' ),
            'manage_options',
            'ecm-settings',
            array( $this, 'render_settings' )
        );
    }


    //Get Consent Logged
    private function get_total_consents_logged() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ecm_consent_logs';
    
        // Check if the table exists.
        if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) !== $table_name ) {
            return 0;
        }
    
        // Fetch the total number of consents.
        $count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );
        return $count ? $count : 0;
    }
    //Get Cookie Scanned
    private function get_total_cookies_scanned() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ecm_cookies_scanned';
    
        // Check if the table exists.
        if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) !== $table_name ) {
            return 0;
        }
    
        // Fetch the total number of scanned cookies.
        $count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );
        return $count ? $count : 0;
    }
    //Get Compliance Status
    private function get_compliance_status() {
        $gdpr_enabled = get_option( 'ecm_gdpr_enabled', false );
        $ccpa_enabled = get_option( 'ecm_ccpa_enabled', false );
    
        if ( $gdpr_enabled && $ccpa_enabled ) {
            return '<span style="color:green;">✔️ Fully Compliant</span>';
        } elseif ( $gdpr_enabled || $ccpa_enabled ) {
            return '<span style="color:orange;">⚠️ Partially Compliant</span>';
        } else {
            return '<span style="color:red;">❌ Not Compliant</span>';
        }
    }
    
        
    // Dashboard Page.
    public function render_dashboard() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Dashboard', 'easy-cookie-manager' ); ?></h1>
            <?php $this->show_admin_notices(); ?>
    
            <p><?php esc_html_e( 'Welcome to Easy Cookie Manager Dashboard. Below you can see quick stats and actions.', 'easy-cookie-manager' ); ?></p>
    
            <h2><?php esc_html_e( 'Quick Stats', 'easy-cookie-manager' ); ?></h2>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Total Consents Logged:', 'easy-cookie-manager' ); ?></th>
                        <td id="ecm-total-consents"><?php echo esc_html( $this->get_total_consents_logged() ); ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Cookies Scanned:', 'easy-cookie-manager' ); ?></th>
                        <td id="ecm-total-cookies"><?php echo esc_html( $this->get_total_cookies_scanned() ); ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'GDPR/CCPA Compliance:', 'easy-cookie-manager' ); ?></th>
                        <td><?php echo $this->get_compliance_status(); ?></td>
                    </tr>
                </tbody>
            </table>
    
            <h2><?php esc_html_e( 'Quick Actions', 'easy-cookie-manager' ); ?></h2>
            <p>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=ecm-banner' ) ); ?>" class="button button-primary">
                    <?php esc_html_e( 'Edit Cookie Banner', 'easy-cookie-manager' ); ?>
                </a>
                <a href="#" id="ecm-run-scan" class="button button-secondary">
                    <?php esc_html_e( 'Run Cookie Scan', 'easy-cookie-manager' ); ?>
                </a>
            </p>
    
            <h2><?php esc_html_e( 'Consent Summary', 'easy-cookie-manager' ); ?></h2>
            <canvas id="ecm-consent-chart" width="400" height="200"></canvas>
        </div>
        <?php
    }
    
    public function show_admin_notices() {
        if ( isset( $_GET['ecm_notice'] ) ) {
            $message = sanitize_text_field( $_GET['ecm_notice'] );
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html( $message ) . '</p></div>';
        }
    }
    
    

    // Other Pages (Placeholders for now).
    public function render_cookie_banner() {
        echo '<h1>' . esc_html__( 'Cookie Banner Settings', 'easy-cookie-manager' ) . '</h1>';
    }

    public function render_cookie_scanning() {
        echo '<h1>' . esc_html__( 'Cookie Scanning', 'easy-cookie-manager' ) . '</h1>';
    }

    public function render_logs() {
        echo '<h1>' . esc_html__( 'Logs & Reports', 'easy-cookie-manager' ) . '</h1>';
    }

    public function render_preference_center() {
        echo '<h1>' . esc_html__( 'Preference Center', 'easy-cookie-manager' ) . '</h1>';
    }

    public function render_compliance() {
        echo '<h1>' . esc_html__( 'Compliance Settings', 'easy-cookie-manager' ) . '</h1>';
    }

    public function render_integration() {
        echo '<h1>' . esc_html__( 'Integration Settings', 'easy-cookie-manager' ) . '</h1>';
    }

    public function render_accessibility() {
        echo '<h1>' . esc_html__( 'Accessibility (Pro)', 'easy-cookie-manager' ) . '</h1>';
    }

    public function render_display_rules() {
        echo '<h1>' . esc_html__( 'Banner Display Rules (Pro)', 'easy-cookie-manager' ) . '</h1>';
    }

    public function render_settings() {
        echo '<h1>' . esc_html__( 'Settings (Pro)', 'easy-cookie-manager' ) . '</h1>';
    }

    // Enqueue Admin Styles.
    public function enqueue_admin_styles( $hook ) {
        if ( strpos( $hook, 'ecm' ) !== false ) {
            wp_enqueue_style( 'ecm-admin-styles', ECM_PLUGIN_URL . 'assets/css/ecm-admin.css', array(), ECM_VERSION );
        }
    }
}
