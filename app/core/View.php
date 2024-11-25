<?php

namespace SimpleEvents\Core;

class View
{
    /**
     * Render a view with optional data
     *
     * @param string $view Path to the view file relative to the views directory
     * @param array $data Associative array of data to pass to the view
     */
    public static function render(string $view, array $data = []): void
    {
        // Extract data into variables
        extract($data);

        // Build the paths to layout and view files
        $viewPath = "../app/views/$view";
        $headerPath = "../app/views/layouts/header.php";
        $footerPath = "../app/views/layouts/footer.php";

        // Ensure the view file exists
        if (!file_exists($viewPath)) {
            throw new \InvalidArgumentException("View file not found: $viewPath");
        }

        // Include layout and view files
        require_once $headerPath;
        require_once $viewPath;
        require_once $footerPath;
    }
}
