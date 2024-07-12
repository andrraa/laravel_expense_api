<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Token extends Model
{
    protected $table = 'tokens';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $timestamps = true;
    public $incrementing = true;
    public $fillable = [
        'username',
        'password'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
