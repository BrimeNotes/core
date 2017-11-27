<?php

namespace Brime\Core\Helpers;

class Random
{
    public function __construct() { }

    public function generateString($length, $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/')
    {
        $maxCharIndex = strlen($characters) - 1;
        $randomString = '';

        while ($length > 0) {
            $randomNumber = \random_int(0, $maxCharIndex);
            $randomString .= $characters[$randomNumber];
            $length--;
        }

        return $randomString;
    }

}
