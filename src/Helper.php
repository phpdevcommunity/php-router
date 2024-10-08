<?php

namespace PhpDevCommunity;

final class Helper
{
    /**
     * Trim the given path by removing leading and trailing slashes.
     *
     * @param string $path The path to be trimmed
     * @return string The trimmed path
     */
    public static function trimPath(string $path): string
    {
        return '/' . rtrim(ltrim(trim($path), '/'), '/');
    }
}
