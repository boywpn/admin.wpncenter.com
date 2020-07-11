var BAP_DisplaySettings = {

    init: function () {

        this.uploadLogo();

    },

    uploadLogo: function () {

        $('#s_display_logo_upload').fileinput({
            dropZoneEnabled: false,
            uploadAsync: false,
            showUpload: false,
            showRemove: false,
            showCaption: true,
            maxFileCount: 1,
            showBrowse: true,
            browseOnZoneClick: true,
        });
        $('#s_display_pdf_logo_upload').fileinput({
            dropZoneEnabled: false,
            uploadAsync: false,
            showUpload: false,
            showRemove: false,
            showCaption: true,
            maxFileCount: 1,
            showBrowse: true,
            browseOnZoneClick: true,
        });

    }


}

BAP_DisplaySettings.init();
