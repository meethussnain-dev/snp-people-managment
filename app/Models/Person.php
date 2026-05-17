<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Person extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'created_by',
        'language_id',
        'name',
        'surname',
        'sa_id_number',
        'mobile_number',
        'email',
        'birth_date',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'date',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function interests(): BelongsToMany
    {
        return $this->belongsToMany(Interest::class);
    }
}
