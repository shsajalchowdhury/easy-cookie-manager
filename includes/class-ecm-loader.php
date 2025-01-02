<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class ECM_Loader {

    public function run() {
        // Register activation hook
        register_activation_hook( ECM_PLUGIN_FILE, array( $this, 'activate_plugin' ) );

        // Core initialization.
        require_once ECM_PLUGIN_DIR . 'includes/class-ecm-core.php';
        $core = new ECM_Core();
        $core->initialize();

        // Admin-only logic.
        if ( is_admin() ) {
            require_once ECM_PLUGIN_DIR . 'includes/admin/class-ecm-admin-menu.php';
            $admin_menu = new ECM_Admin_Menu();
            $admin_menu->initialize();
        }
    }

    /**
     * Handles plugin activation logic.
     */
    public function activate_plugin() {
        // Ensure admin files are loaded for database setup.
        require_once ECM_PLUGIN_DIR . 'includes/admin/class-ecm-admin-menu.php';
        $admin_menu = new ECM_Admin_Menu();
        $admin_menu->create_database_tables();
    }
}
