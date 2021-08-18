<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Owener;

class Shop extends Model
{
    use HasFactory;

    public function owener()
    {
        return $this->belongsTo(Owener::class);
    }
}
