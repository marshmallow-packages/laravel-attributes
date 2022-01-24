<?php

namespace Marshmallow\Attributes\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use Marshmallow\Attributes\Traits\Attributable;

class User extends Model
{
    use Attributable;
}
