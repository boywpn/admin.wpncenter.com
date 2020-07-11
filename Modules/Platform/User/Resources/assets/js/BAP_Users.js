var BAP_Users = {

    init: function () {

        this.uploadProfilePicture();


    },

    uploadProfilePicture: function(){

        $('#profile_picture').fileinput({
            dropZoneEnabled: false,
            uploadAsync: false,
            showUpload: false,
            showRemove: false,
            showCaption: true,
            maxFileCount: 1,
            showBrowse: true,
            browseOnZoneClick: true,
        })

    },

    /**
     * Login as user with identifier
     * @param identifier
     */
    loginAsUser: function (identifier) {
        $(location).attr('href', '/settings/users/ghost-login/'+identifier);
    }

}

BAP_Users.init();
