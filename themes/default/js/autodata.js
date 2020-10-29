function autoDataAPI(customfields, brand_selector, model_selector, generation_selector, search_form = false) {
        var autodata_xhr;
        var select_autodata_brand, $select_autodata_brand;
        var select_autodata_model, $select_autodata_model;
        var select_autodata_generation, $select_autodata_generation;

        if (search_form) {
            $(brand_selector).select2('destroy').attr('multiple', false).attr('style', 'width: 155px;');
            $(model_selector).select2('destroy').attr('multiple', false).attr('style', 'width: 155px;');
            $(generation_selector).select2('destroy').attr('multiple', false).attr('style', 'width: 155px;');
        } else {
            $(brand_selector)[0].selectize.destroy();
            $(model_selector)[0].selectize.destroy();
            $(generation_selector)[0].selectize.destroy();
        }

        $select_autodata_brand = $(brand_selector).selectize({
            valueField: 'name',
            labelField: 'name',
            searchField: ['name'],
            onChange: function(value) {
                if (!value.length) return;

                autodata_xhr && autodata_xhr.abort();
                autodata_xhr = $.ajax({
                    url: $('meta[name="application-name"]').data('baseurl') + 'api/v1/autodatabrands',
                    data: {
                        'name': value
                    },
                    success: function(results) {
                        value = results.brands[0].id_brand;

                        select_autodata_model.disable();
                        select_autodata_model.clearOptions();
                        select_autodata_generation.disable();
                        select_autodata_generation.clearOptions();
                        select_autodata_model.load(function(callback) {
                            autodata_xhr && autodata_xhr.abort();
                            autodata_xhr = $.ajax({
                                url: $('meta[name="application-name"]').data('baseurl') + 'api/v1/autodatabrands/' + value,
                                success: function(results) {
                                    select_autodata_model.enable();
                                    callback(results.brand.models);
                                    if (search_form) {
                                        select_autodata_model.setValue($('#search-custom-fields').data('customfield-values')['cf_model'][0], false);
                                    } else {
                                        select_autodata_model.setValue($('#custom-fields').data('customfield-values')[customfields['cf_model'].label], false);
                                    }
                                },
                                error: function() {
                                    callback();
                                }
                            })
                        });
                    }
                })
            }
        });

        $select_autodata_model = $(model_selector).selectize({
            valueField: 'name',
            labelField: 'name',
            searchField: ['name'],
            onChange: function(value) {
                if (!value.length) return;

                autodata_xhr && autodata_xhr.abort();
                autodata_xhr = $.ajax({
                    url: $('meta[name="application-name"]').data('baseurl') + 'api/v1/autodatamodels',
                    data: {
                        'name': value
                    },
                    success: function(results) {
                        value = results.models[0].id_model;

                        select_autodata_generation.disable();
                        select_autodata_generation.clearOptions();
                        select_autodata_generation.load(function(callback) {
                            autodata_xhr && autodata_xhr.abort();
                            autodata_xhr = $.ajax({
                                url: $('meta[name="application-name"]').data('baseurl') + 'api/v1/autodatamodels/' + value,
                                success: function(results) {
                                    select_autodata_generation.enable();
                                    callback(results.model.generations);
                                    if (search_form) {
                                        select_autodata_generation.setValue($('#search-custom-fields').data('customfield-values')['cf_generation'][0], false);
                                    } else {
                                        select_autodata_generation.setValue($('#custom-fields').data('customfield-values')[customfields['cf_generation'].label], false);
                                    }
                                },
                                error: function() {
                                    callback();
                                }
                            })
                        });
                    }
                })
            }
        });

        $select_autodata_generation = $(generation_selector).selectize({
            valueField: 'name',
            labelField: 'name',
            searchField: ['name']
        });

        select_autodata_brand  = $select_autodata_brand[0].selectize;
        select_autodata_model = $select_autodata_model[0].selectize;
        select_autodata_generation = $select_autodata_generation[0].selectize;

        select_autodata_model.disable();
        select_autodata_generation.disable();

        select_autodata_brand.load(function(callback) {
            autodata_xhr && autodata_xhr.abort();
            autodata_xhr = $.ajax({
                url: $('meta[name="application-name"]').data('baseurl') + 'api/v1/autodatabrands/',
                success: function(results) {
                    callback(results.brands);
                    if (search_form) {
                        select_autodata_brand.setValue($('#search-custom-fields').data('customfield-values')['cf_brand'][0], false);
                    } else {
                        select_autodata_brand.setValue($('#custom-fields').data('customfield-values')[customfields['cf_brand'].label], false);
                    }
                },
                error: function() {
                    callback();
                }
            })
        });
};
