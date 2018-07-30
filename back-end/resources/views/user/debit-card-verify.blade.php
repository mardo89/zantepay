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
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="dashboard-group p-b-60 text-center">
                        <h2 class="h3 headline-mb">Please verify your account before you can pre-order Zantepay debit card</h2>
                        <a href="profile-settings" class="btn btn--shadowed-light">Verify Account</a>
                    </div>
                    <div>
                        <h2 class="h4 headline-mb">Earn 500Zpay just by referring friends to pre-order ZANTEPAY card:</h2>
                        <div class="row row-bottom" style="max-width: 650px;">
                            <div class="col-md-7">
                                <div class="field-group">
                                    <label class="field-label" for="field2">Referral link:</label>
                                    <input class="input-field" type="text" name="referral" value="{{ $referralLink }}" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-5">
                                <button id="copy-link" type="button" class="mb-7 field-btn btn btn--shadowed-light btn--medium btn--full-w">Copy Link</button>
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