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
            <div class="card-steps-wrap">
                <h2 class="h4">Pre-order ZANTEPAY debit card</h2>
                <div class="card-steps">
                    <ul>
                        <li class="current-step">
                            <span>Select a design</span>
                            <div class="right-triangle"></div>
                        </li>
                        <li>
                            <div class="left-triangle"></div>
                            <span>Upload documents</span>
                            <div class="right-triangle"></div>
                        </li>
                        <li>
                            <div class="left-triangle"></div>
                            <span>Verify address</span>
                            <div class="right-triangle"></div>
                        </li>
                    </ul>
                </div>
            </div>

            <form id="dc_design">
                <div class="row">
                    <div class="col-md-6 col-xl-5 offset-xl-1">
                        <div class="card-wrap">
                            <div class="radio-button">
                                <input type="radio" name="card-type" value="{{ \App\Models\DB\DebitCard::DESIGN_WHITE }}" id="radio1" checked>
                                <label for="radio1">
                                    <img src="/images/wh-card.jpg" srcset="/images/wh-card@2x.jpg 2x" alt="White Visa Debit Card">
                                </label>
                            </div>
                        </div>
                        <div class="card-text-wrap">
                            <ul class="triangle-list">
                                <li>Pre-order bonus: 60 ZNX</li>
                                <li>20% cashback in ZNX</li>
                                <li>FREE DEBIT CARD</li>
                                <li>Exchange fee: BTC to EUR 1%, Altcoin to EUR 1,5%</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-5">
                        <div class="card-wrap">
                            <div class="radio-button">
                                <input type="radio" name="card-type" value="{{ \App\Models\DB\DebitCard::DESIGN_RED }}" id="radio2">
                                <label for="radio2">
                                    <img src="/images/red-card.jpg" srcset="/images/red-card@2x.jpg 2x" alt="Red Visa Debit Card">
                                </label>
                            </div>
                        </div>
                        <div class="card-text-wrap">
                            <table class="table-gray">
                                <thead>
                                <tr>
                                    <th class="col-left" colspan="2">Fees</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="col-left" width="50%">Monthly fee</td>
                                    <td class="col-right" width="50%">1€ (first year FREE)</td>
                                </tr>
                                <tr>
                                    <td class="col-left">Annual fee</td>
                                    <td class="col-right">None</td>
                                </tr>
                                <tr>
                                    <td class="col-left">Delivery fee</td>
                                    <td class="col-right">None</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card-submit">
                    <div class="card-submit-wrap">
                        <button class="btn btn--shadowed-light btn--medium btn--160" type="submit">
                            Next
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>

@endsection