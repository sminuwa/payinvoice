<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Card extends Model
{
    use HasFactory;

    protected $table = 'cards';

    protected $hidden = [
        'pin'
    ];

    public function getCreatedAtAttribute($date)
    {
        return date('Y-m-d h:i', strtotime($date));
    }

    public function getUpdatedAtAttribute($date)
    {
        return date('Y-m-d h:i', strtotime($date));
    }

    public function transactions(){
        return $this->hasMany(CardTransaction::class, 'card_id');
    }
}
