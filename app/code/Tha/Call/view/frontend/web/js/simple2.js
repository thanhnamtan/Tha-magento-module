define([
    'jquery',
    'jquery/ui'
], function($) {
    'use strict';

    $.widget('mage.simple2', { // component name = simple2
        options: {
            text: null,
            displayEle: null
        },
        /**
         * Bind handlers to events
         */
        _create: function() {
            console.info(this.options); // print the options in console
            // Save options to the element for later
            this.element.data('options', this.options);
            // Add event and method to the element
            this._on({
                'keyup': $.proxy(this._method, this.element)
            });
        },
        _method: function() {
            // fetch the element options
            var options = this.data('options');
            $(options.displayEle).text(this.val());
        }
    });

    return $.mage.simple2; // return simple2 component
});