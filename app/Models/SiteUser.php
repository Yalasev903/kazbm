<?php

namespace App\Models;

use App\Enums\UserStatusEnum;
use App\Traits\JsonAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SiteUser extends Authenticatable
{
    use HasFactory, JsonAttribute;

    protected $hidden = ['password'];

    protected $casts = [
        'data' => 'array'
    ];

    protected $fillable = [
        'name',
        'surname',
        'patronymic',
        'email',
        'phone',
        'status',
        'password',
        'data'
    ];

    public function getStatus(): string
    {
        return UserStatusEnum::labels()[$this->status] ?? '';
    }
}
