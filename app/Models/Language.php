<?php

namespace App\Models;

use Database\Factories\People\LanguageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Language extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return LanguageFactory::new();
    }

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    public function people(): HasMany
    {
        return $this->hasMany(Person::class);
    }
}
