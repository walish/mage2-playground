var config = {
    paths: {
        fullcalendar: 'SmartOSC_Blog/js/fullcalendar',
        moment: 'SmartOSC_Blog/js/moment.min',
        fancybox: 'SmartOSC_Blog/js/jquery.fancybox',
        vpplayer: 'SmartOSC_Blog/js/vpplayer',
        locale_all: 'SmartOSC_Blog/js/locale-all'
    },
    shim: {
        'fancybox': {
            deps: ['jquery']
        },
        'vpplayer': {
            deps: ['jquery']
        }
    },
    'map': {
        '*': {
            'Magento_Checkout/js/model/quote': 'SmartOSC_Blog/js/model/quote'
        }
    }
};