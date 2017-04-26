<?php

namespace Brime\Core\Helpers;

class JSONResponse
{
    public function __construct($data= [], $statusCode=Http::STATUS_OK) {
        http_response_code($statusCode);
        @header('Content-Type:application/json; charset=utf-8');
        echo json_encode($data);
    }
}