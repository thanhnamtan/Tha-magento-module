define(['jquery', 'uiComponent', 'ko'], function ($, Component, ko) {
    'use strict';
    return Component.extend({
        defaults: {
            template: 'Tha_Call/knockout-test',
            tha_num: "123123123",
            pp: "tha tesst for default ui",
        },
        initialize: function () {
            this.customerName = ko.observableArray([]);
            this.customerData = ko.observable('');
            this.shoow = ko.observable(false);
            this._super();
        },

        addNewCustomer: function () {
            if (this.customerData._latestValue) {
                this.customerName.push({name:this.customerData()});
                this.customerData('');
            }else{
                this.canhbao();
            }
        },

        xoa: function() {
            this.customerName([]);
        },

        canhbao: function () {
            this.shoow(true);
            setTimeout(function(){ this.shoow(false); }.bind(this), 3000);
        }
    });
}
);