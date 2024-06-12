<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            投稿の個別表示
        </h2>

        <x-message :message="session('message')" />

    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="mx-4 sm:p-8">
            <div class="px-10 mt-4 mb-4">

                <div class="bg-white w-full  rounded-2xl px-10 pt-2 pb-8 shadow-lg hover:shadow-2xl transition duration-500">
                        <div class="mt-4 mb-3">
                            <h1 class="text-base text-gray-500 font-semibold text-right">
                                        <p style="text-align: right">
                                           {{ $post->user->name }}　<span style="font-size:10px;">さんの投稿</span>
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
                          <!-- <hr class="w-full"> -->
                        </div>
                        <div class="flex justify-end">

                         @can('update', $post)
                           <a href="{{route('post.edit', $post)}}">
                             <x-primary-button class="bg-teal-700 float-right">
                               編集</x-primary-button>
                           </a>
                         @endcan


                         @can('delete', $post)
                         <form method="post" action="{{route('post.destroy', $post)}}">
                            @csrf
                            @method('delete')
                              <x-primary-button class="bg-red-700 float-right ml-4" onClick="return confirm('本当に削除しますか？');">削除</x-primary-button>
                         </form>
                         @endcan

                        </div>
                       <!-- <hr class="w-full"> -->
                       <p class="mt-4 text-gray-600 py-4 whitespace-pre-line">{{$post->body}}</p>
                         <!-- もし画像があれば画像を表示する -->
                         @if($post->image)
                             <div>
                                <!-- (画像ファイル: {{ $post->image }}) -->
                             </div>
                             <img src="{{ asset('storage/images/' .$post->image) }}" class="mx-auto"  style="height:300px;">
                         @endif
                         <div class="text-sm font-semibold flex flex-row-reverse mt-3">
                           {{$post->created_at->format('Y年m月d日')}}
                            <!-- <p> {{ $post->user->name }} • {{$post->created_at->diffForHumans()}}</p> -->
                         </div>
                </div>

                {{-- コメント表示 --}}
                @foreach ($post->comments as $comment)
                <div class="bg-white w-full  rounded-2xl px-10 py-8 shadow-lg mt-8 whitespace-pre-line">
                    {{$comment->body}}
                    <div class="text-sm font-semibold flex flex-row-reverse">
                        {{-- クラスを変更 --}}
                        <p class="float-left pt-4"> {{ $comment->user->name }}　{{$comment->created_at->format('Y年m月d日')}}</p>
                        {{-- アバター追加 --}}
                        <img width="60" height="auto" src="{{asset('storage/avatar/'.($comment->user->avatar??'user_default.jpg'))}}">

                    </div>
                </div>
                @endforeach

                {{-- コメント作成 --}}
                <div class="mt-4 mb-12">
                        <form method="post" action="{{route('comment.store')}}">
                            @csrf
                            <input type="hidden" name='post_id' value="{{$post->id}}">
                            <textarea name="body" class="bg-white w-full  rounded-2xl px-4 mt-4 py-4 shadow-lg hover:shadow-2xl transition duration-500" id="body" cols="30" rows="3" placeholder="コメントを入力してください">{{old('body')}}</textarea>
                            <x-primary-button class="float-right mr-4 mb-12">コメントする</x-primary-button>
                        </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
