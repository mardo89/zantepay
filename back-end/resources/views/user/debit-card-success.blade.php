@extends('layouts.user')

@section('main-menu')
    <li><a href="profile">Profile</a></li>
    <li><a href="profile-settings">Account Verification</a></li>
    <li><a href="invite-friend">Refer a Friend</a></li>
    <li><a href="wallet">Wallet</a></li>
    <li class="current-menu-item"><a href="debit-card">ZANTEPAY Debit Card</a></li>
@endsection

@section('content')

    <main class="main main-dashboard">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="dashboard-group">
                        <h2 class="h4 headline-mb">Thanks! Your pre-order of ZANTEPAY card has been confirmed!</h2>
                        <p>Selected design:</p>
                        <div class="row">
                            <div class="col-sm-6">

                                @if($debitCard === \App\Models\DB\DebitCard::DESIGN_WHITE)
                                    <img src="/images/wh-card.jpg" srcset="/images/wh-card@2x.jpg 2x" alt="ZANTEPAY Card">
                                @elseif($debitCard === \App\Models\DB\DebitCard::DESIGN_RED)
                                    <img src="/images/red-card.jpg" srcset="/images/red-card@2x.jpg 2x" alt="ZANTEPAY Card">
                                @else
                                    Debit Card not selected
                                @endif

                            </div>
                            <div class="col-sm-6">
                                <p>Pre-order bonus tokens received: <br> 500 ZNX (locked)</p>
                                <h3 class="h5">Terms:</h3>
                                <ol>
                                    <li><a href="/user/profile-settings">Verify account</a> to unlock bonus!!</li>
                                    <li>Make a deposit to ZANTEPAY wallet or buy ZNX tokens from ICO to unlock your bonus. Bonus tokens will be available after ICO.</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h2 class="h4 headline-mb">Earn 500ZNX just by referring friends to pre-order ZANTEPAY card:</h2>
                        <div class="row row-bottom" style="max-width: 650px;">
                            <div class="col-md-7">
                                <div class="field-group">
                                    <label class="field-label" for="field2">Referral link:</label>
                                    <input class="input-field" type="text" name="referral" id="field2" value="{{ $referralLink }}" readonly />
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