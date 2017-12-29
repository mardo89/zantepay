@extends('layouts.user')

@section('main-menu')
    <li><a href="profile">Profile</a></li>
    <li><a href="profile-settings">Profile Settings</a></li>
    <li><a href="invite-friend">Refer a Friend</a></li>
    <li class="current-menu-item"><a href="wallet">Wallet</a></li>
    <li><a href="debit-card">ZANTEPAY Debit Card</a></li>
@endsection

@section('content')

    <main class="main main-dashboard">
        <div class="container">
            <div class="dashboard-row">

                <div class="dashboard-left">

                    <div class="dashboard-group">
                        <div class="table-responsive-500">
                            <table class="table-transparent">
                                <thead>
                                <tr>
                                    <th width="25%">Commission (BTC)</th>
                                    <th width="25%">Commission (ETH)</th>
                                    <th width="25%">Total ZNX</th>
                                    <th width="25%">Referal bonus (ZNX)</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>{{ $wallet->znx_amount }}</td>
                                    <td>0</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="dashboard-group">
                        <h2 class="h4 headline-mb">ZANTECOIN (ZNX) price:</h2>
                        <div class="table-responsive-500">
                            <table class="table-black table-3-cols">
                                <thead>
                                <tr>
                                    <th>Cryptocurrency</th>
                                    <th>Abbreviation</th>
                                    <th>Price</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="col-left">Bitcoin</td>
                                    <td>BTC</td>
                                    <td>0,0001</td>
                                </tr>
                                <tr>
                                    <td class="col-left">Etherium</td>
                                    <td>ETH</td>
                                    <td>0,0018</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="dashboard-group">
                        <h2 class="h4 headline-mb">Select your payment method:</h2>
                        <div class="row">
                            <div class="col-6 col-sm-6">
                                <a href="#deposit-btc" class="js-popup-link mb-10 btn btn--shadowed-light btn--medium">Deposit BTC</a>
                                <div class="text-sm text-gray">Minimum 0,02 BTC</div>
                            </div>
                            <div class="col-6 col-sm-6">
                                <a href="#deposit-eth" class="js-popup-link mb-10 btn btn--shadowed-light btn--medium">Deposit ETH</a>
                                <div class="text-sm text-gray">Minimum 0,2 ETH</div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h2 class="h4 headline-mb">Refer a friend for a 20% commission:</h2>
                        <div class="row row-middle">
                            <div class="col-md-2">
                                <label for="field3" class="field-label">Referral link:</label>
                            </div>
                            <div class="col-lg-6 col-md-5">
                                <div class="field-group">
                                    <input class="input-field" type="text" name="referral" id="field3"
                                           value="{{ $referralLink }}" readonly>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-5">
                                <button id="copy-link" type="button" class="field-btn btn btn--shadowed-light btn--medium btn--full-w">Copy Link</button>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="dashboard-right">

                    <div class="dashboard-group">
                        <h2 class="h4 headline-mb">Withdraw / trasfer:</h2>
                        <div class="row">
                            <div class="col-12 col-sm-2 mb-20">Type:</div>
                            <div class="col-6 col-sm-5">
                                <ul class="radio-list text-regular">
                                    <li>
                                        <div class="radio-button">
                                            <input type="radio" name="pay-method" value="btc-znx" id="radio1" checked><label for="radio1">Transfer
                                                BTC to ZNX</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="radio-button">
                                            <input type="radio" name="pay-method" value="btc" id="radio2"><label for="radio2">Withdraw
                                                BTC</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-5 col-sm-5">
                                <ul class="radio-list text-regular">
                                    <li>
                                        <div class="radio-button">
                                            <input type="radio" name="pay-method" value="eth-znx" id="radio5"><label for="radio5">Transfer
                                                ETH
                                                to ZNX</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="radio-button">
                                            <input type="radio" name="pay-method" value="eth" id="radio6"><label for="radio6">Withdraw
                                                ETH</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="dashboard-group">
                        <div class="row">
                            <div class="col-md-3 col-sm-12 mt-10 mb-15">Amount:</div>
                            <div class="col-md-4 col-sm-5">
                                <div class="field-group">
                                    <input class="input-field" type="text" name="eth" id="field1">
                                    <span class="sub-field-label">ETH</span>
                                </div>
                            </div>
                            <div class="col-md-1 text-center field-or col-sm-2 mt-10">or</div>
                            <div class="col-md-4 col-sm-5">
                                <div class="field-group">
                                    <input class="input-field" type="text" name="znx" id="field2">
                                    <span class="sub-field-label">ZNX</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="dashboard-group">
                        <div class="row">
                            <div class="col-md-3 col-lg-4 mt-10 mb-15">Wallet address:</div>
                            <div class="col-md-9 col-lg-8">
                                <div class="field-group mb-20">
                                    <input class="input-field" type="text" name="spend" id="field3">
                                </div>
                                <button type="button" class="btn btn--shadowed-light btn--medium mb-10">Withdraw</button>
                            </div>
                        </div>
                    </div>

                    <div class="dashboard-group">
                        <h2 class="h4 headline-mb">Pre-sale starts in:</h2>
                        <div class="countdown">
                            <span class="js-countdown" data-date="2017/12/12 12:34:00"></span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-40 p-t-30">
                <h2 class="h4 headline-mb">Withdawal / transfer history:</h2>

                <div class="table-responsive-500">
                    <table class="table table-black table-3-cols">
                        <thead>
                        <tr>
                            <th>Time</th>
                            <th>Description</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="col-left">Lorem ipsum</td>
                            <td class="col-left">Lorem ipsum</td>
                            <td class="col-left">Lorem ipsum</td>
                        </tr>
                        <tr>
                            <td class="col-left">Status</td>
                            <td class="col-left">Lorem ipsum</td>
                            <td class="col-left">Lorem ipsum</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

