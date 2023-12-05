<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $name
 * @property int $age
 * @property string $email
 * @property DateTime $created_at
 * @property DateTime $updated_at
 */
class User extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'age',
    ];

    public function account(): HasOne
    {
        return $this->hasOne(Account::class);
    }
}
