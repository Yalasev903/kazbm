<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

abstract class Application extends Model
{
    use HasFactory;

    protected $casts = [
        'data' => 'array'
    ];

    protected $table = 'applications';

    protected $fillable = [
        'type',
        'name',
        'phone',
        'email',
        'message',
        'data'
    ];

    abstract public function getType(): string;

    public function setDataAttributes(array $data): void
    {
        $this->setRawAttributes($data);
        $this->type = $this->getType();
    }

}
