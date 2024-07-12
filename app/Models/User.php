<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Model implements Authenticatable
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $timestamps = true;
    public $incrementing = true;
    protected $fillable = [
        'full_name',
        'username',
        'email',
        'password'
    ];

    public function token(): HasOne
    {
        return $this->hasOne(Token::class);
    }

    public function getAuthIdentifierName(): string
    {
        return 'username';
    }

    public function getAuthIdentifier()
    {
        return $this->username;
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken(): string
    {
        return $this->token;
    }

    public function setRememberToken($value)
    {
        return $this->token = $value;
    }

    public function getRememberTokenName(): string
    {
        return 'token';
    }
}
