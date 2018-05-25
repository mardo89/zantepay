@extends('layouts.user')

@section('main-menu')
    <li><a href="profile">Profile</a></li>
    <li><a href="profile-settings">Verify Account</a></li>
    <li><a href="invite-friend">Refer a Friend</a></li>
    <li><a href="wallet">Wallet</a></li>
    <li class="current-menu-item"><a href="debit-card">ZANTEPAY Debit Card</a></li>
@endsection

@section('content')

    <main class="main main-dashboard">
        <div class="container">
            <div class="card-steps-wrap text-center p-t-60 row">
                <div class="col-xl-8 offset-xl-2 col-lg-10 offset-lg-1">
                    <h2 class="h4">Current pre-order is currently available for EU countries only. To get a card pre-order bonus you have to be a citizen of listed countries and get your verification approved.</h2>
                </div>
            </div>

            <div class="row">

                <div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2">
                    <div class="step0-container">
                        <div class="text-center mt-15">
                            <div class="form-group">
                                <ul>
                                    <li>Austria</li>
                                    <li>Belgium</li>
                                    <li>Bulgaria</li>
                                    <li>Croatia</li>
                                    <li>Cyprus</li>
                                    <li>Czech Republic</li>
                                    <li>Denmark</li>
                                    <li>Estonia</li>
                                    <li>Finland</li>
                                    <li>France</li>
                                    <li>Germany</li>
                                    <li>Greece</li>
                                    <li>Hungary</li>
                                    <li>Ireland</li>
                                    <li>Italy</li>
                                    <li>Latvia</li>
                                    <li>Lithuania</li>
                                    <li>Luxembourg</li>
                                    <li>Malta</li>
                                    <li>Netherlands</li>
                                    <li>Poland</li>
                                    <li>Portugal</li>
                                    <li>Romania</li>
                                    <li>Slovakia</li>
                                    <li>Slovenia</li>
                                    <li>Spain</li>
                                    <li>Sweden</li>
                                    <li>United Kingdom (UK)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </main>

@endsection

@section('scripts')
    <script src="/js/user_debit_card.js" type="text/javascript"></script>
@endsection