<?php

return [
    'index' => ['Index#index', 'get'],
    'index/hello/{var}' => ['Index#hello', 'get'],
    'user/register' => ['Admin#registerUser', 'post'],
    'user/login' => ['Users#login', 'post'],
    'user/changepassword' => ['Users#changePassword', 'post'],
    'notes/add' => ['Notes#add', 'post'],
    'notes/{userid}/{noteid}' => ['Notes#get', 'get'],

];