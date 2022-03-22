<?php
/**
 * The Reset API endpoint.
 *
 * @since      1.0.0
 *
 * @package    DimofinfCommentsVote
 */

namespace DimofinfCommentsVote\api;

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}

/**
 * The Reset API endpoint.
 *
 */
class Endpoint extends \WP_REST_Controller {

    protected $namespace;

    function __construct() {
        $this->namespace = 'dcv/v1';
    }

    public function registerRoute(){
        register_rest_route($this->namespace, '/like', array(
            array(
                'methods'   => \WP_REST_Server::CREATABLE,
                'callback'  => array($this, 'postLike'),
                'permission_callback' => '__return_true',
                'args' => array(
                    'comment_id' => array(
                        'required' => true,
                        'validate_callback' => 'is_numeric'
                    )
                )
            )
        ));

        register_rest_route($this->namespace, '/dislike', array(
            array(
                'methods'   => \WP_REST_Server::CREATABLE,
                'callback'  => array($this, 'postDislike'),
                'permission_callback' => '__return_true',
                'args' => array(
                    'comment_id' => array(
                        'required' => true,
                        'validate_callback' => 'is_numeric'
                    )
                )
            )
        ));
    }

    public function registerFields() {
        register_rest_field( 'comment', 'dcv-likes', array(
                'get_callback'    => array( $this, 'getLikes' ),
                'schema'          => null,
            )
        );

        register_rest_field( 'comment', 'dcv-dislikes', array(
                'get_callback'    => array( $this, 'getDislikes' ),
                'schema'          => null,
            )
        );

        register_rest_field( 'comment', 'dcv-userVote', array(
                'get_callback'    => array( $this, 'getUserVote' ),
                'schema'          => null,
            )
        );
    }

    public function getLikes( $request ) {
        $comment_id = (int)$request['id'];

        return get_comment_meta( $comment_id, 'dcv_vote_like', true );
    }

    public function getDislikes( $request ) {
        $comment_id = (int)$request['id'];

        return get_comment_meta( $comment_id, 'dcv_vote_dislike', true );
    }

    public function getUserVote( $request ) {
        $comment_id = (int)$request['id'];

        return esc_attr( $_COOKIE['dcv_comment_'.$comment_id] );
    }

    public function commentFound( $comment_id ) {
        $comment = get_comment( $comment_id );
        if( isset( $comment ) ) {
            return true;
        }

        return false;
    }

    public function postLike( $request ) {
        $comment_id = $request->get_param( 'comment_id' );
        if( ! $this->commentFound() ) {
            return new \WP_Error(
                'dcv_like_fails',
                esc_html__( 'Sorry, Comment ID does not exist', 'dimofinf-comments-vote'),
                array( 'status' => 403 )
            );
        }
        $inserted = dimofinf_comments_vote_insert_vote( $comment_id, 'like' );

        return new \WP_REST_Response($inserted, 200);
    }

    public function postDislike( $request ) {
        $comment_id = $request->get_param( 'comment_id' );
        if( ! $this->commentFound() ) {
            return new \WP_Error(
                'dcv_dislike_fails',
                esc_html__( 'Sorry, Comment ID does not exist', 'dimofinf-comments-vote'),
                array( 'status' => 403 )
            );
        }
        $inserted = dimofinf_comments_vote_insert_vote( $comment_id, 'dislike' );

        return new \WP_REST_Response($inserted, 200);
    }
}