@extends('layouts.main')

@section('header')

    <header class="header">

        @parent

    </header>

@endsection

@section('main')

    <main class="main main-dashboard">
        <div class="container">
            <h2 class="h2 text-uppercase p-b-60">Twitter bounty program</h2>
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
                    <li>500+ followers:  1 stake/retweet | 2 stake/tweet</li>
                    <li>1,500+ followers: 2 stake/retweet | 4 stake/tweet</li>
                    <li>5,000+ followers: 5 stake/retweet | 10 stake/tweet</li>
                    <li>10,000+ followers: 11 stake/retweet | 24 stake/tweet</li>
                </ul>

                <p class="mt-40 h4"><a target="_blank" href="https://docs.google.com/forms/d/10g-rEqmT21YIgawJUQUKlw2rtemDK_9uVgH5RiZarUw/viewform?edit_requested=true">Fill the form to Join</a></p>

                <h3 class="h4 mt-40 mb-20">How to join:</h3>
                <ul class="styl-list styl-list--sm">
                    <li>Follow us on <a href="https://twitter.com/zantepay" target="_blank">Twitter</a></li>
                    <li>Minimum rank (bitcointalk) of the participant: Jr. Member.</li>
                    <li>7 tweet (maximum) in a week and retweet of all tweets in our group.  </li>
                    <li>1 tweet per day.  </li>
                    <li>Posts older than this reporting week are not accepted.</li>
                    <li>All tweets have to be with a hashtag #Zantepay #ZPAY  #newmainstreamcryptocurrency and link <a href="{{ asset('/') }}" target="_blank">{{ asset('/') }}</a>.</li>
                    <li>Your account must be at least 6 month old.</li>
                    <li>Your audit score must be more than 90%.</li>
                    <li>Do not RT tweets replying to other users.</li>
                    <li>Retweets & tweets must stay public until the end of the Bounty campaign.</li>
                    <li>Twitter account must be original. Inactive account or account with fake followers will not be accepted. </li>
                    <li>Post report in this thread till every Sunday until 23:59 UTC.</li>
                </ul>
            </div>


        </div>
    </main>

@endsection


@section('popups')

    @parent

@endsection