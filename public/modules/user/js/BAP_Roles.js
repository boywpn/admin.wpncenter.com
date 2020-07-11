var BAP_Roles = {

    init: function () {

    },

    /**
     * Redirect to permissions
     * @param id
     */
    setupPermissions: function (id) {
        $(location).attr('href', '/settings/roles/permissions/'+id);
    }

}

BAP_Roles.init();
