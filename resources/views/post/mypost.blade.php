<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            投稿の一覧
        </h2>

        <x-message :message="session('message')" />

    </x-slot>

    {{-- 投稿一覧表示用のコード --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <!-- {{ auth()->user()->name }}さん、こんにちは！ -->

        @if(count($posts) == 0)
           <p class="mt-4">
              投稿がありません！
           </p>
        @endif

        @foreach ($posts as $post)
            <div class="mx-4 sm:p-8">
                <div class="mt-4">
                   <div class="bg-white w-full  rounded-2xl px-10 pt-2 pb-8 shadow-lg hover:shadow-2xl transition duration-500">
                        <div class="mt-4">
                            <h1 class="text-base text-gray-500 font-semibold text-right">
                                        <p style="text-align: right">
                                           {{ $post->user->name??'削除されたユーザー' }}　<span style="font-size:10px;">さんの投稿</span>
                                        </p>
                            </h1>
                            <div class="flex">
                               {{-- アバター表示 --}}
                                    {{-- 二項演算子【値】 ?? 【A】もし値＝null ならAにする --}}
                                   <img src="{{asset('storage/avatar/'.($post->user->avatar??'user_default.jpg'))}}" width="60" height="auto">

                               <h1 class="text-lg text-gray-700 font-semibold hover:underline cursor-pointer float-left pt-4">
                                        <a href="{{ route('post.show',$post) }}">
                                           {{ $post->title }}
                                        </a>
                               </h1>
                            </div>
                            <hr class="w-full">
                            <p class="mt-4 text-gray-600 py-4">{{Str::limit($post->body,50, '...')}} </p>
                            <!-- もし画像があれば画像を表示する -->
                            @if($post->image)
                            <div>
                              <!-- (画像ファイル: {{ $post->image }}) -->
                            </div>
                            <img src="{{ asset('storage/images/' .$post->image) }}" class="mx-auto"  style="height:300px;">
                            @endif
                            <div class="text-sm font-semibold flex flex-row-reverse mt-3">
                                <!-- <p>{{ $post->user->name }} • {{$post -> created_at -> diffForHumans()}}</p> -->
                                {{$post->created_at->format('Y年m月d日')}}
                            </div>
                            <hr class="w-full mb-2">
                            @if ($post->comments->count())
                              <span class="badge">
                              コメント {{$post->comments->count()}}件
                            </span>
                            @else
                            <span>コメントはまだありません。</span>
                            @endif
                            <a href="{{route('post.show', $post)}}" style="color:white;">
                               <x-primary-button class="float-right">コメントする</x-primary-button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
