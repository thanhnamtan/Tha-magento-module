//Cách đơn giản, không cần khai báo componentName
define(
    ['jquery', 'vendorOwl'],
    function ($) {
        return function (config) {
            var element = config.elementClass;
            $(element).owlCarousel(config.options);
        }
    }
)