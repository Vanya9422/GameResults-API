<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $milliseconds
 * @property Member $member
 */
class Result extends Model {

    use HasFactory;

    protected $fillable = ['member_id', 'milliseconds'];

    /**
     * @return BelongsTo
     */
    public function member(): BelongsTo {
        return $this->belongsTo(Member::class);
    }
}
