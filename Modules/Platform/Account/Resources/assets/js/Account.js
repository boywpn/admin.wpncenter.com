var BAP_Account = {

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
        });

    },


}

BAP_Account.init();
