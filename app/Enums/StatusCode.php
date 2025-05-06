<?php

namespace App\Enums;
use BenSampo\Enum\Enum;
final class StatusCode extends Enum
{
    const UNPROCESSABLE_ENTITY = 422;
    const OK = 200;
    const CREATED = 201;
    const UNAUTHORIZED = 403;
    const NOT_FOUND = 404;
    const BAD_REQUEST = 400;
}

