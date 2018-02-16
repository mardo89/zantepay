@extends('layouts.admin')

@section('main-menu')
    <li class="current-menu-item"><a href="users">Users</a></li>
    <li><a href="wallet">Wallet</a></li>
@endsection

@section('content')

    <main class="main main-dashboard">
        <div class="container">
            <form id="user-profile">

                <input type="hidden" id="user-profile-id" value="{{ $user->uid }}">

                <div class="dashboard-top-panel">
                    <a href="/admin/users" class="back-arrow"><i class="fa fa-angle-double-left" aria-hidden="true"></i> Back</a>
                    <div class="dashboard-top-panel-row">
                        <ul class="tabs-head">
                            <li class="is-active">
                                <a href="#profile">Profile</a>
                            </li>
                            <li>
                                <a href="#documents">Documents</a>
                            </li>
                            <li>
                                <a href="#account-wallets">Account & Wallets</a>
                            </li>
                        </ul>
                        @if(count($userRoles) > 0)
                            <div class="form-group">
                                <label class="field-label">Role:</label>
                                <select name="user-role" class="input-field">
                                    @foreach($userRoles as $userRole)
                                        <option
                                                value="{{ $userRole['id'] }}"
                                                @if($userRole['id'] == $user->role)
                                                selected
                                                @endif
                                        >
                                            {{ $userRole['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="tabs-wrap">
                    <!-- tab Profile -->
                    <div class="tab-body is-active" id="profile">
                        <div class="dashboard-group-sm">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="field-label" for="field3">First Name:</label>
                                        <input class="input-field" type="text" name="f-name" id="field3" value="{{ $user->first_name }}"
                                               readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="field-label" for="field4">Last Name:</label>
                                        <input class="input-field" type="text" name="l-name" id="field4" value="{{ $user->last_name }}"
                                               readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="field-label" for="field5">Email:</label>
                                        <input class="input-field" type="email" name="email" id="field5" value="{{ $user->email }}"
                                               readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="field-label" for="field5">Phone Number:</label>
                                        <input class="input-field" type="text" name="phone" id="field5" value="{{ $user->phone_number }}"
                                               readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="dashboard-group-sm">
                            <h2 class="h4 headline-mb">Address:</h2>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="field-label" for="field6">Country:</label>
                                        <input class="input-field" type="text" name="city" id="field16" value="{{ $profile->countryName }}"
                                               readonly>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="field-label" for="field6">State / Country:</label>
                                        <input class="input-field" type="text" name="city" id="field17" value="{{ $profile->stateName }}"
                                               readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="field-label" for="field6">City:</label>
                                        <input class="input-field" type="text" name="city" id="field6" value="{{ $profile->city }}"
                                               readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="field-label" for="field7">Address:</label>
                                        <input class="input-field" type="text" name="address" id="field7" value="{{ $profile->address }}"
                                               readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="field-label" for="field8">Postcode:</label>
                                        <input class="input-field" type="text" name="postcode" id="field8" value="{{ $profile->postcode }}"
                                               readonly>
                                    </div>
                                </div>
                            </div>

                            <input id="remove-user" class="mt-20 btn btn--medium btn--shadowed-light" type="button" value="Delete User">
                        </div>
                    </div>
                    <!-- END tab Profile -->

                    <!-- tab Documents -->
                    <div class="tab-body" id="documents">
                        <div class="dashboard-group">
                            <h2 class="h4 headline-mb primary-color">Identification:</h2>
                            <div class="row">
                                <div class="col-lg-3 col-sm-6">
                                    <div class="form-group">
                                        <label class="field-label" for="field9">Passport/ID/Driver's license:</label>
                                        <input class="input-field" type="text" name="government" id="field9"
                                               value="{{ $profile->passport_id }}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <div class="form-group">
                                        <label class="field-label" for="field10">Passport / ID expiry date:</label>
                                        <div class="date-picker-wrap">
                                            <input class="input-field date-picker-inp" type="text" name="expiry" id="field10"
                                                   data-toggle="datepicker" value="{{ $profile->passportExpDate }}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <div class="form-group">
                                        <label class="field-label" for="field11">Date of birth:</label>
                                        <div class="date-picker-wrap">
                                            <input class="input-field date-picker-inp" type="text" name="birth" id="field11"
                                                   data-toggle="datepicker" value="{{ $profile->birthDate }}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <div class="form-group">
                                        <label class="field-label" for="field12">Country of birth:</label>
                                        <input class="input-field" type="text" name="country-birth" id="field12"
                                               value="{{ $profile->birth_country }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="dashboard-group-lg">
                            <h2 class="h4 headline-mb">Passport/ID/Driver's license:</h2>
                            <div class="row">
                                <div class="col-md-5">
                                    @foreach($idDocuments as $document)
                                        @if($document['type'] == 'application/pdf')
                                            <object width="100%" height="300px" type="application/pdf"
                                                    data="{{ $document['src'] }}"></object>

                                            <a href="{{ $document['src'] }}" target="_blank" class="mt-40 mb-20 btn btn--small btn--shadowed-light">
                                                View
                                            </a>
                                        @else
                                            <img width="100%" src="{{ $document['src'] }}"/>

                                            <a href="{{ $document['src'] }}" target="_blank" class="mt-40 mb-20 btn btn--small btn--shadowed-light">
                                                View
                                            </a>
                                        @endif
                                    @endforeach
                                </div>

                                @if($verification->id_documents_status == \App\Models\DB\Verification::DOCUMENTS_UPLOADED)
                                    <div class="col-md-3 col-sm-4 col-5 text-center mb-20 document-actions">
                                        <input type="hidden" name="document-type"
                                               value="{{ \App\Models\DB\Document::DOCUMENT_TYPE_IDENTITY }}">

                                        <button type="button"
                                                class="mt-40 mb-20 btn btn--medium btn--shadowed-light btn--full-w decline-documents">
                                            Decline
                                        </button>

                                        <button type="button"
                                                class="mb-20 btn btn--medium btn--shadowed-light btn--full-w approve-documents">
                                            Approve
                                        </button>
                                    </div>

                                    <div class="col-md-4 document-reason">
                                        <div class="form-group">
                                            <label class="field-label" for="field12">Reason:</label>
                                            <input class="input-field" type="text" name="decline-reason" id="field12"
                                                   value="{{ $verification->id_decline_reason }}">
                                        </div>
                                    </div>
                                @else
                                    <div class="col-md-3 col-sm-4 col-5 mb-20 document-status">
                                        {{ $verification->idStatusName }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="dashboard-group">
                            <h2 class="h4 headline-mb">Proof of address:</h2>
                            <div class="row">
                                <div class="col-md-5">
                                    @foreach($addressDocuments as $document)
                                        @if($document['type'] == 'application/pdf')
                                            <object width="100%" height="300px" type="application/pdf"
                                                    data="{{ $document['src'] }}"></object>

                                            <a href="{{ $document['src'] }}" target="_blank" class="mt-40 mb-20 btn btn--small btn--shadowed-light">
                                                View
                                            </a>
                                        @else
                                            <img width="500px" src="{{ $document['src'] }}"/>

                                            <a href="{{ $document['src'] }}" target="_blank" class="mt-40 mb-20 btn btn--small btn--shadowed-light">
                                                View
                                            </a>
                                        @endif
                                    @endforeach
                                </div>

                                @if($verification->address_documents_status == \App\Models\DB\Verification::DOCUMENTS_UPLOADED)
                                    <div class="col-md-3 col-sm-4 col-5 text-center mb-20 document-actions">
                                        <input type="hidden" name="document-type"
                                               value="{{ \App\Models\DB\Document::DOCUMENT_TYPE_ADDRESS }}">

                                        <button type="button"
                                                class="mt-40 mb-20 btn btn--medium btn--shadowed-light btn--full-w decline-documents">
                                            Decline
                                        </button>

                                        <button type="button"
                                                class="mb-20 btn btn--medium btn--shadowed-light btn--full-w approve-documents">
                                            Approve
                                        </button>
                                    </div>

                                    <div class="col-md-4 document-reason">
                                        <div class="form-group">
                                            <label class="field-label" for="field13">Reason:</label>
                                            <input class="input-field" type="text" name="decline-reason" id="field13"
                                                   value="{{ $verification->address_decline_reason }}">
                                        </div>
                                    </div>
                                @else
                                    {{ $verification->addressStatusName }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- END tab Documents -->

                    <!-- tab Account & Wallets -->
                    <div class="tab-body" id="account-wallets">
                        <div class="dashboard-row dashboard-group-lg">
                            <div class="dashboard-left">
                                <div class="dashboard-group">
                                    <div class="table-responsive-500">
                                        <table class="table-transparent">
                                            <thead>
                                            <tr>
                                                <th width="25%">Total ZNX</th>
                                                <th width="25%">Commission (BTC)</th>
                                                <th width="25%">Commission (ETH)</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td id="total-znx-amount">{{ $wallet->znx_amount }}</td>
                                                <td>0</td>
                                                <td>0</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="dashboard-group-sm">
                                                <label for="field24" class="field-label">Add ZNX:</label>
                                                <div class="row row-middle">
                                                    <div class="col-lg-7 col-md-7">
                                                        <div class="field-group">
                                                            <input class="input-field" type="text" name="znx-amount" id="field24"
                                                                   placeholder="ZNX amount">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-5 col-md-5">
                                                        <button id="add-znx" type="button"
                                                                class="field-btn btn btn--shadowed-light btn--medium btn--full-w">
                                                            Add ZNX
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="dashboard-right">
                                <div class="dashboard-group">
                                    <div class="table-responsive-500">
                                        <table class="table-transparent">
                                            <thead>
                                            <tr>
                                                <th width="33.333%">ZNX bonus - Referral signup</th>
                                                <th width="33.333%">Referral transactions</th>
                                                <th width="33.333%">Debit card pre-order</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>{{ $wallet->rs_bonus }}</td>
                                                <td>{{ $wallet->rt_bonus }}</td>
                                                <td>{{ $wallet->dc_bonus }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="field-label" for="field28">Referrer:</label>
                                    <input class="input-field" type="text" name="" id="field28" readonly value="{{ $referrer }}" readonly>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6">
                                @if($debitCard === \App\Models\DB\DebitCard::DESIGN_WHITE)
                                    <img src="/images/wh-card.jpg" srcset="/images/wh-card@2x.jpg 2x" alt="ZANTEPAY Card">
                                @elseif($debitCard === \App\Models\DB\DebitCard::DESIGN_RED)
                                    <img src="/images/red-card.jpg" srcset="/images/red-card@2x.jpg 2x" alt="ZANTEPAY Card">
                                @else
                                    Debit Card not selected
                                @endif
                            </div>

                            <div class="col-md-6">
                                {{--<div class="dashboard-group-sm wallet-address-group">--}}
                                {{--<label for="field24" class="field-label">Bitcoin Wallet:</label>--}}
                                {{--<div class="row row-middle">--}}
                                {{--<input type="hidden" name="wallet-currency" value="{{ \App\Models\Wallet\Currency::CURRENCY_TYPE_BTC }}">--}}

                                {{--<div class="col-lg-7 col-md-7">--}}
                                {{--<div class="field-group">--}}
                                {{--<input class="input-field" type="text" name="wallet-address" id="field24"--}}
                                {{--value="{{ $wallet->btc_wallet }}">--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="col-lg-5 col-md-5">--}}
                                {{--<button type="button"--}}
                                {{--class="field-btn btn btn--shadowed-light btn--medium btn--full-w update-wallet">--}}
                                {{--Change Address--}}
                                {{--</button>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                <div class="dashboard-group-sm wallet-address-group">
                                    <label for="field23" class="field-label">Ethereum Wallet:</label>
                                    <div class="row row-middle">
                                        <input type="hidden" name="wallet-currency"
                                               value="{{ \App\Models\Wallet\Currency::CURRENCY_TYPE_ETH }}">

                                        <div class="col-lg-7 col-md-7">
                                            <div class="field-group">
                                                <input class="input-field" type="text" name="wallet-address" id="field23"
                                                       value="{{ $profile->eth_wallet }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-5 col-md-5">
                                            <button type="button"
                                                    class="field-btn btn btn--shadowed-light btn--medium btn--full-w update-wallet">
                                                Change Address
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{--<div class="dashboard-group-sm wallet-address-group">--}}
                                {{--<label for="field25" class="field-label">Zantecoin Wallet:</label>--}}
                                {{--<div class="row row-middle">--}}
                                {{--<input type="hidden" name="wallet-currency" value="{{ \App\Models\Wallet\Currency::CURRENCY_TYPE_ZNX }}">--}}

                                {{--<div class="col-lg-7 col-md-7">--}}
                                {{--<div class="field-group">--}}
                                {{--<input class="input-field" type="text" name="wallet-address" id="field25"--}}
                                {{--value="{{ $wallet->znx_wallet }}">--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="col-lg-5 col-md-5">--}}
                                {{--<button type="button"--}}
                                {{--class="field-btn btn btn--shadowed-light btn--medium btn--full-w update-wallet">--}}
                                {{--Change Address--}}
                                {{--</button>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                            </div>
                        </div>

                    </div>
                    <!-- END tab Account & Wallets -->
                </div>
            </form>
        </div>
    </main>

@endsection

@section('popups')

    <!-- Save profile confirmation -->
    <div class="logon-modal mfp-hide" id="save-profile-modal">
        <div class="logon-modal-container">
            <h3 class="h4">SAVED!</h3>
            <div class="logon-modal-text">
                <p>User profile successfully saved.</p>
            </div>
        </div>
    </div>

    <!-- Remove profile confirmation -->
    <div class="logon-modal mfp-hide" id="remove-profile-modal">
        <div class="logon-modal-container">
            <h3 class="h4">DELETED!</h3>
            <div class="logon-modal-text">
                <p>User profile successfully deleted.</p>
            </div>
        </div>
    </div>

    <!-- Approve documents -->
    <div class="logon-modal mfp-hide" id="approve-documents-modal">
        <div class="logon-modal-container">
            <h3 class="h4">Approved!</h3>
            <div class="logon-modal-text">
                <p>Documents approved.</p>
            </div>
        </div>
    </div>

    <!-- Decline documents -->
    <div class="logon-modal mfp-hide" id="decline-documents-modal">
        <div class="logon-modal-container">
            <h3 class="h4">Declined!</h3>
            <div class="logon-modal-text">
                <p>Documents declined.</p>
            </div>
        </div>
    </div>

    <!-- Add ZNX amount -->
    <div class="logon-modal mfp-hide" id="add-znx-modal">
        <div class="logon-modal-container">
            <h3 class="h4">Added!</h3>
            <div class="logon-modal-text">
                <p>You ZNX Wallet was successfully updated.</p>
            </div>
        </div>
    </div>

    <!-- Update Wallet Address-->
    <div class="logon-modal mfp-hide" id="wallet-address-modal">
        <div class="logon-modal-container">
            <h3 class="h4">Changed!</h3>
            <div class="logon-modal-text">
                <p>Your Wallet address was successfully changed.</p>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="/js/admin_profile.js" type="text/javascript"></script>
@endsection