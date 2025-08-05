<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hosting extends Model
{
    protected $table = 'hosting';
    protected $fillable = [
        'user_id',
        'server_id',
        'plan',
        'username',
        'password',
        'domain',
        'country',
        'reason',
        'approved',
    ];

    public function user() {
        
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function servers() {
        
        return $this->hasOne(Servers::class, 'id', 'server_id');
    }

}
