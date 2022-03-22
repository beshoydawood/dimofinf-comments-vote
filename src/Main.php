<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 *
 * @package    DimofinfCommentsVote
 *
 */

namespace DimofinfCommentsVote;

class Main {
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     *
     * @var DimofinfCommentsVote\utils\Loader maintains and registers all hooks for the plugin
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     *
     * @var string the string used to uniquely identify this plugin
     */
    protected $dimofinf_comments_vote;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     *
     * @var string the current version of the plugin
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        $this->dimofinf_comments_vote = 'dimofinf-comments-vote';
        $this->version = '1.0.0';
        $this->loader = new utils\Loader();
        $this->setLocale();
        $this->defineAdminHooks();
        $this->definePublicHooks();
        $this->defineApiHooks();

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the utils\Internationalization class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     */
    private function setLocale()
    {
        $plugin_i18n = new utils\Internationalization();

        $this->loader->addAction( 'plugins_loaded', $plugin_i18n, 'loadPluginTextdomain' );
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     */
    private function defineAdminHooks()
    {
        $plugin_admin = new admin\Controller( $this->getDimofinfCommentsVote(), $this->getVersion() );

        $this->loader->addAction( 'admin_enqueue_scripts', $plugin_admin, 'enqueueStyles' );
        $this->loader->addAction( 'admin_menu', $plugin_admin, 'addAdminMenu' );
        $this->loader->addAction( 'admin_init', $plugin_admin, 'registerSettings' );
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     */
    private function definePublicHooks()
    {
        $plugin_public = new front\Controller( $this->getDimofinfCommentsVote(), $this->getVersion() );

        $this->loader->addAction( 'wp_enqueue_scripts', $plugin_public, 'enqueueStyles' );
        $this->loader->addAction( 'wp_ajax_dvc_insert_vote', $plugin_public, 'ajaxVote' );
        $this->loader->addAction( 'wp_ajax_nopriv_dvc_insert_vote', $plugin_public, 'ajaxVote' );
        $this->loader->addFilter( 'comment_text', $plugin_public, 'votesHookComments', 40, 2 );
    }

    /**
     * Register all of the hooks related to the Rest API functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {
        $plugin_public = new front\Controller( $this->get_plugin_name(), $this->get_version() );
        $this->loader->addAction( 'wp_enqueue_scripts', $plugin_public, 'registerAssets' );

    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function defineApiHooks() {
        $api = new api\Endpoint();
        $this->loader->addAction( 'rest_api_init', $api, 'registerRoute' );
        $this->loader->addAction( 'rest_api_init', $api, 'registerFields' );
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     *
     * @return string the name of the plugin
     */
    public function getDimofinfCommentsVote()
    {
        return $this->dimofinf_comments_vote;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     *
     * @return DimofinfCommentsVote\utils\Loader orchestrates the hooks of the plugin
     */
    public function getLoader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     *
     * @return string the version number of the plugin
     */
    public function getVersion()
    {
        return $this->version;
    }
}