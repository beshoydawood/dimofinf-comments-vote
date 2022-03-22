<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    DimofinfCommentsVote
 */

namespace DimofinfCommentsVote\front;
use DimofinfCommentsVote\admin\Settings;
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}

/**
 * The public-facing functionality of the plugin.
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
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueueStyles()
    {
        wp_enqueue_style( $this->dimofinf_comments_vote, DIMOFINF_COMMENTS_VOTE_PLUGIN_URL . 'assets/css/public.css', array(), $this->version, 'all' );
        wp_enqueue_script( $this->dimofinf_comments_vote, DIMOFINF_COMMENTS_VOTE_PLUGIN_URL . 'assets/js/public.js', array( 'jquery' ), $this->version, true );
        wp_localize_script( $this->dimofinf_comments_vote, 'DVC_VAR',
            array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'current_ip'    => dimofinf_comments_vote_get_ip()
            )
        );
    }

    /**
     * Include the vote template.
     *
     * @since    1.0.0
     */
    public function voteView() {
        $view_settings = Settings::getOption( 'dimofinf-settings', 'dcv_display', 'both' );
        $counter_settings = Settings::getOption( 'dimofinf-settings', 'dcv_counter', 'both' );
        $votes = json_decode(get_comment_meta( get_comment_ID(), 'dcv_votes', true ), true);
        $like_count =  get_comment_meta( get_comment_ID(), 'dcv_vote_like', true );
        $like_count_markup = 'dislikes' !== $counter_settings ? sprintf( '<span class="count">%s</span>', empty( $like_count ) ? 0 : $like_count ) : '';
        $like = 'dislikes' !== $view_settings ? sprintf( '<a href="#" data-type="like" class="dcv-btn dcv-like">%s %s</a>',
        '<svg x="0px" y="0px" viewBox="0 0 58 58" style="enable-background:new 0 0 58 58;">
	        <path d="M9.5,43c-2.757,0-5,2.243-5,5s2.243,5,5,5s5-2.243,5-5S12.257,43,9.5,43z"/>
            <path d="M56.5,35c0-2.495-1.375-3.662-2.715-4.233C54.835,29.85,55.5,28.501,55.5,27c0-2.757-2.243-5-5-5H36.134l0.729-3.41
		    c0.973-4.549,0.334-9.716,0.116-11.191C36.461,3.906,33.372,0,30.013,0h-0.239C28.178,0,25.5,0.909,25.5,7c0,14.821-6.687,15-7,15
		    h0h-1v-2h-16v38h16v-2h28c2.757,0,5-2.243,5-5c0-1.164-0.4-2.236-1.069-3.087C51.745,47.476,53.5,45.439,53.5,43
		    c0-1.164-0.4-2.236-1.069-3.087C54.745,39.476,56.5,37.439,56.5,35z M3.5,56V22h12v34H3.5z"/></svg>',
            $like_count_markup
        ) : '';
        $dislike_count = get_comment_meta( get_comment_ID(), 'dcv_vote_dislike', true );
        $dislike_count_markup = 'likes' !== $counter_settings ? sprintf( '<span class="count">%s</span>', empty( $dislike_count ) ? 0 : $dislike_count ) : '';
        $dislike = 'likes' !== $view_settings ? sprintf( '<a href="#" data-type="dislike" class="dcv-btn dcv-dislike">%s %s</a>', '<svg x="0px" y="0px" viewBox="0 0 58 58" style="enable-background:new 0 0 58 58;" >
            <path d="M40.5,0v2h-28c-2.757,0-5,2.243-5,5c0,1.164,0.4,2.236,1.069,3.087C6.255,10.524,4.5,12.561,4.5,15
                c0,1.164,0.4,2.236,1.069,3.087C3.255,18.524,1.5,20.561,1.5,23c0,2.495,1.375,3.662,2.715,4.233C3.165,28.15,2.5,29.499,2.5,31
                c0,2.757,2.243,5,5,5h14.366l-0.729,3.41c-0.973,4.551-0.334,9.717-0.116,11.191C21.539,54.094,24.628,58,27.987,58h0.239
                c1.596,0,4.274-0.909,4.274-7c0-14.82,6.686-15,7-15h0h1v2h16V0H40.5z M54.5,36h-12V2h12V36z"/>
            <path d="M48.5,15c2.757,0,5-2.243,5-5s-2.243-5-5-5s-5,2.243-5,5S45.743,15,48.5,15z"/>
        </svg>',
            $dislike_count_markup
        ) : '';
        return sprintf( '<div class="dcv-vote" data-comment="%s" data-nonce="%s">%s %s</div>',
            get_comment_ID(),
            wp_create_nonce( 'dcv_vote_' . get_comment_ID() ),
            $like,
            $dislike );
    }


    /**
     * Add the vote template to each comment.
     *
     * @since    1.0.0
     */
    public function votesHookComments( $comment_text, $comment ) {
        if( defined('REST_REQUEST') ) {
            return $comment_text;
        }
        $enable = Settings::getOption( 'dimofinf-settings', 'dcv_display', 'both' );
        ob_start();
        $html = $this->voteView();

        echo  dimofinf_comments_vote_sanatize_html($comment_text).$html;

        echo ob_get_clean();
    }

    /**
     * Ajax method to insert votes.
     *
     * @since    1.0.0
     */
    public function ajaxVote() {
        $vote = filter_input( INPUT_POST, 'dvc_vote', FILTER_SANITIZE_SPECIAL_CHARS  );
        $comment_id = filter_input( INPUT_POST, 'dvc_comment_id', FILTER_SANITIZE_NUMBER_INT  );
        if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'dcv_vote_' . (int)$comment_id ) ) {
            die( __( 'Nonce check fails', 'dimofinf-comments-vote' ) );
        }

        echo dimofinf_comments_vote_insert_vote( $comment_id, $vote );
        wp_die();
    }

}