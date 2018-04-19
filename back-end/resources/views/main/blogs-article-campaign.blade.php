@extends('layouts.main')

@section('header')

    <header class="header">

        @parent

    </header>

@endsection

@section('main')

    <main class="main main-dashboard">
        <div class="container">
            <h2 class="h2 text-uppercase p-b-60">Blog, article bounty program</h2>
            <div class="mb-20">
                <h3 class="h4 mb-10">General terms:</h3>
                <p>Stakes will be distributed in our spreadsheets every week.
                    We reserve the right to remove you from any campaign at any time if we think you are not honest, or spam the forum.<br>
                    We reserve the right to remove you from any campaign without explaining why we removed you.<br>
                    In case we remove you from the campaign for any reason, we reserve the right to delete your stakes.<br>
                    We may not accept you in the campaign for any reasons.<br>
                    If your rank changes during the campaign, you must contact the campaign manager, Trugad in a bitcointalk PM.<br>
                    Using multi-accounts, cheating and spamming are not allowed. It will result getting all of your accounts permanently banned from the campaign.<br>
                    <strong>Weekly reports in this thread.</strong><br>
                    <strong>All participants MUST be in the group (<a href="http://telegram.me/zantepay" target="_blank">Zantepay Telegram ANN group</a> and <a href="http://telegram.me/zantepay" target="_blank">Zantepay Telegram Chat group</a>) until the end of ICO.</strong></p>
                <p class="primary-color"><strong>At final calculation of points in each campaign the rating system will be used. TOP-40 receive additional points, depending on the place in rating.</strong></p>
                <p class="primary-color"><strong>For example:</strong><br>
                    1st: + 20% <br>
                    2st: + 19.5% <br>
                    3st: + 19% <br>
                    ...<br>
                    40st: + 0.5%
                </p>
            </div>
            <div class="mt-40 p-t-30">
                <h2 class="h2 mb-20 text-uppercase">Article</h2>
                <h3 class="h4 mb-20">Rewards:</h3>
                <ul class="styl-list styl-list--sm">
                    <li>SuperDuper quality: 300-1000 Stakes</li>
                    <li>Good quality: 100 Stakes</li>
                    <li>Medium quality: 70 Stakes</li>
                    <li>Normal quality: 40 Stakes</li>
                    <li>Copy/Paste quality: 10 Stakes</li>
                </ul>

                <p class="mt-40 h4"><a target="_blank" href="https://docs.google.com/forms/d/1cjP56rZ0jypar_fIHJkVXEkvGqhBecSrH3DdNbrRYkk/viewform?edit_requested=true">Fill the form to ADD </a></p>

                <h3 class="h4 mt-40 mb-20">Rules:</h3>
                <ul class="styl-list styl-list--sm">
                    <li>Low-quality articles will not be accepted.</li>
                    <li>Any languages are accepted.</li>
                    <li>Article must be genuine. You can use official images, logos, graphics posted on the website, ANN thread (not bounty thread), whitepaper, Blog, Facebook and Twitter.</li>
                    <li>Articles must be longer than 300 words, fewer than 300 words will not be accepted.</li>
                    <li>"SuperDuper" is or professional article with a large number of viewings/comments.</li>
                    <li>Medium, Steemit, Newbium, and other general/free blogging platforms are allowed.</li>
                    <li>Blog posts/articles will be accepted in .com, .net, .org and other premium websites and blogs.</li>
                    <li>To proof your ownership of the blog you must add your bitcointalk profile link to your blog posts footer.</li>
                    <li>Put the following links at the end of the article and in the video descriptions (if you didn't use them before):
                        <blockquote>
                            —---------------------------------- <br>
                            IСO [15 April, 2018 - 15 Jul, 2018] <br>
                            —---------------------------------- <br>
                            Twitter: <a target="_blank" href="https://twitter.com/zantepay">https://twitter.com/zantepay</a><br>
                            Facebook: <a target="_blank" href="https://www.facebook.com/ZANTEPAY">https://www.facebook.com/ZANTEPAY</a><br>
                            Telegram: <a target="_blank" href="https://t.me/zantepay_ico">https://t.me/zantepay_ico</a><br>
                            Instagram: <a target="_blank" href="https://www.instagram.com/zantepay/">https://www.instagram.com/zantepay/</a><br>
                            Whitepaper: <a target="_blank" href="https://zantepay.com/storage/Zantepay_Whitepaper.pdf">https://zantepay.com/storage/Zantepay_Whitepaper.pdf</a><br>
                            Website: <a target="_blank" href="https://zantepay.com/">https://zantepay.com/</a>
                        </blockquote>
                    </li>
                </ul>
            </div>
        </div>
    </main>

@endsection


@section('popups')

    @parent

@endsection