<?php

namespace App\Support;

use RuntimeException;

class Site
{
    public static function data(): array
    {
        $path = resource_path('js/config/site.js');
        $js = file_get_contents($path);

        if ($js === false || ! preg_match('/export\s+default\s*(\{.*\})/s', $js, $match)) {
            throw new RuntimeException('Could not parse '.$path);
        }

        return json_decode($match[1], true, 512, JSON_THROW_ON_ERROR);
    }
}
