<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Kirschbaum\PowerJoins\PowerJoins;

abstract class Model extends Eloquent
{
    use PowerJoins;

    protected static $unguarded = true;
}
