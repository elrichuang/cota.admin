<?php

return [
    'context_path'=>env('ADMIN_CONTEXT_PATH','admin'),
    'page_limit' => env('ADMIN_PAGE_LIMIT',15),
    'api_cookie_name' => env('ADMIN_API_COOKIE_NAME','my_cota_admin_token')
];
