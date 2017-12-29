@extends('layouts.user')

@section('main-menu')
    <li><a href="profile">Profile</a></li>
    <li><a href="profile-settings">Profile Settings</a></li>
    <li><a href="invite-friend">Refer a Friend</a></li>
    <li><a href="wallet">Wallet</a></li>
    <li class="current-menu-item"><a href="debit-card">ZANTEPAY Debit Card</a></li>
@endsection

@section('content')

    <main class="main main-dashboard">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-lg-10">
                            <div class="dashboard-group">
                                <h2 class="h4 headline-mb">Success!</h2>
                                <p class="text-regular">You have successfully pre-ordered your debit card! <br> We will notify you, once your card is ready.</p>
                                <p class="primary-color">You have received a bonus of 60 ZNX! </p>
                            </div>
                            <div class="dashboard-group">
                                <h2 class="h4 headline-mb">Invite friends to pre-order a card, too!</h2>
                                <p class="text-regular">Invite your friends to pre-order a ZANTEPAY debit card. Once the pre-order is finished, you will receive additional 60 ZNX as a referral bonus and your friends will get 40 ZNX.</p>
                                <p class="text-uppercase">FIRST 1000 CARDS GET A BONUS OF 100 ZNX! </p>
                            </div>
                        </div>
                    </div>
                    <div class="row row-bottom">
                        <div class="col-md-7">
                            <div class="field-group">
                                <label class="field-label" for="field2">Referral link:</label>
                                <input class="input-field" type="text" name="referral" id="field2" placeholder="https://zantepay.com/en/registration/567..." value="{{ $referralLink }}" readonly="">
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-5">
                            <button id="copy-link" type="button" class="mb-7 field-btn btn btn--shadowed-light btn--medium btn--full-w">Copy Link</button>
                        </div>
                    </div>

                </div>
                <div class="col-lg-6">
                    <div class="lp-znx-wrap dashboard-group">
                        <div class="lp-znx-img">
                            <div class="lp-znx-txt"><span>600</span> ZNX</div>
                        </div>
                        <ul class="lp-znx-list">
                            <li>Pre-Sale value: <span>30€</span></li>
                            <li>ICO I value: <span>60€</span></li>
                            <li>ICO II value: <span>84€</span></li>
                            <li>ICO III value: <span>150€</span></li>
                        </ul>
                    </div>
                    <div class="lp-znx-wrap">
                        <div class="lp-znx-img">
                            <div class="lp-znx-txt"><span>400</span> ZNX</div>
                        </div>
                        <ul class="lp-znx-list">
                            <li>Pre-Sale value: <span>20€</span></li>
                            <li>ICO I value: <span>40€</span></li>
                            <li>ICO II value: <span>56€</span></li>
                            <li>ICO III value: <span>100€</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection