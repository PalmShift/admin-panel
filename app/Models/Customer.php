<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // Add 'name', 'email', and other fields you want to allow mass assignment
    protected $fillable = [
        'name',
        'email',
        'contact',
        'date',
        'time',
        'npeople',
        'message',
        'status'
    ];

    // If you want to allow timestamps (created_at and updated_at) to be automatically managed, ensure they're enabled
    public $timestamps = true;
}
?>
