<?php

/**
 * Get plugin options
 *
 * @return string
 */
function dimofinf_comments_vote_get_option( $section, $option, $default = '' ) {
    $options = get_option( $section );
    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }
}

/**
 * Sanitize output before print
 * TODO: Add real sanatizer
 *
 * @return string
 */
function dimofinf_comments_vote_sanatize_html( $html ) {
    return $html;
}

/**
 * Get the actual ip address
 *
 * @return string
 */
function dimofinf_comments_vote_get_ip() {
    if (getenv('HTTP_CLIENT_IP')) {
        $ip = getenv('HTTP_CLIENT_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('HTTP_X_FORWARDED')) {
        $ip = getenv('HTTP_X_FORWARDED');
    } elseif (getenv('HTTP_FORWARDED_FOR')) {
        $ip = getenv('HTTP_FORWARDED_FOR');
    } elseif (getenv('HTTP_FORWARDED')) {
        $ip = getenv('HTTP_FORWARDED');
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    return $ip;
}

/**
 * Insert vote
 *
 * TODO: Insert vote in custom table for long queries.
 * @return string|array
 */
function dimofinf_comments_vote_insert_vote( $comment_id, $vote, $user_ip = '' ) {
    if( empty( $user_ip ) ) {
        $user_ip = dimofinf_comments_vote_get_ip();
    }
    $vote_process = dimofinf_comments_vote_get_option( 'dimofinf-settings', 'dcv_vote', 'ip' );
    if( ! is_user_logged_in() && 'logged' === $vote_process ) {
        return esc_html__( 'You need to login to add vote', 'dimofinf-comments-vote' );
    }
    $voted = false;
    $user_id = get_current_user_id();
    $votes_meta = get_comment_meta( $comment_id, 'dcv_votes', true );
    $votes = ! empty( $votes_meta ) ? json_decode( $votes_meta, true ) : array();
    $current_count = get_comment_meta( $comment_id, 'dcv_vote_'.$vote, true );
    $vote_hash = bin2hex(random_bytes(10));
    if( empty( $current_count ) ) {
        add_comment_meta( $comment_id, 'dcv_vote_'.$vote, 0 );
    }
    if( 'ip' === $vote_process ) {
        foreach ($votes as $key => $_vote) {
            if (in_array($user_id, $_vote) || in_array($user_ip, $_vote)) {
                $voted = true;
                if ($vote !== $_vote['vote']) {
                    dimofinf_comments_vote_remove_old_vote( $comment_id, $_vote['vote'] );
                    unset($votes[$key]);
                    setcookie( 'dcv_comment_'.$comment_id, null, -1 );
                    setcookie( 'dcv_comment_'.$comment_id.'_hash', null, -1 );
                    $votes[] = array('vote_hash' => $vote_hash, 'vote' => esc_html($vote), 'user_ip' => $user_ip, 'user_id' => $user_id);
                    update_comment_meta($comment_id, 'dcv_votes', json_encode($votes));
                    $current_count = 0 === $current_count ? 1 : $current_count;
                    update_comment_meta( $comment_id, 'dcv_vote_'.$vote, $current_count + 1 );
                    setcookie( 'dcv_comment_'.$comment_id.'_hash', $vote_hash, strtotime("+1 week"), '/' );
                    setcookie( 'dcv_comment_'.$comment_id, esc_attr( $vote ), strtotime("+1 week"), '/' );
                    return esc_html__('Vote updated', 'dimofinf-comments-vote');
                }
            }
        }
    } elseif( 'cookie' === $vote_process ) {
        if( isset( $_COOKIE['dcv_comment_'.$comment_id] ) ) {
            $voted = true;
            if( $_COOKIE['dcv_comment_'.$comment_id] !== $vote ) {
                foreach ($votes as $key => $_vote) {
                    if( $_vote['vote_hash'] === $_COOKIE['dcv_comment_'.$comment_id.'_hash'] ) {
                        dimofinf_comments_vote_remove_old_vote( $comment_id, $_vote['vote'] );
                        unset($votes[$key]);
                        $votes[] = array('vote_hash' => $vote_hash, 'vote' => esc_html($vote), 'user_ip' => $user_ip, 'user_id' => $user_id);
                        update_comment_meta($comment_id, 'dcv_votes', json_encode($votes));
                        $current_count = 0 === $current_count ? 1 : $current_count;
                        update_comment_meta( $comment_id, 'dcv_vote_'.$vote, $current_count + 1 );
                        setcookie( 'dcv_comment_'.$comment_id.'_hash', $vote_hash, strtotime("+1 week"), '/' );
                        setcookie( 'dcv_comment_'.$comment_id, esc_attr( $vote ), strtotime("+1 week"), '/' );
                        return esc_html__('Vote updated', 'dimofinf-comments-vote');
                    }
                }
            }
        }
    }
    if( ! $voted ) {
        $votes[] = array('vote_hash' => $vote_hash, 'vote' => esc_html( $vote ), 'user_ip' => $user_ip, 'user_id' => $user_id );
        setcookie( 'dcv_comment_'.$comment_id, esc_attr( $vote ), strtotime("+1 week"), '/' );
        setcookie( 'dcv_comment_'.$comment_id.'_hash', $vote_hash, strtotime("+1 week"), '/' );
        update_comment_meta( $comment_id, 'dcv_vote_'.$vote, $current_count + 1 );
        update_comment_meta( $comment_id, 'dcv_votes', json_encode( $votes ));
    }  else {
        return esc_html__( 'You already voted on this comment', 'dimofinf-comments-vote' );
    }

    return $votes;
}

/**
 * Reduce the number of votes for given vote type
 *
 * @return null
 */
function dimofinf_comments_vote_remove_old_vote( $comment_id, $vote ) {
    $old_numb = get_comment_meta( $comment_id, 'dcv_vote_'.$vote, true );
    if( empty( $current_count ) ) {
        add_comment_meta( $comment_id, 'dcv_vote_'.$vote, 0 );
    }
    $old_numb = 0 === $old_numb ? 1 : $old_numb;
    update_comment_meta( $comment_id, 'dcv_vote_'.$vote, $old_numb - 1 );
}