<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    //ここに入力しておかないとデータベースに登録できない
    protected $fillable = [
        'title',
        'email',
        'body',
    ];
}
