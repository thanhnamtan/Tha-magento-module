define([
    'Jquery',
    'uiComponent',
    'ko'
], function($, Component, ko) {
    'use strict';

    var isLoggedIn = ko.observable(window.isCustomerLoggedIn);

    return {
        address: function (params) {
            var items = [],
                customerData = window.customerData;

            if (isLoggedIn()) {
                if (Object.keys(customerData).length) {
                    $.each(customerData.addresses, function (key, item) {
                        items.push(new Address(item));
                    });
                }
            }else{
                items.push('not login now');
            }
    
            return items;
        },

        show: function (param) {
            console.log(param);
        }

    }
});