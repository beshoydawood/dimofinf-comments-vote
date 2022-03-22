jQuery(document).ready(function(){
    jQuery(document).on("click", ".dcv-btn", function(e){
        e.preventDefault();
        var task = jQuery(this).data("type");
        var comment_id = jQuery(this).parent( '.dcv-vote' ).data( 'comment' );
        var nonce = jQuery(this).parent( '.dcv-vote' ).data( 'nonce' );
        console.log(nonce)
        jQuery(this).parent( '.dcv-vote' ).addClass("loading");

        jQuery.ajax({
            type : "post",
            async : false,
            dataType : "json",
            url : DVC_VAR.ajaxurl,
            data : {action: "dvc_insert_vote", dvc_vote : task, dvc_comment_id : comment_id, nonce: nonce},
            success: function(response) {
                jQuery(this).parent( '.dcv-vote' ).removeClass("loading");
            }
        });
    });
});