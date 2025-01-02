<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class ECM_Core {

    public function initialize() {
        // General hooks and initialization logic.
        add_action( 'init', array( $this, 'load_textdomain' ) );
        register_activation_hook( __FILE__, array( $this, 'create_database_tables' ) );
    }

    public function load_textdomain() {
        load_plugin_textdomain( 'easy-cookie-manager', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    
    public function create_database_tables() {
        global $wpdb;
    
        $charset_collate = $wpdb->get_charset_collate();
    
        // Table 1: Consent Logs
        $consent_logs_table = $wpdb->prefix . 'ecm_consent_logs';
        $sql1 = "CREATE TABLE IF NOT EXISTS $consent_logs_table (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            user_ip VARCHAR(45) NOT NULL,
            consent_date DATETIME NOT NULL,
            category VARCHAR(255) NOT NULL,
            status VARCHAR(20) NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";
    
        // Table 2: Cookies Scanned
        $cookies_scanned_table = $wpdb->prefix . 'ecm_cookies_scanned';
        $sql2 = "CREATE TABLE IF NOT EXISTS $cookies_scanned_table (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            cookie_name VARCHAR(255) NOT NULL,
            category VARCHAR(255) NOT NULL,
            expiry VARCHAR(255) NOT NULL,
            scan_date DATETIME NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";
    
        // Run dbDelta for both tables
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql1 );
        dbDelta( $sql2 );
    
    }
    
}
