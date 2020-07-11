var BAP_Products = {

    init: function () {

        if(!window.bapOrderModuleLoaded) {

            this.uploadImage();

            window.bapOrderModuleLoaded = true;

        }

    },

    uploadImage: function(){

        $('#image_path').fileinput({
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

BAP_Products.init();
