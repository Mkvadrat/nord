(function(w){
    var q=[
        ['setContext', 'TL-INT-hotelnord', 'ru']
    ];
    var t=w.travelline=(w.travelline||{}),ti=t.integration=(t.integration||{});ti.__cq=ti.__cq?ti.__cq.concat(q):q;
    if (!ti.__loader){ti.__loader=true;var d=w.document,p=d.location.protocol,s=d.createElement('script');s.type='text/javascript';s.async=true;s.src=(p=='https:'?p:'http:')+'//ibe.tlintegration.com/integration/loader.js';(d.getElementsByTagName('head')[0]||d.getElementsByTagName('body')[0]).appendChild(s);}
})(window);

    function bookingStepChanged(data) {
        switch (data.step) {
            case 'search':
            case 'preview':
            case 'payment':
            case 'complete':
                break;
            default:
                return;
        }
        window.yaCounter45482307.hit('/' + data.step);
    }

    function bookingSuccess(data) {
        window.yaCounter45482307.reachGoal('Bron_Travelline', {
            'order_id': data.bookingNumber,
            'order_price': data.price,
            'currency': data.currency
        });
    }

    function noAvailableRooms(data) {
        window.yaCounter45482307.reachGoal('Booked_Up', {
            'now': data.now,
            'arriaval': data.arrivalDate,
            'departure': data.departureDate,
            'guests': data.guests
        });
    }
    (function(w){
        var q=[
            ['setContext', 'TL-INT-hotelnord', 'ru'],
            ['embed', 'search-form', {container: 'tl-search-form'}]
        ];
        var t=w.travelline=(w.travelline||{}),ti=t.integration=(t.integration||{});ti.__cq=ti.__cq?ti.__cq.concat(q):q;
        if (!ti.__loader){ti.__loader=true;var d=w.document,p=d.location.protocol,s=d.createElement('script');s.type='text/javascript';s.async=true;s.src=(p=='https:'?p:'http:')+'//ibe.tlintegration.com/integration/loader.js';(d.getElementsByTagName('head')[0]||d.getElementsByTagName('body')[0]).appendChild(s);}
    })(window);
    (function(w){
        if (document.getElementById('tl-booking-form') != null) {
            var searchForm = document.getElementById('tl-search-form');
            if (searchForm)
                searchForm.hidden = true;
        }
        var q=[
            ['setContext', 'TL-INT-hotelnord', 'ru'],
            ['embed', 'booking-form', {container: 'tl-booking-form',
                onBookingStepChanged: bookingStepChanged,
                onBookingSuccess: bookingSuccess,
                onNoAvailableRooms: noAvailableRooms}]
        ];
        var t=w.travelline=(w.travelline||{}),ti=t.integration=(t.integration||{});ti.__cq=ti.__cq?ti.__cq.concat(q):q;
        if (!ti.__loader){ti.__loader=true;var d=w.document,p=d.location.protocol,s=d.createElement('script');s.type='text/javascript';s.async=true;s.src=(p=='https:'?p:'http:')+'//ibe.tlintegration.com/integration/loader.js';(d.getElementsByTagName('head')[0]||d.getElementsByTagName('body')[0]).appendChild(s);}
    })(window);
