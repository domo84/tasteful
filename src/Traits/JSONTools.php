<?php

namespace Sunnyvale\REST\Traits;

trait JSONTools
{
    private function isJSON($content)
    {
        if (is_string($content)) {
            $pos = strpos($content, "{");
            if ($pos === 0 || $pos === 1) {
                return true;
            }
        }

        return false;
    }

    private function encode($content)
    {
        return json_encode($content, JSON_UNESCAPED_UNICODE);
    }
}
