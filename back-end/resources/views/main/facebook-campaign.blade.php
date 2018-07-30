@extends('layouts.main')

@section('header')

    <header class="header">

        @parent

    </header>

@endsection

@section('main')

    <main class="main main-dashboard">
        <div class="container">
            <h2 class="h2 text-uppercase p-b-60">Facebook bounty program</h2>
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
                    <strong>All participants MUST be in the group (<a href="https://t.me/zantepay" target="_blank">Zantepay Telegram ANN group</a> and <a href="https://t.me/zantepay_ico" target="_blank">Zantepay Telegram Chat group</a>) until the end of ICO.</strong></p>
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
                <h3 class="h4 mb-20">Rewards:</h3>
                <ul class="styl-list styl-list--sm">
                    <li>500+ friends: 1 stake/repost | 2 stake/post</li>
                    <li>1,500+ friends: 2 stake/repost | 4 stake/post</li>
                    <li>5,000+ friends: 5 stake/repost | 10 stake/post</li>
                    <li>10,000+ friends: 11 stake/repost | 24 stake/post</li>
                </ul>

                <p class="mt-40 h4"><a target="_blank" href="https://docs.google.com/forms/d/1MakyIixcJCwpxnUDbAzWeSNYKi9EDIkCguYEpcm6gNw/viewform?edit_requested=true">Fill the form to Join</a></p>

                <h3 class="h4 mt-40 mb-20">How to join:</h3>
                <ul class="styl-list styl-list--sm">
                    <li>Follow us on <a href="https://www.facebook.com/zantepay" target="_blank">Facebook</a></li>
                    <li>Minimum rank (bitcointalk) of the participant: Jr. Member.</li>
                    <li>7 post (maximum) in a week and repost of all posts in our group.</li>
                    <li>1 post per day.   </li>
                    <li>Posts older than this reporting week are not accepted.</li>
                    <li>All posts have to be with a hashtag #Zantepay #Zpay  #newmainstreamcryptocurrency and link <a href="{{ asset('/') }}" target="_blank">{{ asset('/') }}</a>.</li>
                    <li>Your account must be at least 6 month old.</li>
                    <li>Do not RP posts replying to other users.</li>
                    <li>Repost & posts must stay public until the end of the Bounty campaign.</li>
                    <li>Facebook account must be original. Inactive account or account with fake followers will not be accepted. </li>
                    <li>Post report in this thread till every Sunday until 23:59 UTC.</li>
                </ul>
            </div>


        </div>
    </main>

@endsection


@section('popups')

    @parent

@endsection