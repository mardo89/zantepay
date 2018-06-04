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
                <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2">
                    <div class="mb-20 text-center">
                        <h2 class="h4">ZANTEPAY FREE debit card pre-order will be available shortly after ICO (after 15th July)</h2>
                    </div>
                </div>
            </div>

            <div class="p-t-40">
                <div class="row">
                    <div class="col-md-6 col-xl-5 offset-xl-1">
                        <div class="text-center">
                            <img src="/images/wh-card.jpg" srcset="images/wh-card@2x.jpg 2x" alt="White Visa Debit Card">
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-5">
                        <div class="text-center">
                            <img src="/images/red-card.jpg" srcset="images/red-card@2x.jpg 2x" alt="Red Visa Debit Card">
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center p-t-40">
                <p class="h5">First 1000 card pre-orders will get rewarded 500 ZNX in bonus.</p>
            </div>

            <div class="row p-t-60">
                <div class="col-lg-8 offset-lg-2">
                    <h2 class="h4 headline-mb">Earn 500ZNX just by referring friends to pre-order ZANTEPAY card:</h2>
                    <div class="row row-bottom" style="max-width: 650px;">
                        <div class="col-md-7">
                            <div class="field-group">
                                <label class="field-label" for="field2">Referral link:</label>
                                <input class="input-field" type="text" name="referral" value="https://test1.zantepay.com/invitation?ref=5afaa0a06d038" readonly="">
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-5">
                            <button id="copy-link" type="button" class="mb-7 field-btn btn btn--shadowed-light btn--medium btn--full-w">Copy Link</button>
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