<?php

namespace App\Utils;

class StringUtils
{
    /**
     * Convert a string to "Title Case" format.
     *
     * @param  string  $value
     * @return string
     */
    public static function titleCase(string $value): string
    {
        // Replace hyphens with spaces, then apply "ucwords" for Title Case
        return ucwords(str_replace('-', ' ', $value));
    }
}
