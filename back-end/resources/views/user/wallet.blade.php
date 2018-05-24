@extends('layouts.user')

@section('main-menu')
    <li><a href="profile">Profile</a></li>
    <li><a href="profile-settings">Account Verification</a></li>
    <li><a href="invite-friend">Refer a Friend</a></li>
    <li class="current-menu-item"><a href="wallet">Wallet</a></li>
    <li><a href="debit-card">ZANTEPAY Debit Card</a></li>
@endsection

@section('content')

    <main class="main main-dashboard main-panel-layout">
        <div id="particles-js" class="particles-js-black"></div>
        <div class="container">
            <div class="panel">
                <div class="mb-20">
                    <h2 class="h4 headline-mb">Buy ZNX tokens now:</h2>
                    <div class="row row-middle rate-calculator">
                        <div class="col col-md-3 col-lg-auto">
                            <div class="form-group text-regular">You will get</div>
                        </div>
                        <div class="col-lg-4 col-md-9">
                            <div class="input-group form-group">
                                <label for="field10">ZNX tokens</label>
                                <input class="input-field" type="text" name="znx_amount" id="field10" placeholder="Enter amount">
                            </div>
                        </div>
                        <div class="col col-md-3 col-lg-auto">
                            <div class="form-group text-regular">For</div>
                        </div>
                        <div class="col-lg-4 col-md-9">
                            <div class="input-group form-group">
                                <label for="field11">ETH</label>
                                <input class="input-field" type="text" name="eth_amount" id="field11" placeholder="Enter amount">
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="mb-20 text-regular">Your personal Ethereum address to fund this account:</div>
                    <div class="row row-middle wallet">
                        @if ($gettingAddress)
                            <div class="col col-md-3">
                                <a href="" class="btn btn--shadowed-light btn--medium mt-sm-15 is-loading" disabled>
                                    Create Address
                                    <div class="spinner spinner--30">
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                    </div>
                                </a>
                            </div>

                            <div class="col col-md-12 mt-20 primary-color text-sm address-warning">
                                Your request is pending. Please refresh the page to see the result.
                            </div>
                        @elseif($wallet->eth_wallet)
                            <div class="col col-sm-auto text-lg wordwrap address">{{ $wallet->eth_wallet }}</div>

                            <div class="col col-md-3">
                                <a id="copy-address" href="" class="btn btn--shadowed-light btn--medium btn--130 mt-sm-15">Copy</a>
                            </div>
                        @else
                            <div class="col col-md-3">
                                <a href="" class="btn btn--shadowed-light btn--medium mt-sm-15 create-address">Create Address</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 col-xl-6">
                    <div class="panel">
                        <div class="dashboard-group">
                            <h2 class="h4 headline-mb">Wallet:</h2>
                            <div class="table-responsive-500">
                                <table class="table-transparent table-3-cols">
                                    <thead>
                                    <tr>
                                        <th>ICO ZNX</th>
                                        <th>Card pre-order bonuses</th>
                                        <th>Card referral bonus</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>{{ $wallet->znx_amount }}</td>
                                        <td>{{ $wallet->debit_card_bonus }}</td>
                                        <td>{{ $wallet->referral_bonus }}</td>
                                    </tr>
                                    </tbody>
                                </table>
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
                                        <input class="input-field" type="text" name="referral" id="field3" value="{{ $referralLink }}"
                                               readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-5">
                                    <button id="copy-link" type="button" class="field-btn btn btn--shadowed-light btn--medium btn--full-w">
                                        Copy Link
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-xl-6">
                    <div class="panel">
                        <div class="dashboard-group">
                            <h2 class="h4 headline-mb">ZANTECOIN (ZNX) price:</h2>
                            <div class="table-responsive-500">
                                <table class="table-black table-3-cols">
                                    <thead>
                                    <tr>
                                        <th>Cryptocurrency</th>
                                        <th>Coin</th>
                                        <th>Price</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="col-left">Ethereum</td>
                                        <td>ETH</td>
                                        <td>{{ $ico['znx_rate'] }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div>
                            <h2 class="h4 mb-20">{{ $ico['part_name'] }} ends in:</h2>
                            <div class="countdown">
                                <span class="js-countdown" data-date="{{ $ico['end_date'] }}"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel">
                <h2 class="h4 mb-20">Bonuses:</h2>

                <span>{{ $wallet->commission_bonus }} ETH Available </span>

                <div class="row">
                    <div class="col-lg-6 mb-md-30">
                        <h2 class="h4 headline-mb">Transfer ETH to ZNX:</h2>
                        <div class="row row-middle mt-40">
                            <div class="col-lg-2 col-md-3">
                                <div class="form-group text-regular"><label for="field14">Amount:</label></div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <input id="field14" class="input-field" type="text" name="transfer_eth_amount" placeholder="ETH">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-md-8 offset-md-3 offset-lg-2">
                                <a href="" id="transfer_btn" class="btn btn--shadowed-light btn--medium btn--130">Transfer</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <h2 class="h4 headline-mb">Withdraw ETH:</h2>
                        <div class="row row-middle mt-40">
                            <div class="col-md-3 pr-0">
                                <div class="form-group text-regular"><label for="field16">Wallet address:</label></div>
                            </div>
                            <div class="col-md">
                                <div class="form-group">
                                    <input id="field16" class="input-field" type="text" name="withdraw_address" placeholder="Paste address">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-md-8 offset-md-3">
                                <a href="" id="withdraw_btn" class="btn btn--shadowed-light btn--medium btn--130">Withdraw</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel">
                <h2 class="h4 mb-20">Transfer:</h2>
                <div class="text-regular mb-20">Available for transfer: <span id="available_znx_amount"
                                                                              class="primary-color text-lg"> {{ $availableAmount }} </span>
                </div>
                <div class="text-regular">You will be able to withdraw your ERC20 tokens to Ethereum Network after the end of the ICO.</div>
            </div>

            <div class="panel">
                <!-- toggle class .is-active for .update-icon while loadding proccess -->
                <h2 class="h4 headline-mb">Transactions history: </h2>
                <div class="table-responsive-500">
                    <table class="table-black">
                        <thead>
                        <tr>
                            <th width="105">Date</th>
                            <th width="115">Time</th>
                            <th width="70">Type</th>
                            <th width="220">Address</th>
                            <th width="150">Amount</th>
                            <th width="110">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($transactions as $transaction)
                            <tr>
                                <td> {{ $transaction['date'] }} </td>
                                <td> {{ $transaction['time'] }} </td>
                                <td>{{ $transaction['type'] }}</td>
                                <td>{{ $transaction['address'] }}</td>
                                <td class="nowrap">{{ $transaction['amount'] }}</td>
                                <td>{{ $transaction['status'] }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row-bellow-panel text-center">If you have any questions, suggestions or comments on the wallet’s work, feel free to
                reach us at
                <a href="mailto:support@zantepay.com">support@zantepay.com</a>
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

            <img src="/images/eth-qr-code.png" alt="ETH QR">
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

            <img src="/images/btc-qr-code.png" alt="ETH QR">
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

    <!-- Address confirmation -->
    <div class="logon-modal mfp-hide" id="wallet-address-modal">
        <div class="logon-modal-container">
            <h3 class="h4">CREATED!</h3>
            <div class="logon-modal-text">
                <p>Your Ethereum address successfully created.</p>
            </div>
        </div>
    </div>

    <!-- Transfer confirmation -->
    <div class="logon-modal mfp-hide" id="transfer-modal">
        <div class="logon-modal-container">
            <h3 class="h4">TRANSFERED!</h3>
            <div class="logon-modal-text">
                <p>
                    <span id="znx_balance"> </span> ZNX successfully transferred!
                </p>
            </div>
        </div>
    </div>

    <!-- Withdraw confirmation -->
    <div class="logon-modal mfp-hide" id="withdraw-modal">
        <div class="logon-modal-container">
            <h3 class="h4">TRANSFERED!</h3>
            <div class="logon-modal-text">
                <p>You have successfully submitted a withdrawal request!</p>
            </div>
        </div>
    </div>

    <!-- WELCOME TO ZANTEPAY -->
    <div class="logon-modal mfp-hide logon-modal--560" id="welcome">
        <div class="logon-modal-container">
            <h3 class="h4 text-uppercase">Welcome to ZANTEPAY</h3>
            <form id="frm_welcome">
                <div class="logon-group">Before you can proceed you must read & accept:</div>

                <div class="logon-group text-left">
                    <div class="checkbox">
                        <input type="checkbox" name="tc_item" id="check12">
                        <label for="check12">I’ve read and understood the
                            <a href="{{ asset('storage/Zantepay_Terms_and_Conditions.pdf') }}">Terms & Conditions</a>
                        </label>
                    </div>
                </div>

                <div class="logon-group text-left">
                    <div class="checkbox">
                        <input type="checkbox" name="tc_item" id="check13">
                        <label for="check13">I’ve read, understood and agree with the
                            <a href="{{ asset('storage/Zantepay_Privacy_Policy.pdf') }}">Privacy Terms</a>
                        </label>
                    </div>
                </div>

                <div class="logon-group text-left">
                    <div class="checkbox">
                        <input type="checkbox" name="tc_item" id="check11">
                        <label for="check11">I’ve read, understood and agree with the
                            <a href="{{ asset('storage/Zantepay_Whitepaper.pdf') }}">Whitepaper</a>
                        </label>
                    </div>
                </div>

                <div class="logon-group text-left">
                    <div class="checkbox">
                        <input type="checkbox" name="tc_item" id="check14">
                        <label for="check14">I’ve read, understood and agree with the
                            <a href="{{ asset('storage/Zantepay_Cookie_Policy.pdf') }}">Cookie Policy</a>
                        </label>
                    </div>
                </div>

                <div class="logon-group text-left">
                    <div class="checkbox">
                        <input type="checkbox" name="tc_item" id="check15"><label for="check15">Citizens and residents from the US, South
                            Korea and China are not allowed to contribute during the ICO.</label>
                    </div>
                </div>

                <div class="logon-submit">
                    <div class="row justify-content-center">
                        <div class="col-sm-4 col-6">
                            <a id="logout-btn" href="" class="js-close-popup btn btn--shadowed-light btn--260">Cancel</a>
                        </div>
                        <div class="col-sm-4 col-6">
                            <input class="btn btn--shadowed-light btn--260" type="submit" value="Ok">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="/js/user_wallet.js" type="text/javascript"></script>

    @if($showWelcome)
        <script type='text/javascript'>
            $.magnificPopup.open(
                {
                    items: {
                        src: '#welcome'
                    },
                    type: 'inline',
                    midClick: true,
                    showCloseBtn: false,
                    closeOnBgClick: false,
                    mainClass: 'mfp-fade',
                    fixedContentPos: false,
                    callbacks: {
                       open: function() {
                          $('body').addClass('noscroll');
                       },
                       close: function() {
                           $('body').removeClass('noscroll');
                       }
                    }
                }
            );

        </script>
    @endif
@endsection