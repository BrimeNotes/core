<?php

return [
    'index' => ['Index#index', 'get'],
    'index/hello/{var}' => ['Index#hello', 'get'],
    'user/register' => ['Admin#registerUser', 'post'],
    'user/login' => ['Users#login', 'post'],
];