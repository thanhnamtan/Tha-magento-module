define([
    'ko',
    'uiComponent'
],
function(ko, Component) {
    'use strict';
    return Component.extend({
        firstName: ko.observable(''),
        initialize: function() {
            this._super();
        }
    });
});