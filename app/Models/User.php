<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
    ];

    public function emails(): HasMany
    {
        return $this->hasMany(UserEmail::class);
    }
}
