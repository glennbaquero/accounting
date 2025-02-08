<?php

namespace App\Helpers;

class CodeHelper {
    /**
     * ########-##########-####
     * @return string
     */
    static function generateNumberCode() {
        return date('Ymd') . '-' . round(microtime(true));
    }
}