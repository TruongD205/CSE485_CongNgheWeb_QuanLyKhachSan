<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $primaryKey = 'AccountID';

    protected $fillable = ['AccountID', 'Username', 'Password', 'Gmail'];
}
