@extends('layouts.user')

@section('main-menu')
    <li><a href="profile">Profile</a></li>
    <li><a href="profile-settings">Verify Account</a></li>
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
                            <div class="row">
                                <div class="col-xl-6">Austria</div>
                                <div class="col-xl-6">Belgium</div>
                                <div class="col-xl-6">Bulgaria</div>
                                <div class="col-xl-6">Croatia</div>
                                <div class="col-xl-6">Cyprus</div>
                                <div class="col-xl-6">Czech Republic</div>
                                <div class="col-xl-6">Denmark</div>
                                <div class="col-xl-6">Estonia</div>
                                <div class="col-xl-6">Finland</div>
                                <div class="col-xl-6">France</div>
                                <div class="col-xl-6">Germany</div>
                                <div class="col-xl-6">Greece</div>
                                <div class="col-xl-6">Hungary</div>
                                <div class="col-xl-6">Ireland</div>
                                <div class="col-xl-6">Italy</div>
                                <div class="col-xl-6">Latvia</div>
                                <div class="col-xl-6">Lithuania</div>
                                <div class="col-xl-6">Luxembourg</div>
                                <div class="col-xl-6">Malta</div>
                                <div class="col-xl-6">Netherlands</div>
                                <div class="col-xl-6">Poland</div>
                                <div class="col-xl-6">Portugal</div>
                                <div class="col-xl-6">Romania</div>
                                <div class="col-xl-6">Slovakia</div>
                                <div class="col-xl-6">Slovenia</div>
                                <div class="col-xl-6">Spain</div>
                                <div class="col-xl-6">Sweden</div>
                                <div class="col-xl-6">United Kingdom (UK)</div>
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