<?php

namespace Agroprodutor\Helpers;

class RequestHelper
{
    public static function getJsonInput(): array
    {
        $jsonInput = file_get_contents('php://input');
        $decoded = json_decode($jsonInput, true);

        return is_array($decoded) ? $decoded : [];
    }
}
