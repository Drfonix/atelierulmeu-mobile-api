<?php

if (!function_exists('path_fixer')) {
    /**
     * @param string $path
     * @return bool
     */
    function path_fixer(string $path)
    {
        // Laravel uses / separator by default.

        if (DIRECTORY_SEPARATOR !== '/') {
            // Let's check the current system default is this.
            return str_replace('/', DIRECTORY_SEPARATOR, $path); // Change the separator for current system.
        }

        return $path; // Use coming path.
    }
}
