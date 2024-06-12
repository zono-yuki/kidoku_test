<!-- レイアウトはguest-layoutを使用 slotの部分にこのファイルが入るかんじ♪ -->
<x-guest-layout>
 <div class="h-screen pb-14 bg-right bg-cover">
    <div class="container pt-10 md:pt-18 px-6 mx-auto flex flex-wrap flex-col md:flex-row items-center bg-yellow-50">
        <!--左側-->
        <div class="flex flex-col w-full xl:w-2/5 justify-center lg:items-start overflow-y-hidden ">
            <h1 class="my-4 text-3xl md:text-5xl text-green-800 font-bold leading-tight text-center md:text-left slide-in-bottom-h1">エンジニアの犬猫談議</h1>
            <p class="leading-normal text-base md:text-2xl mb-8 text-center md:text-left slide-in-bottom-subtitle">
                仕事や勉強の合間に、犬猫談議するエンジニアの集い場です。
            </p>
            <p class="leading-normal text-base md:text-2xl mb-8 text-center md:text-left slide-in-bottom-subtitle">
                プログラミングの話題もウェルカム♪
            </p>

            <p class="text-blue-400 font-bold pb-8 lg:pb-6 text-center md:text-left fade-in">
                会員募集中。お気軽にひょっこりきてください。
            </p>

            <div class="flex w-full justify-center md:justify-start pb-24 lg:pb-0 fade-in ">
                {{-- ボタン設定 --}}
                <a href="{{ route('contact.create') }}">
                   <button class="btnsetg">お問い合わせ</button>
                </a>
                <a href="{{ route('register') }}">
                    <button class="btnsetr">ご登録はこちら</button>
                </a>

            </div>
        </div>
        {{-- 右側 --}}
        <div class="w-full xl:w-3/5 py-6 overflow-y-hidden">
            <img class="w-5/6 mx-auto lg:mr-0 slide-in-bottom rounded-lg shadow-xl" src="{{asset('logo/top.jpg')}}">
        </div>
    </div>
    <div class="container pt-10 md:pt-18 px-6 mx-auto flex flex-wrap flex-col md:flex-row items-center">
        <div class="w-full text-sm text-center md:text-left fade-in border-2 p-4 text-red-800 leading-8 mb-8">
            <p>犬と猫が好きでこのサイトを作りました✌️</p>
            <p>新しい機能をどんどん追加していきますのでお楽しみに！！笑</p>
            <p>どうぞよろしくお願いいたします🌈</p>
        </div>
        <!--フッタ-->
        <div class="w-full pt-10 pb-6 text-sm md:text-left fade-in">
            <p class="text-gray-500 text-center">@2024 Maezono Inc.</p>
        </div>
    </div>
 </div>
</x-guest-layout>
