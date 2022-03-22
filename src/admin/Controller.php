<?php
/**
 * The admin-facing functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    DimofinfCommentsVote
 */

namespace DimofinfCommentsVote\admin;
use DimofinfCommentsVote\admin\Settings;

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}

/**
 * The admin-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 */

class Controller
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     *
     * @var string the ID of this plugin
     */
    private $dimofinf_comments_vote;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     *
     * @var string the current version of this plugin
     */
    private $version;

    /**
     * Settings object wrapper.
     *
     * @since    1.0.0
     *
     * @var object settings object
     */
    private $settings;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     *
     * @param string $dimofinf_comments_vote the name of this plugin
     * @param string $version     the version of this plugin
     */
    public function __construct( $dimofinf_comments_vote, $version )
    {
        $this->dimofinf_comments_vote = $dimofinf_comments_vote;
        $this->version = $version;
        $this->settings = new Settings();
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueueStyles()
    {
        wp_enqueue_style( $this->dimofinf_comments_vote, DIMOFINF_COMMENTS_VOTE_PLUGIN_URL . 'assets/css/admin.css', array(), $this->version, 'all' );
    }

    /**
     * Register the admin menu page.
     *
     * @since    1.0.0
     */
    public function addAdminMenu() {
        add_menu_page(
            esc_html__( 'Dimofinf Comments Votes', 'dimofinf' ),
            esc_html__( 'Dimofinf Votes', 'dimofinf' ),
            'manage_options',
            'dimofinf_votes',
            array( $this, 'adminPageContent' ),
            '',
            110
        );
    }

    /**
     * Include admin menu page content.
     *
     * @since    1.0.0
     */
    public function adminPageContent() {
        $form = $this->settings;
        include DIMOFINF_COMMENTS_VOTE_PLUGIN_PATH . '/src/admin/views/settings.php';
    }

    /**
     * Start settings object.
     *
     * @since    1.0.0
     */
    public function registerSettings() {
        $this->settings->initSettings();
    }
}