@endsection

@section('popups')

    <!-- Deposit ETH -->
    <div class="logon-modal mfp-hide logon-modal--450" id="deposit-eth">
        <div class="logon-modal-container">
            <h3 class="h4">DEPOSIT ETH</h3>
            <p class="text-regular break-word">0x9A833f93884D2D47F821d43B0dd51416FA6BA9e4</p>

            <img src="images/eth-qr-code.png" alt="ETH QR">
            <div class="text-gray text-sm">Minimum 0,2 ETH</div>

            <div class="row mt-35 vertical-middle-col deposit-modal-row">
                <div class="col-5 col-sm-5 text-left">
                    <div class="form-group">
                        <label class="field-label" for="field8">Purchase amount:</label>
                        <input class="input-field" type="text" name="ETH" id="field8">
                        <span class="sub-field-label">ETH</span>
                    </div>
                </div>
                <div class="col-2 col-sm-2">
                    <div class="mb-20">or</div>
                </div>
                <div class="col-5 col-sm-5 text-left">
                    <div class="form-group">
                        <label class="field-label" for="field9">Receive:</label>
                        <input class="input-field" type="text" name="ZNX" id="field9">
                        <span class="sub-field-label">ZNX</span>
                    </div>
                </div>
            </div>
            <div class="mt-20">To receive tokens, please send us the screenshot of the payment together with your email address to
                support@zantepay.com.
            </div>
        </div>
    </div>

    <!-- Deposit BTC -->
    <div class="logon-modal mfp-hide logon-modal--450" id="deposit-btc">
        <div class="logon-modal-container">
            <h3 class="h4">DEPOSIT BTC</h3>
            <p class="text-regular break-word">33vvLqhAPeD2h8P965uFstz5cC8Lo3t27d</p>

            <img src="images/btc-qr-code.png" alt="ETH QR">
            <div class="text-gray text-sm">Minimum 0,02 BTC</div>

            <div class="row mt-35 vertical-middle-col deposit-modal-row">
                <div class="col-5 col-sm-5 text-left">
                    <div class="form-group">
                        <label class="field-label" for="field10">Purchase amount:</label>
                        <input class="input-field" type="text" name="BTC" id="field10">
                        <span class="sub-field-label">BTC</span>
                    </div>
                </div>
                <div class="col-2 col-sm-2">
                    <div class="mb-20">or</div>
                </div>
                <div class="col-5 col-sm-5 text-left">
                    <div class="form-group">
                        <label class="field-label" for="field11">Receive:</label>
                        <input class="input-field" type="text" name="ZNX" id="field11">
                        <span class="sub-field-label">ZNX</span>
                    </div>
                </div>
            </div>
            <div class="mt-20">To receive tokens, please send us the screenshot of the payment together with your email address to
                support@zantepay.com.
            </div>
        </div>
    </div>

@endsection