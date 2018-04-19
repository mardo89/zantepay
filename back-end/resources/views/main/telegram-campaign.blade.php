@extends('layouts.main')

@section('header')

    <header class="header">

        @parent

    </header>

@endsection

@section('main')

    <main class="main main-dashboard">
        <div class="container">
            <h2 class="h2 text-uppercase p-b-60">Telegram  bounty program</h2>
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
                <h3 class="h4 mb-20">Rewards:</h3>
                <ul class="styl-list styl-list--sm">
                    <li>Subscribe: 5 stake</li>
                    <li>TOP-15 activity: 10-70 stake / week</li>
                </ul>

                <p class="mt-40 h4"><a target="_blank" href="https://docs.google.com/forms/d/1HbuT4LXxxDmkvTSJXHQeF0IpDqPAJCHYZMbUNJkvUYI/viewform?edit_requested=true">Fill the form to Join</a></p>

                <h3 class="h4 mt-40 mb-20">How to join:</h3>
                <ul class="styl-list styl-list--sm">
                    <li>Subscribe to our <a href="http://telegram.me/zantepay" target="_blank">Telegram</a></li>
                    <li>At least 10 posts in week. </li>
                    <li>You must not discuss bounty topics in the telegram group. Please keep all bounty conversations to this thread only or PM Trugad in Telegram.</li>
                    <li>The task of participants is to give the constructive answers to others members’ questions, to provoke activity in chat if it “sleeps”, if you have an opportunity - invite people to chat, share your positive opinion about the project.</li>
                    <li>At the end of the week Telegram-manager will appreciate your activity in chat. The maximum award a participant may receive is 70 stakes in a week, it’s 10 stakes daily.</li>
                    <li>SPAM and non-constructive discussions are not taken into account.</li>
                </ul>
            </div>


        </div>
    </main>

@endsection


@section('popups')

    @parent

@endsection