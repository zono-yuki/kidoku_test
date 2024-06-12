<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Gate;//これでadmin以外はprofile/indexが見れなくなる。
use Illuminate\Support\Facades\Storage;//古いアイコンの削除用で使用する。
use Illuminate\Validation\Rule;
use App\Models\Role;

class ProfileController extends Controller
{
    public function index(){//Gateを使用する。
        Gate::authorize('admin');
        $users = User::all();
        return view('profile.index',compact('users'));
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View //戻り値指定なのでreturnのあとは viewにする。
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse//戻り値の指定Redirectじゃないとエラーになる
    {
        $request->user()->fill($request->validated());//バリデーションかけたデータを全てユーザーに仮保存

        if ($request->user()->isDirty('email')) {//isDirty()変更があったらtrue
            $request->user()->sendEmailVerificationNotification();//メールアドレスが変更された時点で確認メールを飛ばす。
            $request->user()->email_verified_at = null;
        }

        // アバター画像の保存
        if($request->validated('avatar')) {//アイコンの変更がrequestにあれば、trueで入る。
            // 古いアバター削除用コード
            $user=User::find(auth()->user()->id);
            if ($user->avatar!=='user_default.jpg') {//もし、アイコンがデフォルト画像でなければtrueで入る。
            $oldavatar='public/avatar/'.$user->avatar;//古いアイコンのパスを取得する。
            Storage::delete($oldavatar);
            }
            $name=request()->file( 'avatar')->getClientOriginalName();
            $avatar=date('Ymd_His').'_'.$name;//日付＋$name
            request()->file( 'avatar')->storeAs('public/avatar', $avatar); //作った名前でpublic/avatarに画像を保存する。
            $request->user()->avatar = $avatar;
        }

        $request->user()->save();//保存する。

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/'); //戻り値はRedirectにしないとエラーになる。
    }

    public function adedit(User $user) {//管理者用アカウント編集ページの表示
        $admin=true;//管理者のフラグを立てる
        $roles=Role::all();
        return view('profile.edit', [
            'user' => $user,
            'admin' => $admin,
            'roles' => $roles,
        ]);
    }

    public function adupdate(User $user, Request $request): RedirectResponse
    {
        $inputs=$request->validate([
            'name' => ['string', 'max:255'],
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($user)],
            //ユニークにするが、ログインユーザーだと同じメールアドレスがOKというルールにする。
            'avatar'=> ['image', 'max:1024'],
        ]);

        // アバター画像の保存
        if(request()->hasFile('avatar')) {
            // 古いアバター削除用コード
            if ($user->avatar!=='user_default.jpg') {
                $oldavatar='public/avatar/'.$user->avatar;
                Storage::delete($oldavatar);
            }
            $name=request()->file( 'avatar')->getClientOriginalName();
            $avatar=date('Ymd_His').'_'.$name;
            request()->file('avatar')->storeAs('public/avatar', $avatar);
            $user->avatar = $avatar;
        }

        $user->name=$inputs['name'];
        $user->email=$inputs['email'];
        $user->save();

        return Redirect::route('profile.adedit', compact('user'))->with('status', 'profile-updated');
    }

}
