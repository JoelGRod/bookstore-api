<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesBook extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['sale_id', 'book_id', 'amount'];

    public function sale() {
        return $this->belongsTo('App\Models\Sale');
    }

    public function book() {
        return $this->hasOne('App\Models\Book');
    }
}
