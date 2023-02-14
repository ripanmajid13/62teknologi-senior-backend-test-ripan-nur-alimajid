<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Business extends Model
{
    use HasFactory;

    protected function id(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Crypt::encryptString($value),
        );
    }
}
