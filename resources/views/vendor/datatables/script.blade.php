
    (function (window, $) {

        var dataTableParams = %2$s;

         dataTableParams['language'] = {
            url : '/bap/js/trans/datatable/{{ app()->getLocale() }}.json'
        };

        window.LaravelDataTables = window.LaravelDataTables || {};
        window.LaravelDataTablesFilters = window.LaravelDataTablesFilters || {};
        window.LaravelDataTables["%1$s"] = $("#%1$s").DataTable(dataTableParams);

        // Filters in columns
        if(typeof(dataTableParams.headerFilters) !== 'undefined' && dataTableParams.headerFilters){
            yadcf.init(window.LaravelDataTables["%1$s"] ,dataTableParams.columns);
        }

        // Query Filter builder
        if(typeof(dataTableParams.filterDefinitions) !== 'undefined'){
            window.LaravelDataTablesFilters["%1$s"] = dataTableParams.filterDefinitions;

            if($("#queryFilter_%1$s").length){

                $("#queryFilter_%1$s").queryBuilder({
                plugins: ['bt-tooltip-errors','filter-description'],
                filters: window.LaravelDataTablesFilters["%1$s"],
                sort_filters : true,
                lang_code : window.APPLICATION_USER_LANGUAGE,

                });

                if(typeof(dataTableParams.filterRules) !== 'undefined' && dataTableParams.filterRules != null){
                    $("#queryFilter_%1$s").queryBuilder('setRules', JSON.parse(dataTableParams.filterRules));
                    $("#queryFilter_%1$s").show();
                }

            }
            if($("#advanced_settings_filters_creator").length){
                $("#advanced_settings_filters_creator").queryBuilder({
                plugins: ['bt-tooltip-errors','filter-description'],
                filters: window.LaravelDataTablesFilters["%1$s"],
                sort_filters : true,
                lang_code : window.APPLICATION_USER_LANGUAGE,

                });
            }


        }

    })(window, jQuery);
