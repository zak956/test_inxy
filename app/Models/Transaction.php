<?php

declare(strict_types=1);

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int|null $sender_id
 * @property int $recipient_id
 * @property numeric $amount
 * @property string $title
 * @property DateTime $created_at
 * @property DateTime $updated_at
 */
class Transaction extends Model
{
    /**
     * @var string
     */
    protected $table = 'transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sender_id',
        'recipient_id',
        'amount',
        'title'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
