<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $email
 */
class Member extends Model {
    use HasFactory;

    protected $fillable = ['email'];
}
