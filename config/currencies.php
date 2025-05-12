<?php

return [
    "currencies" => [
        "usd" => [
            'stripe_key' => "sk_test_51RMSHhCTRbbPNe2xjQPea83UDIKnByOpqYyOFZOf7G98Nd82czR8OCIZdhE6QWYobDzF4JXVlu1WEKxL4FpwFCuT009LyuggG5",//env('STRIPE_SECRET_USD'),
            'symbol' => '$',
        ],
        "eur" => [
            'stripe_key' => "sk_test_51JVGnKHoopo31wN6bHEOzkgDph1slH1EBzcurH4XBnXvQISz1xiWFZ5qvoyNGsPJTei52CYBAa6roAIAONjma9WD00P5yhGWa4",//env('STRIPE_SECRET_EUR'),
            'symbol' => '€',
        ],
        "gbp" => [
            'stripe_key' => "sk_test_51HFSGmDGVZS7xdeiEoxDU3lmzBII48LDiFL96Mv9nWFiYJYBWOPKbquHnUV47OLQesKpihXoBRhB2iwQxJz9uchz00ntbrOg0X",///env('STRIPE_SECRET_GBP'),
            'symbol' => '£',
        ],
        "aed" => [
            'stripe_key' => "sk_test_51Iw2TmGcHcZIsrlm2pJ40wtSAO1uFU4ZPwBNGZbUfEujIEIn62pr8jlTHolA7VPA1ZyzHsNHtoBLxtir77JT06pg00H0N31N0w",///env('STRIPE_SECRET_GBP'),
            'symbol' => '£',
        ],

    ],
];
