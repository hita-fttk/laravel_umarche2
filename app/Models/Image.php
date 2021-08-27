<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Owener;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'owener_id',
        'filename',
    ];

    public function owener()
    {
        return $this->belongsTo(Owener::class);
    }
}
