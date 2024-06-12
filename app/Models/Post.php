<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    //投稿して保存される項目を設定　ホワイトリスト
    protected $fillable=[
        'title',
        'body',
        'user_id',
    ];

    //投稿して保存されない項目を設定　ブラックリスト　
    // protected $guarded = [
    //     'id'
    // ];



    public function user(){
        return $this->belongsTo(User::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }
}
