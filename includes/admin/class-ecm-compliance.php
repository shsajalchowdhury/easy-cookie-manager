public function render_compliance() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Compliance Settings', 'easy-cookie-manager' ); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields( 'ecm_compliance_settings' );
            do_settings_sections( 'ecm_compliance' );
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

public function register_compliance_settings() {
    register_setting( 'ecm_compliance_settings', 'ecm_gdpr_enabled' );
    register_setting( 'ecm_compliance_settings', 'ecm_ccpa_enabled' );

    add_settings_section(
        'ecm_compliance_section',
        __( 'Global Compliance Settings', 'easy-cookie-manager' ),
        null,
        'ecm_compliance'
    );

    add_settings_field(
        'ecm_gdpr_enabled',
        __( 'Enable GDPR Compliance', 'easy-cookie-manager' ),
        array( $this, 'render_gdpr_field' ),
        'ecm_compliance',
        'ecm_compliance_section'
    );

    add_settings_field(
        'ecm_ccpa_enabled',
        __( 'Enable CCPA Compliance', 'easy-cookie-manager' ),
        array( $this, 'render_ccpa_field' ),
        'ecm_compliance',
        'ecm_compliance_section'
    );
}

public function render_gdpr_field() {
    $value = get_option( 'ecm_gdpr_enabled', false );
    ?>
    <input type="checkbox" name="ecm_gdpr_enabled" value="1" <?php checked( 1, $value ); ?>>
    <?php
}

public function render_ccpa_field() {
    $value = get_option( 'ecm_ccpa_enabled', false );
    ?>
    <input type="checkbox" name="ecm_ccpa_enabled" value="1" <?php checked( 1, $value ); ?>>
    <?php
}
