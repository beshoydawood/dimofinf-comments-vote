<?php
/**
 * The admin-settings functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    DimofinfCommentsVote
 */

namespace DimofinfCommentsVote\admin;

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}

/**
 * The admin settings functionality of the plugin.
 *
 */

class Settings {

    /**
     * settings sections array
     *
     * @var array
     */
    protected $settings = array();

    /**
     * The admin settings functionality of the plugin.
     *
     * @since     1.0.0
     *
     * Set settings array.
     */
    public function __construct() {
        $this->settings = array(
          'dimofinf-settings'   => array(
              array(
                  'name'                => 'dcv_display',
                  'label'               => esc_html__( 'Display vote icons', 'dimofinf-comments-vote' ),
                  'type'                => 'select',
                  'sanitize_callback'   => 'esc_attr',
                  'default'             => 'both',
                  'options'     => array(
                      'likes'      =>  esc_html__( 'Likes Only', 'dimofinf-comments-vote' ),
                      'dislikes'   =>  esc_html__( 'Dislikes Only', 'dimofinf-comments-vote' ),
                      'both'       =>  esc_html__( 'Both', 'dimofinf-comments-vote' ),
                  )
              ),
              array(
                  'name'                => 'dcv_counter',
                  'label'               => esc_html__( 'Display Counts', 'dimofinf-comments-vote' ),
                  'type'                => 'select',
                  'sanitize_callback'   => 'esc_attr',
                  'default'             => 'both',
                  'options'     => array(
                      'likes'      =>  esc_html__( 'Likes Only', 'dimofinf-comments-vote' ),
                      'dislikes'   =>  esc_html__( 'Dislikes Only', 'dimofinf-comments-vote' ),
                      'both'       =>  esc_html__( 'Both', 'dimofinf-comments-vote' ),
                  )
              ),
              array(
                  'name'                => 'dcv_vote',
                  'label'               => esc_html__( 'Vote restriction', 'dimofinf-comments-vote' ),
                  'type'                => 'select',
                  'sanitize_callback'   => 'esc_attr',
                  'default'             => 'ip',
                  'options'     => array(
                      'logged'      =>  esc_html__( 'Logged users only', 'dimofinf-comments-vote' ),
                      'ip'          =>  esc_html__( 'IP Restriction', 'dimofinf-comments-vote' ),
                      'cookie'      =>  esc_html__( 'Cookie Restriction', 'dimofinf-comments-vote' ),
                  )
              ),

          ),

        );
    }

    /**
     * Hooked function to register settings to WordPress options table.
     *
     * @since    1.0.0
     */
    public function initSettings() {
        $this->registerSettings();
    }

    /**
     * Register settings array to core.
     *
     * @since    1.0.0
     */
    public function registerSettings() {

        foreach ( $this->settings as $section => $field  ) {
            if ( false === get_option( $section ) ) {
                add_option( $section );
            }
            register_setting( $section, $section, array($this, 'sanitizeOptions') );
            add_settings_section( $section, '', null, $section );

            foreach ( $field as $setting ) {
                $type = isset( $setting['type'] ) ? $setting['type'] : 'text';
                $label = isset( $setting['label'] ) ? $setting['label'] : '';
                $callback = isset( $setting['callback'] ) ? $setting['callback'] : array($this, 'callback_' . $type);

                $args = array(
                    'desc'              => isset( $setting['desc'] ) ? $setting['desc'] : '',
                    'name'              => $setting['name'],
                    'label'             => $label,
                    'id'                => "{$section}[{$setting['name']}]",
                    'section'           => $section,
                    'options'           => isset( $setting['options'] ) ? $setting['options'] : '',
                    'size'              => isset( $setting['size'] ) ? $setting['size'] : null,
                    'std'               => isset( $setting['default'] ) ? $setting['default'] : '',
                    'sanitize_callback' => isset( $setting['sanitize_callback'] ) ? $setting['sanitize_callback'] : '',
                    'type'              => $type,

                );

                add_settings_field( "{$section}[{$setting['name']}]", $label, $callback, $section, $section, $args );
            }
        }
    }

    /**
     * Hooked function to register settings to WordPress options table.
     *
     * @since    1.0.0
     */
    public function sanitizeOptions( $options ) {
        return $options;
    }

    public function callback_select( $args ) {
        $value 	= esc_attr( $this->getOption(  $args['section'], $args['name'], $args['std'] ) );
        printf( '<select class="dvc-select" id="%1$s" name="%2$s">', esc_attr( $args['name'] ), esc_attr( $args['id'] ) );

        foreach ( $args['options'] as $key => $label ) {
            printf( '<option value="%s"%s>%s</option>', $key, selected( $value, $key, false ), $label );
        }

        printf( '</select>' );
    }

    public static function getOption( $section, $option, $default = '' ) {
        $options = get_option( $section );
        if ( isset( $options[$option] ) ) {
            return $options[$option];
        }
    }

    public function showForm() { ?>
            <div class="metabox-holder">
                <form method="post" action="options.php" enctype="multipart/form-data">
                    <?php foreach ( $this->settings as $section => $field ) {
                        settings_fields( $section );
                        do_settings_sections( $section );
                    }?>
                    <?php submit_button(); ?>
                </form>
            </div>
            <?php
    }

}