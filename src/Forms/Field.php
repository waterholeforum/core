<?php

namespace Waterhole\Forms;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\View\Component;

abstract class Field extends Component
{
    public function validating(Validator $validator): void
    {
    }

    public function saving(FormRequest $request): void
    {
    }

    public function saved(FormRequest $request): void
    {
    }
}
