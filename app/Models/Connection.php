<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    use HasFactory;
    protected $guarded = [];

    //--------------- Get the user that  have connection ---------------
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
