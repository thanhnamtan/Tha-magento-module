define([
    'jquery', 'ko', 'uiComponent'
], function($, ko, uiComponent) {
    'use strict';
    return uiComponent.extend(
        {
            defaults: {
                template: 'Tha_Call/knockout-test',
                tha_num: "123123123",
                pp: "tha tesst for default ui",
            },

            initialize: function(config, node) {
                this.config = config;
                this.node = node;
                var self = this;

                $(node).click($.proxy(
                    function(event) {
                        var pp = this; // this = uiComponent sau khi dùng proxy
                        alert(this.config.parameter);
                        this.color(0);
                    }, self
                ));
            },

            color: function (params) {
                alert(123123);
            }
    });
});