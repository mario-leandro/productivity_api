<?php

namespace Src\Core;

class Request
{
    public static function json(): array
    {
        return json_decode(
            file_get_contents('php://input'),
            true
        ) ?? [];
    }
}
