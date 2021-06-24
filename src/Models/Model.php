<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

abstract class Model extends Eloquent
{
    protected static $unguarded = true;
}
