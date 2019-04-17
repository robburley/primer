<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    public function updateAndReturn(array $attributes = [], array $options = [])
    {
        Parent::update($attributes, $options);

        return $this;
    }
}
