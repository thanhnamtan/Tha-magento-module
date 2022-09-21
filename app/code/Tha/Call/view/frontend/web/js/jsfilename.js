define(['uiComponent', 'jquery'],
function (Component, $) {
   'use strict';
   return Component.extend({
       initialize: function (config, node) {
            // some code
            this.config = config;
            this.node = node;
            var self = this;

            $(node).click($.proxy( // proxy: để đưa 1 ngữ cảnh khác vào trong 1 function. vd đưa ngữ cảnh this chung vào trong function trên.
                function (event) {
                    var pp = this; // this = uiComponent sau khi dùng proxy
                    alert(this.config.parameter);
                }, self
            ));
        }
    });
});