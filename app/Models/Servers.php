<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servers extends Model
{
    protected $table = 'servers';
    protected $fillable = [
        'provider', 
        'location', 
        'cpanelUrl', 
        'whmUrl', 
        'apiKey', 
        'username',
        'package',
        'ns1', 
        'ns2', 
        'ip'
    ];

    public function Hosting() {
        
        return $this->hasMany(Hosting::class, 'server_id', 'id');
    }


}
