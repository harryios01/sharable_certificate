<?php

namespace App\Utils;

use Ramsey\Uuid\Uuid;

class UUIDGenerator
{
    public static function generate()
    {
        return Uuid::uuid4()->toString();
    }

    public static function isValid($uuid)
    {
        return Uuid::isValid($uuid);
    }
}
