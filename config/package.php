<?php

return [
    'free' => [
        'max_organizers' => 1,
        'max_same_time_event' => 1,
        'max_sponsors' => 5,
        'max_exhibitors' => 10,
        'max_media_partner' => 10,
        'max_file_size' => 2048, // byte
        'ticket_commission' => 5,
        'custom_event_link' => false,
        'download_report' => false,
    ],
    'starter' => [
        'max_organizers' => 3,
        'max_same_time_event' => 2,
        'max_sponsors' => -1,
        'max_exhibitors' => -1,
        'max_media_partner' => -1,
        'max_file_size' => 5120, // byte
        'ticket_commission' => 3,
        'custom_event_link' => true,
        'download_report' => true,
    ],
    'pro' => [
        'max_organizers' => 5,
        'max_same_time_event' => 5,
        'max_sponsors' => -1,
        'max_exhibitors' => -1,
        'max_media_partner' => -1,
        'max_file_size' => 10240, // byte
        'ticket_commission' => 2,
        'custom_event_link' => true,
        'download_report' => true,
    ]
];