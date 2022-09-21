define(['Jquery', 'uiComponent', 'ko'
], function($, Component, ko) {
    'use strict';
    return Component.extend({
        defaults: {
            template: 'Tha_Call/nan/demo1',
            tha_num: "123123123",
            pp: "tha tesst for default ui",
        },

        initialize: function (config) {
            this.config = config;
            this.a = config.a;
            this.b = config.b;
            this.show();
        },

        show: function () {
            console.log(this.config.a);
            console.log(this.config.b);
        }
    });
    
});