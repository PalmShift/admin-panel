<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;


    protected $fillable = [
        'id',
        'created_at',
        'updated_at',
        'name',
        'email',
        'contact',
        'date',
        'time',
        'npeople',
        'message',
        'status'
    ];


    public $timestamps = true;
}
?>
