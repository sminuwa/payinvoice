<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardTransaction extends Model
{
    use HasFactory;

    protected $table = 'card_transactions';

    public function getCreatedAtAttribute($date)
    {
        return date('Y-m-d h:i', strtotime($date));
    }

    public function getUpdatedAtAttribute($date)
    {
        return date('Y-m-d h:i', strtotime($date));
    }
}
