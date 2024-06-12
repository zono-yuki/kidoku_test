<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;//古いアイコンの削除用で使用する。


class PostController extends Controller
{

    //HOME画面（投稿一覧画面）
    public function index()// /post
    {
        $posts =Post::orderBy('created_at','desc')->get();

        $user = auth()->user();
        return view('post.index',compact('posts','user'));
    }


    //新規投稿画面
    public function create()
    {
        return view('post.create');// post/create
    }
    //  public function create()
    // {
    //     Gate::authorize('admin');
    //     return view('post.create');
    // }


    //投稿処理
    public function store(Request $request)
    {
        // dd($request);
        $inputs = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required|max:255',
            // 'image' => 'image|max:3024',
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->body = $request->body;
        $post->user_id = auth()->user()->id;


        if(request('image')){//もしrequestの中にimageがあったら
        $original = request()->file('image')->getClientOriginalName();//元の写真の名前を$nameに入れる。これしないと元の名前が消える。
        $name = date('Ymd_His').'_'.$original;//今の時間＋ファイル名　同じファイルだと上書きされてしまうから別にするため。
        request()->file('image')->move('storage/images',$name);//$nameという名前で、storage/imagesに入れる。
        $post->image = $name; //レコードのimageに$nameを入れる。名前でアクセルする。
       }

        $post->save();
        return redirect()->route('post.create')->with('message','投稿を作成しました!!!');//投稿画面に戻る
    }


    //投稿の詳細画面を表示
    public function show(Post $post)
    {
        return view('post.show', compact('post'));
    }

    //編集画面を表示
    public function edit(Post $post)
    {
        $this->authorize('update',$post);//ポリシーを適用する。投稿者と管理者以外はここで制限を受ける。PostPolicy.phpで設定する。
        return view('post.edit', compact('post'));
    }


    // 更新
    public function update(Request $request, Post $post)
    {
        // dd($request);

        $this->authorize('update',$post);//制限する PostPolicy.phpで投稿者と管理者以外はここの時点で入れない。

        //バリデーション
        $inputs = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required|max:255',
            // 'image' => 'image|max:3024',
        ]);

        $post->title = $inputs['title'];
        $post->body = $request['body'];


        if(request('image')){//もしrequestの中にimageがあったら
        $original = request()->file('image')->getClientOriginalName();//元の写真の名前を$nameに入れる。これしないと元の名前が消える。
        $name = date('Ymd_His').'_'.$original;//今の時間＋ファイル名　同じファイルだと上書きされてしまうから別にするため。
        request()->file('image')->move('storage/images',$name);//$nameという名前で、storage/imagesに入れる。
        $post->image = $name; //レコードのimageに$nameを入れる。名前でアクセルする。
       }

        $post->save();
        return redirect()->route('post.show',$post)->with('message','投稿を編集しました!!!');//投稿画面に戻る
    }

    //削除
    public function destroy(Post $post)
    {
        $this->authorize('delete',$post);//制限する PostPolicy.phpで投稿者と管理者以外はここの時点で入れない。

        //削除するときに 投稿してある画像情報もStorageから削除する。
        if (isset($post->image)) {//もし、アイコンがデフォルト画像でなければtrueで入る。
            $oldimage='public/images/'.$post->image;
            //古いアイコンのパスを取得する。保存されているStorageapppublic avatarの中の画像のパス　ここを削除すると、シンボリックリンクのpublic storage avatar　の中も削除される。
            Storage::delete($oldimage);
            }

        $post->comments()->delete();//投稿に紐づいているコメントも同時に削除してあげる
        $post->delete();//投稿を削除する
        return redirect()->route('post.index')->with('message','投稿を削除しました!!!');
    }

    //自分の投稿のみ表示する処理
    public function mypost()
    {
        $user = auth()->user()->id;

        $posts = Post::where('user_id',$user)-> orderBy('created_at','desc')->get();
        return view('post.mypost',compact('posts'));
    }

    //自分のコメントした投稿のみ表示する処理
    public function mycomment() {
        $user=auth()->user()->id;
        $comments=Comment::where('user_id', $user)->orderBy('created_at', 'desc')->get();
        return view('post.mycomment', compact('comments'));
    }
}
