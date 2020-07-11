var BAP_Platform = {

    /**
     * Init
     */
    init: function () {

        this.activityLogExtenesion();
        this.announcementModal();

    },

    announcementModal: function(){

        $(window).load(function()
        {


            if(Cookies.get('announcementModal') != 'seen' ) {
                $('#announcement-message').modal();

                Cookies.set('announcementModal','seen',{
                    expires : 7
                });
            }
        });

    },

    /**
     * Activity Log Extension
     */
    activityLogExtenesion: function () {

        $(document.body).on('click', '.open-in-modal a', function (e) {

            e.preventDefault();

            var link = $(this);

            $('#genericModal .modal-title').html($.i18n._('entity_activity_log'));
            $('#genericModal .modal-body').load(link.attr('href'), function (result) {

                $('#genericModal').modal({show: true});

                return true;
            });
        });

    },



    /**
     * Comments Extension function
     *
     * @param entityId
     */
    commentsExtension: function (entityId, entityClass) {

        $('#entity-comments-container').comments({



            textareaPlaceholderText: $.i18n._('comment_ext_add_a_commnet'),
            newestText: $.i18n._('comment_ext_newest'),
            oldestText: $.i18n._('comment_ext_oldest'),
            popularText: $.i18n._('comment_ext_popular'),
            attachmentsText: $.i18n._('comment_ext_attachments'),
            sendText: $.i18n._('comment_ext_send'),
            replyText: $.i18n._('comment_ext_reply'),
            editText: $.i18n._('comment_ext_edit'),
            editedText: $.i18n._('comment_ext_edited'),
            youText: $.i18n._('comment_ext_you'),
            saveText: $.i18n._('comment_ext_save'),
            deleteText: $.i18n._('comment_ext_delete'),
            viewAllRepliesText: $.i18n._('comment_ext_show_all'),
            hideRepliesText: $.i18n._('comment_ext_hide_replies'),
            noCommentsText: $.i18n._('comment_ext_no_comments'),
            noAttachmentsText: $.i18n._('comment_ext_no_attachments'),
            attachmentDropText: $.i18n._('comment_ext_drop_file_here'),

            enablePinging: false,
            enableDeletingCommentWithReplies: false,
            enableAttachments: false, // Not implemented
            deleteButtonColor: '#fb483a',

            timeFormatter: function (time) {
                return moment(time).fromNow();
            },

            /**
             * Get Comments
             * @param success
             * @param error
             */
            getComments: function (success, error) {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: '/core/extensions/comments/get-comments',
                    data: {entityId: entityId, entityClass: entityClass},
                    success: function (commentsArray) {
                        success(commentsArray)
                    },
                    error: error
                });
            },

            /**
             * Get Users
             *
             * @param success
             * @param error
             */
            getUsers: function (success, error) {
                $.ajax({
                    type: 'get',
                    url: '/core/extensions/comments/get-users',
                    success: function (userArray) {
                        success(userArray)
                    },
                    error: error
                });
            },

            /**
             * Post Comment
             *
             * @param commentJSON
             * @param success
             * @param error
             */
            postComment: function (commentJSON, success, error) {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: '/core/extensions/comments/post-comment',
                    data: {comment: commentJSON, entityId: entityId, entityClass: entityClass, path: window.location.pathname},
                    success: function (comment) {
                        success(comment)
                    },
                    error: error
                });
            },

            /**
             * Put Comment
             *
             * @param commentJSON
             * @param success
             * @param error
             */
            putComment: function (commentJSON, success, error) {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'put',
                    url: '/core/extensions/comments/update-comment',
                    data: {comment: commentJSON, entityId: entityId, entityClass: entityClass},
                    success: function (comment) {
                        success(comment)
                    },
                    error: error
                });
            },

            /**
             * Delete Comment
             *
             * @param commentJSON
             * @param success
             * @param error
             */
            deleteComment: function (commentJSON, success, error) {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'delete',
                    url: '/core/extensions/comments/delete-comment',
                    data: {comment: commentJSON, entityId: entityId, entityClass: entityClass},
                    success: success,
                    error: error
                });
            },

            /**
             * Upvote Comment
             *
             * @param commentJSON
             * @param success
             * @param error
             */
            upvoteComment: function (commentJSON, success, error) {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: '/core/extensions/comments/upvote',
                    data: {commentId: commentJSON.id},
                    success: function () {
                        success(commentJSON)
                    },
                    error: error
                });


            },


        });

    }


}

BAP_Platform.init();
