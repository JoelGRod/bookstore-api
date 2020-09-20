<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowedBook extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['book_id', 'customer_id', 'start'];

    public function book() {
        //Un alquiler tiene un libro
        return $this->hasOne('App\Models\Book');
    }

    public function customer() {
        //Un alquiler pertenece a un user
        return $this->belongsTo('App\Models\User');
    }
}
