define([
    'ko',
    'uiComponent',
    'mage/url',
    'mage/storage',
],
function (ko, Component, urlBuilder, storage) {
    'use strict';
    return Component.extend({
        defaults: {template: 'Tha_Call/nan/ajax-example'},
        /** @inheritdoc */
        initialize: function () {
            this.searchdata = ko.observableArray([]);
            this._super();
            return this;
        },
        getKeyword: function () {
            var self = this;
            var serviceUrl = urlBuilder.build('more/ajax/index');
            var data = document.getElementById('search-example').value;
            storage.post(
                serviceUrl,
                JSON.stringify({'keyword': data}),
                false
            ).done(function (response) {
                    // alert(response.keyword);
                    this.searchdata.push({value: response.keyword});
                }.bind(self) // pass function context to done() function.
            ).fail(function (response) {
                // code khi fail
            });
        }
    });
}
);