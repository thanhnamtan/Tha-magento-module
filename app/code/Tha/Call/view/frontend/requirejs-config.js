var config = {
    map: {
        '*': {
                demojsdp: 'Tha_Call/js/demojs',
                demojs2dp: 'Tha_Call/js/demojs2',
                jsfilenamedp: 'Tha_Call/js/jsfilename',
                jslocate: 'Tha_Call/js/jslocate', // khai bao js source
                demo1: 'Tha_Call/js/nan/demo1',
                colorpicker: 'Tha_Call/js/demo/colorpicker',
                'Magento_Checkout/template/summary.html': 'Tha_Call/template/summary.html' // override file html template
            }
        },

        paths: {
            'highcharts': 'Tha_Call/js/highcharts'
        },

        shim: {
            "jslocate": ["jquery", "highcharts"],
            "colorpicker": ["jquery"],
            "Tha_Call/js/custom-slider": ['jquery']
        }
};