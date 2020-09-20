<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $fillable = ['customer_id'];

    public function customer() {
        return $this->belongsTo('App\Models\User');
    }

    public function salesBooks() {
        return $this->hasMany('App\Models\SalesBook');
    }
}
