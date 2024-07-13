<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExpenseType extends Model
{
    protected $table = 'expense_types';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $timestamps = true;
    public $incrementing = true;
    protected $fillable = [
        'name',
        'is_active'
    ];

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}
