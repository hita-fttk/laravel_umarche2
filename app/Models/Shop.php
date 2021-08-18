<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Owener;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'owener_id',
        'name',
        'information',
        'filename',
        'is_selling'
    ];

    public function owener()
    {
        return $this->belongsTo(Owener::class);
    }
}
