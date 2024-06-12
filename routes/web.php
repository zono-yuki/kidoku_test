<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;//追加
use App\Http\Controllers\CommentController;//追加
use App\Http\Controllers\ContactController;//追加
use App\Http\Controllers\RoleController;//追加


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('top');

Route::controller(ContactController::class)->group(function(){
    Route::get('contact/create', [ContactController::class, 'create'])->name('contact.create')->middleware('guest');
    //逆に、ログイン後のユーザーがお問い合わせ作成画面を表示できないようにする

    Route::post('contact/store', 'store')->name('contact.store');
});

// Route::get('/dashboard',function(){
//     return view('dashboard');
// })->middleware(['auth','verified'])->name('dashboard');

Route::middleware('verified')->group(function () {//authをverifiedにしたらメール認証後ログインできるようになる。本番環境にしていないので、確認メールは飛んでくるが、ローカルで認証できないのでphpmyadminでemail_verified_atは手打ちするとログインできる。
    //自分の投稿を表示
    Route::get('post/mypost', [PostController::class, 'mypost'])->name('post.mypost');
    //自分のコメントを表示
    Route::get('post/mycomment', [PostController::class, 'mycomment'])->name('post.mycomment');
    //基本的な投稿に関するroute
    Route::resource('post', PostController::class);
    //コメントを保存するためのroute
    Route::post('post/comment/store',[CommentController::class,'store'])->name('comment.store');
    //プロフィール編集
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //管理者用画面
    Route::middleware('can:admin')->group(function () {
       Route::get('profile/index', [ProfileController::class, 'index'])->name('profile.index');
       Route::get('/profile/adedit/{user}', [ProfileController::class, 'adedit'])->name('profile.adedit');
       Route::patch('/profile/adupdate/{user}', [ProfileController::class, 'adupdate'])->name('profile.adupdate');

       //中間テーブルの権限のつけ外し　attach detach
       Route::patch('roles/{user}/attach', [RoleController::class, 'attach'])->name('role.attach');
       Route::patch('roles/{user}/detach', [RoleController::class, 'detach'])->name('role.detach');
    });
});

Route::get('/dashboard', [PostController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');



require __DIR__.'/auth.php';
