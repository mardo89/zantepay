@extends('layouts.user')

@section('main-menu')
    <li><a href="profile">Profile</a></li>
    <li class="current-menu-item"><a href="profile-settings">Profile Settings</a></li>
    <li><a href="invite-friend">Refer a Friend</a></li>
    <li><a href="wallet">Wallet</a></li>
    <li><a href="debit-card">ZANTEPAY Debit Card</a></li>
@endsection

@section('content')

    <main class="main">
        <div class="container">
            <div class="dashboard-container">

                <div class="dashboard-left-wrap">
                    <div class="dashboard-col-wrap">
                        <h2 class="h4 headline-mb headline-subline">
                            Account verification:
                            @if(!$user->accountVerified)
                                <span>Account requires verification</span>
                            @else
                                <span>Account verified</span>
                            @endif

                        </h2>
                        <div class="row">
                            <div class="col-md-6">
                                @if($verification->id_documents_status == \App\Models\DB\Verification::DOCUMENTS_NOT_UPLOADED)
                                    <div>Your passport / ID / driver’s license:</div>

                                    <form id="upload-identity-documents">
                                        <div class="drag-drop-area">
                                            <div class="drag-drop-container">
                                                <div class="drag-drop-ico"></div>
                                                <div class="drag-drop-text">Click to open explorer or <br> drag and drop your
                                                    verification file here
                                                </div>
                                                <label>
                                                    <span class="btn btn--shadowed-light btn--medium">Choose file</span>
                                                    <input id="document-files" type="file" name="files[]" multiple="multiple"
                                                           title='Click to add Files'>
                                                </label>
                                                <div class="drag-drop-text">Accepted file formats: png, jpeg, pdf. <br> The document
                                                    should not be bigger than 4 Mb.
                                                </div>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn--shadowed-light btn--medium">Upload Documents</button>
                                    </form>

                                @else
                                    <div class="headline-subline headline-mb">
                                        ID proof documents:
                                        <span>{{ count($idDocuments) }} file uploaded | {{ $verification->idStatus }}</span>
                                    </div>
                                    <ul class="files-list">
                                        @foreach($idDocuments as $document)
                                            <li id="{{ $document['did'] }}">
                                                <i class="file-ico"></i>
                                                <div class="file-name">{{ $document['name'] }}</div>
                                                <a href="" class="cross-ico remove-document">×</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>


                            <div class="col-md-6">
                                @if($verification->address_documents_status == \App\Models\DB\Verification::DOCUMENTS_NOT_UPLOADED)
                                    <div>Your address proof documents:</div>

                                    <form id="upload-address-documents">
                                        <div class="drag-drop-area">
                                            <div class="drag-drop-container">
                                                <div class="drag-drop-ico"></div>
                                                <div class="drag-drop-text">Click to open explorer or <br> drag and drop your
                                                    verification file here
                                                </div>
                                                <label>
                                                    <span class="btn btn--shadowed-light btn--medium">Choose file</span>
                                                    <input id="address-files" type="file" name="files[]" multiple="multiple"
                                                           title='Click to add Files'>
                                                </label>
                                                <div class="drag-drop-text">Accepted file formats: png, jpeg, pdf. <br> The document
                                                    should not be bigger than 4 Mb.
                                                </div>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn--shadowed-light btn--medium">Upload Documents</button>
                                    </form>

                                @else
                                    <div class="headline-subline headline-mb">
                                        Address proof document:
                                        <span>{{ count($addressDocuments) }} file uploaded | {{ $verification->addressStatus }}</span>
                                    </div>
                                    <ul class="files-list">
                                        @foreach($addressDocuments as $document)
                                            <li id="{{ $document['did'] }}">
                                                <i class="file-ico"></i>
                                                <div class="file-name">{{ $document['name'] }}</div>
                                                <a href="" class="cross-ico remove-document">×</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr>

                    {{--<div class="dashboard-col-wrap dashboard-group-md">--}}
                        {{--<h2 class="h4 mb-20">Bitcoin wallet:</h2>--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-lg-11 wallet-address-group">--}}
                                {{--<form action="">--}}
                                    {{--<input type="hidden" name="wallet-currency" value="{{ \App\Models\Wallet\Currency::CURRENCY_TYPE_BTC }}"/>--}}

                                    {{--<div class="row mb-15">--}}
                                        {{--<div class="col-xl-8 col-lg-7 col-md-8">--}}
                                            {{--<label class="field-label" for="field4">BTC wallet address:</label>--}}
                                            {{--<input class="input-field" type="text" name="wallet-address" id="field4"--}}
                                                   {{--value="{{ $wallet->btc_wallet }}">--}}
                                        {{--</div>--}}

                                        {{--<div class="col-xl-4 col-lg-5 col-md-4">--}}
                                            {{--<button type="button"--}}
                                                    {{--class="btn btn--shadowed-light btn--medium mt-35 mt-sm-15  update-wallet">--}}
                                                {{--Change Address--}}
                                            {{--</button>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}

                                    {{--<div class="checkbox">--}}
                                        {{--<input type="checkbox" id="check1" class="owner-confirm"><label for="check1" class="text-sm">Hereby--}}
                                            {{--I confirm that I am the owner of this account</label>--}}
                                    {{--</div>--}}
                                {{--</form>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    <div class="dashboard-col-wrap">
                        <h2 class="h4 mb-20">Ethereum wallet:</h2>
                        <div class="row">
                            <div class="col-lg-11 wallet-address-group">
                                <form action="">
                                    <input type="hidden" name="wallet-currency" value="{{ \App\Models\Wallet\Currency::CURRENCY_TYPE_ETH }}"/>

                                    <div class="row mb-15">
                                        <div class="col-xl-8 col-lg-7 col-md-8">
                                            <label class="field-label" for="field4">ETH wallet address:</label>
                                            <input class="input-field" type="text" name="wallet-address" id="field5"
                                                   value="{{ $wallet->eth_wallet }}">
                                        </div>

                                        <div class="col-xl-4 col-lg-5 col-md-4">
                                            <button type="submit"
                                                    class="btn btn--shadowed-light btn--medium mt-35 mt-sm-15 update-wallet">
                                                Change Address
                                            </button>
                                        </div>
                                    </div>

                                    <div class="checkbox">
                                        <input type="checkbox" id="check2" class="owner-confirm"><label for="check2" class="text-sm">Hereby
                                            I confirm that I am the owner of this account</label>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dashboard-right-wrap">
                    <form id="change-password">
                        <h2 class="h4 headline-mb">Change password:</h2>
                        <div class="form-group">
                            <label class="field-label" for="field1">Current password:</label>
                            <input class="input-field" type="password" name="current-password" id="field1">
                        </div>
                        <div class="form-group">
                            <label class="field-label" for="field2">New password:</label>
                            <input class="input-field" type="password" name="password" id="field2">
                        </div>
                        <div class="form-group dashboard-group-md">
                            <label class="field-label" for="field3">Current password:</label>
                            <input class="input-field" type="password" name="confirm-password" id="field3">
                        </div>

                        <button type="submit" class="btn btn--shadowed-light btn--medium">Change Password</button>
                    </form>
                </div>

            </div>
        </div>
    </main>

@endsection

@section('popups')

    <!-- Remove document confirmation -->
    <div class="logon-modal mfp-hide" id="remove-document-modal">
        <div class="logon-modal-container">
            <h3 class="h4">DELETED!</h3>
            <div class="logon-modal-text">
                <p>File successfully deleted.</p>
            </div>
        </div>
    </div>

    <!-- Upload documents confirmation -->
    <div class="logon-modal mfp-hide" id="upload-documents-modal">
        <div class="logon-modal-container">
            <h3 class="h4">Uploaded!</h3>
            <div class="logon-modal-text">
                <p>Documents successfully uploaded.</p>
            </div>
        </div>
    </div>

    <!-- Update Wallet Address confirmation -->
    <div class="logon-modal mfp-hide" id="wallet-address-modal">
        <div class="logon-modal-container">
            <h3 class="h4">Changed!</h3>
            <div class="logon-modal-text">
                <p>Your Wallet address was successfully changed.</p>
            </div>
        </div>
    </div>

    <!-- Change user password confirmation -->
    <div class="logon-modal mfp-hide" id="change-password-modal">
        <div class="logon-modal-container">
            <h3 class="h4">Changed!</h3>
            <div class="logon-modal-text">
                <p>Password successfully changed.</p>
            </div>
        </div>
    </div>

@endsection