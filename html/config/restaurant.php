<?php

return [
    'pickup_time_start' => env('RESTAURANT_PICKUP_TIME_START', '12:00'),
    'pickup_time_end' => env('RESTAURANT_PICKUP_TIME_END', '16:00'),
    'reservation_mail_delivery' => env('RESTAURANT_RESERVATION_MAIL_DELIVERY', 'sync'),
    'custom_order_url' => env(
        'RESTAURANT_CUSTOM_ORDER_URL',
        'https://wa.me/34652700611?text=Hola,%20quiero%20hacer%20un%20pedido%20personalizado'
    ),
    'home_hero_image' => env('RESTAURANT_HOME_HERO_IMAGE', 'images/Gemini_Generated_Image_eklv9oeklv9oeklv.png'),
    'home_hero_focus' => env('RESTAURANT_HOME_HERO_FOCUS', 'center center'),
    'home_chef_image' => env('RESTAURANT_HOME_CHEF_IMAGE', 'images/veronica.jpeg'),
    'home_chef_focus' => env('RESTAURANT_HOME_CHEF_FOCUS', 'center top'),
    'home_chef_name' => env('RESTAURANT_HOME_CHEF_NAME', 'Chef principal'),
    'home_chef_role' => env('RESTAURANT_HOME_CHEF_ROLE', 'Encargos a medida y cocina criolla por encargo'),
];
