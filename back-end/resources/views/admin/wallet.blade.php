@extends('layouts.admin')

@section('main-menu')
    <li><a href="users">Users</a></li>
    <li class="current-menu-item"><a href="wallets">Wallets</a></li>
@endsection

@section('content')

    <main class="main main-dashboard">
        <div class="container">
            <input type="hidden" id="user-profile-id" value="{{ $profile['uid'] }}">

            <div class="dashboard-group">
                <h2 class="h4 headline-mb">User:</h2>

                <form>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="field-label" for="field1">Email:</label>
                                <input class="input-field" type="email" name="email" id="field1" value="{{ $profile['email'] }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="field-label" for="field2">Name:</label>
                                <input class="input-field" type="text" name="f-name" id="field2" value="{{ $profile['name'] }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="field-label" for="field3">Status:</label>
                                <input class="input-field" type="text" name="f-name" id="field3" value="{{ $profile['status'] }}" readonly>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div>
                <div class="table-responsive-500">
                    <table class="table-black table">
                        <tbody class="no-borders no-hover">
                        <tr>
                            <td width="50%" class="col-left">
                                <h3 class="h4">Debit Card:</h3>
                            </td>
                            <td width="50%" class="col-left">
                                <h3 class="h4">Wallet:</h3>
                            </td>
                        </tr>

                        <tr>
                            <td class="col-left">
                                @if($debitCard === \App\Models\DebitCard::DESIGN_WHITE)
                                    <img src="/images/wh-card.jpg" srcset="/images/wh-card@2x.jpg 2x" alt="ZANTEPAY Card">
                                    @elseif($debitCard === \App\Models\DebitCard::DESIGN_RED)
                                    <img src="/images/red-card.jpg" srcset="/images/red-card@2x.jpg 2x" alt="ZANTEPAY Card">
                                @else
                                    Debit Card not selected
                                @endif

                            </td>
                            <td class="col-left">
                                <div class="row row-middle mb-15">
                                    <div class="col-lg-8 col-md-7">
                                        <div class="field-group">
                                            <label for="field6" class="field-label">BTC:</label>
                                            <input class="input-field" type="text" name="btc" id="field6">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-5">
                                        <button type="button" class="field-btn btn btn--shadowed-light btn--medium btn--full-w mt-25">Copy
                                            Link
                                        </button>
                                    </div>
                                </div>
                                <div class="row row-middle mb-15">
                                    <div class="col-lg-8 col-md-7">
                                        <div class="field-group">
                                            <label for="field7" class="field-label">ETH:</label>
                                            <input class="input-field" type="text" name="ETH" id="field7">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-5">
                                        <button type="button" class="field-btn btn btn--shadowed-light btn--medium btn--full-w mt-25">Copy
                                            Link
                                        </button>
                                    </div>
                                </div>
                                <div class="row row-middle mb-15">
                                    <div class="col-lg-8 col-md-7">
                                        <div class="field-group">
                                            <label for="field8" class="field-label">ZNX:</label>
                                            <input class="input-field" type="text" name="ZNX" id="field8">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-5">
                                        <button type="button" class="field-btn btn btn--shadowed-light btn--medium btn--full-w mt-25">Copy
                                            Link
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="dashboard-group">
                <h2 class="h4 headline-mb">ZNX:</h2>

                <form id="user-wallet">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <input class="input-field" type="text" name="ztx-amount" id="field4" placeholder="ZNX amount">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn--shadowed-light btn--medium btn--160">Add to wallet</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </main>

@endsection

@section('popups')

    <!-- Save profile confirmation -->
    <div class="logon-modal mfp-hide" id="profile-modal">
        <div class="logon-modal-container">
            <h3 class="h4">SAVED!</h3>
            <div class="logon-modal-text">
                <p>User profile successfully saved.</p>
            </div>
        </div>
    </div>

    <!-- Approve documents -->
    <div class="logon-modal mfp-hide" id="document-modal">
        <div class="logon-modal-container">
            <h3 class="h4">Approved!</h3>
            <div class="logon-modal-text">
                <p>Documents approved.</p>
            </div>
        </div>
    </div>

    <!-- Error saving profile confirmation -->
    <div class="logon-modal mfp-hide" id="error-modal">
        <div class="logon-modal-container">
            <h3 class="h4 error-message">ERROR!</h3>
            <div class="logon-modal-text">
                <p id="error-message"></p>
            </div>
        </div>
    </div>

@endsection