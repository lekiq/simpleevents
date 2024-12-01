<?php

namespace SimpleEvents\Core;

class Utils
{
    /**
     * Redirect to a given URL
     * @param string $url
     * @return void
     */
    public static function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }

    /**
     * Send JSON response
     * @param mixed $data
     * @param int $statusCode
     * @return void
     */
    public static function jsonResponse(mixed $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
