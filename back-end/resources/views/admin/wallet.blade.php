@extends('layouts.admin')

@section('main-menu')
    <li><a href="users">Users</a></li>

    @if(\Illuminate\Support\Facades\Auth::user()->role === \App\Models\DB\User::USER_ROLE_ADMIN)
        <li class="current-menu-item"><a href="wallet">Wallet</a></li>
    @endif

@endsection

@section('content')

    <main class="main main-dashboard">
        <div class="container">

            <div class="dashboard-top-panel">
                <div class="dashboard-top-panel-row tabs-head-wrap">
                    <ul class="tabs-head">
                        <li class="is-active">
                            <a href="#admin-zantecoin">ZanteCoin</a>
                        </li>
                        <li>
                            <a href="#admin-crowdsale">Crowdsale</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="tabs-wrap">
                <!-- tab ZanteCoin -->
                <div class="tab-body is-active" id="admin-zantecoin">

                    <div class="dashboard-group">
                        <p id="metamask_missing"></p>

                        <div class="row">
                            <div class="col-md-7 dashboard-group">
                                <h2 class="h4 headline-mb">Transfer ownership</h2>
                                <p><b>Current owner address: </b> <span id="current_zantecoin_owner"></span>
                                    <br> &nbsp;
                                </p>
                                <div class="row row-middle mt-20">
                                    <div class="col col-sm-auto">
                                        <div class="form-group text-regular"><label for="new_zantecoin_owner_address">Set new owner:</label></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input class="input-field" type="text" id="new_zantecoin_owner_address" placeholder="Fill new owner address">
                                        </div>
                                    </div>
                                    <div class="col col-sm-auto">
                                        <button class="form-group btn btn--medium btn--shadowed-light" type="submit" id="set_new_zantecoin_owner"> Submit</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 dashboard-group">
                                <h2 class="h4 headline-mb">Accept ownership</h2>
                                <p class="mb-25"><b>Proposed new owner address pending to accept ownership:</b> <span id="pending_zantecoin_owner_address"></span>
                                </p>
                                <button class="form-group btn btn--medium btn--shadowed-light" type="submit" id="accept_zantecoin_ownership"> Accept ownership
                                </button>
                            </div>
                        </div>

                        <!-- TODO: this part is demo. Need to connect user wallet addresses database -->
                        <h2 class="h4 headline-mb">Issue ICO coins</h2>
                        <div class="table-responsive-500 table--left">
                            <table id="ico-participants" class="table table-black">
                                <thead>
                                <tr>
                                    <th class="col-left sort">ICO status <span class="caret"></span></th>
                                    <th class="sort sort-asc">Total coins <span class="caret"></span></th>
                                    <th class="sort sort-desc">Available coins <span class="caret"></span></th>
                                    <th class="sort">ETH received <span class="caret"></span></th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($ico as $icoInfo)
                                    <tr>
                                        <td class="col-left">{{ $icoInfo['name'] }}</td>
                                        <td>{{ $icoInfo['limit'] }}</td>
                                        <td>{{ $icoInfo['balance'] }}</td>
                                        <td>{{ $icoInfo['eth'] }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row justify-content-end mb-10">
                        <div class="col-sm col-sm-auto">
                            <div class="checkbox">
                                <input type="checkbox" name="confirm" id="check11"><label for="check11">Success</label>
                            </div>
                        </div>
                        <div class="col-sm col-sm-auto">
                            <div class="checkbox">
                                <input type="checkbox" name="confirm" id="check12"><label for="check12">Failure</label>
                            </div>
                        </div>
                        <div class="col-sm col-sm-auto">
                            <div class="checkbox">
                                <input type="checkbox" name="confirm" id="check13"><label for="check13">In progress</label>
                            </div>
                        </div>
                        <div class="col-sm col-sm-auto">
                            <div class="checkbox">
                                <input type="checkbox" name="confirm" id="check14"><label for="check14">Pending</label>
                            </div>
                        </div>
                    </div>


                    <div class="dashboard-group">
                        <p><b>Click Issue Token button to send tokens to participant</b></p>
                        <div class="dashboard-top-panel-row dashboard-top-panel-row--sm tabs-head-wrap mb-10">
                            <ul class="tabs-head">
                                <li class="is-active">
                                    <a href="#pre-ico">Pre-ICO</a>
                                </li>
                                <li>
                                    <a href="#ico1">ICO I</a>
                                </li>
                                <li>
                                    <a href="#ico2">ICO II</a>
                                </li>
                                <li>
                                    <a href="#ico3">ICO III</a>
                                </li>
                                <li>
                                    <a href="#total">Total</a>
                                </li>
                            </ul>
                        </div>
                        <div class="tabs-wrap">

                            <!-- pre ico -->
                            <div class="tab-body is-active" id="pre-ico">
                                <div class="table-responsive-500">
                                    <table id="ico-participants" class="table table-black">
                                        <thead>
                                        <tr>
                                            <th class="sort sort-asc">Name <span class="caret"></span></th>
                                            <th class="sort">Proxy address <span class="caret"></span></th>
                                            <th class="sort">Amount <span class="caret"></span></th>
                                            <th class="sort">Issue tokens <span class="caret"></span></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($grant as $transaction)
                                            @continue($transaction['ico']['part_one'] == 0)

                                            <tr>
                                                <td>{{ $transaction['user'] }}</td>
                                                <td>{{ $transaction['address'] }}</td>
                                                <td>{{ $transaction['ico']['part_one'] }}</td>
                                                <td>---</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- ico 1 -->
                            <div class="tab-body" id="ico1">
                                <div class="table-responsive-500">
                                    <table id="ico-participants" class="table table-black">
                                        <thead>
                                        <tr>
                                            <th class="sort sort-asc">Name <span class="caret"></span></th>
                                            <th class="sort">Proxy address <span class="caret"></span></th>
                                            <th class="sort">Amount <span class="caret"></span></th>
                                            <th class="sort">Issue tokens <span class="caret"></span></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($grant as $transaction)
                                            @continue($transaction['ico']['part_two'] == 0)

                                            <tr>
                                                <td>{{ $transaction['user'] }}</td>
                                                <td>{{ $transaction['address'] }}</td>
                                                <td>{{ $transaction['ico']['part_two'] }}</td>
                                                <td>---</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- ico 2 -->
                            <div class="tab-body" id="ico2">
                                <div class="table-responsive-500">
                                    <table id="ico-participants" class="table table-black">
                                        <thead>
                                        <tr>
                                            <th class="sort sort-asc">Name <span class="caret"></span></th>
                                            <th class="sort">Proxy address <span class="caret"></span></th>
                                            <th class="sort">Amount <span class="caret"></span></th>
                                            <th class="sort">Issue tokens <span class="caret"></span></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($grant as $transaction)
                                            @continue($transaction['ico']['part_three'] == 0)

                                            <tr>
                                                <td>{{ $transaction['user'] }}</td>
                                                <td>{{ $transaction['address'] }}</td>
                                                <td>{{ $transaction['ico']['part_three'] }}</td>
                                                <td>---</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- ico 3 -->
                            <div class="tab-body" id="ico3">
                                <div class="table-responsive-500">
                                    <table id="ico-participants" class="table table-black">
                                        <thead>
                                        <tr>
                                            <th class="sort sort-asc">Name <span class="caret"></span></th>
                                            <th class="sort">Proxy address <span class="caret"></span></th>
                                            <th class="sort">Amount <span class="caret"></span></th>
                                            <th class="sort">Issue tokens <span class="caret"></span></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($grant as $transaction)
                                            @continue($transaction['ico']['part_four'] == 0)

                                            <tr>
                                                <td>{{ $transaction['user'] }}</td>
                                                <td>{{ $transaction['address'] }}</td>
                                                <td>{{ $transaction['ico']['part_four'] }}</td>
                                                <td>---</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Total -->
                            <div class="tab-body" id="total">
                                <div class="table-responsive-500">
                                    <table id="ico-participants" class="table table-black">
                                        <thead>
                                        <tr>
                                            <th class="sort sort-asc">Name <span class="caret"></span></th>
                                            <th class="sort">Proxy address <span class="caret"></span></th>
                                            <th class="sort">Amount <span class="caret"></span></th>
                                            <th class="sort">Issue tokens <span class="caret"></span></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($grant as $transaction)
                                            @continue($transaction['ico']['total'] == 0)

                                            <tr>
                                                <td>{{ $transaction['user'] }}</td>
                                                <td>{{ $transaction['address'] }}</td>
                                                <td>{{ $transaction['ico']['total'] }}</td>
                                                <td>
                                                    @if( $transaction['transaction'] != '')
                                                        {{ $transaction['transaction'] }}
                                                    @else
                                                        <button class="btn btn--medium btn--shadowed-light" type="button" id="issue_ico">
                                                            Issue Token
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="dashboard-group">
                        <h2 class="h4 headline-mb">Grant Marketing Coins. &nbsp; &nbsp; Total left: {{ $grant['marketing_balance'] }}</h2>
                        <p><b>Fill beneficiary address and amount in ZNX to grant marketing coins</b></p>
                        <p>Curently available: <span id="grant_marketing_available"></span> out of <span
                                    id="total_grant_marketing_supply"></span> ZNX</p>

                        <div class="row row-middle mt-20">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="text-regular mb-10"><label for="grant_marketing_address">Beneficiary addres</label></div>
                                    <input class="input-field" type="text" id="grant_marketing_address"
                                           placeholder="Set beneficiary address">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="text-regular mb-10"><label for="grant_marketing_amount">Number of ZNX to grant</label></div>
                                    <input class="input-field" type="text" id="grant_marketing_amount" placeholder="Set amount to grant">
                                </div>
                            </div>

                            <div class="col col-sm-auto">
                                <button class=" mt-10 btn btn--medium btn--shadowed-light" type="submit" id="grant_marketing_coins"> Grant
                                    Coins
                                </button>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h2 class="h4 headline-mb">Grant Company Coins. &nbsp; &nbsp; Total left: {{ $grant['company_balance'] }}</h2>
                        <p><b>Fill beneficiary address and amount in ZNX to Grant Company coins</b></p>
                        <p>Curently available: <span id="grant_company_available"></span>(<span id="total_grant_company_supply"></span>) ZNX
                        </p>
                        <div class="row row-middle mt-20">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="text-regular mb-10"><label for="grant_company_address">Beneficiary address</label></div>
                                    <input class="input-field" type="text" id="grant_company_address" placeholder="Set beneficiary address">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="text-regular mb-10"><label for="grant_company_amount">Number of ZNX to grant</label></div>
                                    <input class="input-field" type="text" id="grant_company_amount" placeholder="Set amount to grant">
                                </div>
                            </div>
                            <div class="col col-sm-auto">
                                <button class=" mt-10 btn btn--medium btn--shadowed-light" type="submit" id="grant_company_coins"> Grant
                                    Coins
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- END tab ZanteCoin -->

                <!-- tab Crowdsale -->
                <div class="tab-body" id="admin-crowdsale">
                    
                    <div class="row">
                            <div class="col-md-7 dashboard-group">
                                <h2 class="h4 headline-mb">Transfer ownership</h2>
                                <p><b>Current owner address: </b> <span id="current_crowdsale_owner"></span>
                                    <br> &nbsp;
                                </p>
                                <div class="row row-middle mt-20">
                                    <div class="col col-sm-auto">
                                        <div class="form-group text-regular"><label for="new_crowdsale_owner_address">Set new owner:</label></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input class="input-field" type="text" id="new_crowdsale_owner_address" placeholder="Fill new owner address">
                                        </div>
                                    </div>
                                    <div class="col col-sm-auto">
                                        <button class="form-group btn btn--medium btn--shadowed-light" type="submit" id="set_new_crowdsale_owner"> Submit</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 dashboard-group">
                                <h2 class="h4 headline-mb">Accept ownership</h2>
                                <p class="mb-25"><b>Proposed new owner address pending to accept ownership:</b> <span id="pending_crowdsale_owner_address"></span>
                                </p>
                                <button class="form-group btn btn--medium btn--shadowed-light" type="submit" id="accept_crowdsale_ownership"> Accept ownership
                                </button>
                            </div>
                        </div>

                    <div class="row">
                        <div class="col-md-6 dashboard-group">
                            <h2 class="h4 headline-mb">Set Wallet</h2>
                            <p><b>Please fill field to set wallet</b> (current wallet address : <span id="current_crowdsale_wallet"></span>) </p>
                            <div class="row row-middle mt-20">
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <div class="mb-10 text-regular"><label for="new_wallet_address">Set new Wallet</label></div>
                                        <input class="input-field" type="text" id="new_crowdsale_wallet_address"
                                               placeholder="Fill new wallet address">
                                    </div>
                                </div>
                                <div class="col col-sm-auto">
                                    <button class="mt-10 btn btn--medium btn--shadowed-light" type="submit" id="set_crowdsale_wallet"> Set Wallet
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 dashboard-group">
                            <h2 class="h4 headline-mb">Withdraw funds</h2>
                            <p><b>Select amount to withdraw funds</b> (curently available: <span id="crowdsale_address_balance"></span> Wei)</p>
                            <div class="row row-middle mt-20">
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <div class="mb-10 text-regular"><label for="crowdsale_withdrawAmount">Set amount to withdraw in Wei</label>
                                        </div>
                                        <input class="input-field" type="text" id="crowdsale_withdrawAmount" placeholder="Set amount to withdraw">
                                    </div>
                                </div>
                                <div class="col col-sm-auto">
                                    <button class="mt-10 btn btn--medium btn--shadowed-light" type="submit" id="withdraw_crowdsale_funds"> Withdraw
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- END tab Crowdsale -->
            </div>
        </div>
    </main>

@endsection

@section('popups')

    <!-- Grant ZNX Coins -->
    <div class="logon-modal mfp-hide" id="grant-coins-modal">
        <div class="logon-modal-container">
            <h3 class="h4">GRANTED!</h3>
            <div class="logon-modal-text">
                <p>ZNX Coins successfully granted.</p>
            </div>
        </div>
    </div>

@endsection


@section('scripts')
    <script src="/js/components/web3.min.js"></script>
    <script src="/js/components/zantecoin.js"></script>
    <script src="/js/components/crowdsale.js"></script>
    <script src="/js/admin_wallet.js" type="text/javascript"></script>
@endsection
