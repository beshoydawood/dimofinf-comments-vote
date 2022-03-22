<?php
/**
 * Plugin Name:	Dimofinf Comments Vote
 * Plugin URI:	https://www.dimofinf.net
 * Description:	Dimofinf comments vote plugin, Add like and dislike buttons to comments
 * Version:		1.0.0
 * Author:		Dimofinf Team
 * Author URI:	https://www.dimofinf.net
 * Text Domain:	dimofinf-comments-vote
 * Domain Path:	/languages
 *
 * @package           DimofinfCommentsVote
 */

namespace DimofinfCommentsVote;

if ( !defined( 'WPINC' ) ) {
    die;
}

/**
 * Compose autoload file
 */
require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

/**
 * Plugin functions
 */
require plugin_dir_path( __FILE__ ) . 'inc/functions.php';

define('DIMOFINF_COMMENTS_VOTE_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
define('DIMOFINF_COMMENTS_VOTE_PLUGIN_URL', plugin_dir_url( __FILE__ ));
define('DIMOFINF_COMMENTS_VOTE_PLUGIN_VERSION', '1.0.0');


/**
 * The code that runs during plugin activation.
 */
function dimofinf_comments_vote_activate( $network_wide ) {
    utils\Activator::activate( $network_wide );
}

/**
 * The code that runs during plugin deactivation.
 */
function dimofinf_comments_vote_deactivate() {
    utils\Deactivator::deactivate();
}

register_activation_hook( __FILE__, '\DimofinfCommentsVote\dimofinf_comments_vote_activate' );
register_deactivation_hook( __FILE__, '\DimofinfCommentsVote\dimofinf_comments_vote_deactivate' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function dimofinf_comments_vote_run() {
    $plugin = new Main();
    $plugin->run();
}
dimofinf_comments_vote_run();