<?php

namespace App\Helpers\VerificationGenerators;

class SixDigitGenerator implements VerificationGenerator
{
    public static function generate()
    {
        return random_int(100000, 999999);
    }
}
