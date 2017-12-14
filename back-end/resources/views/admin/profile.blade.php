@extends('layouts.admin')

@section('main-menu')
    <li class="current-menu-item"><a href="users">Users</a></li>
    <li><a href="wallets">Wallets</a></li>
@endsection

@section('content')

    <main class="main main-dashboard">
        <div class="container">
            <input type="hidden" id="user-profile-id" value="{{ $profile['uid'] }}">

            <div class="dashboard-group">
                <h2 class="h4 headline-mb">Profile:</h2>

                <form id="user-profile">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="field-label" for="field2">Email:</label>
                                <input class="input-field" type="email" name="email" id="field2" value="{{ $profile['email'] }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="field-label" for="field1">Name:</label>
                                <input class="input-field" type="text" name="f-name" id="field1" value="{{ $profile['name'] }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="field-label">Role:</label>
                                <select name="role" class="input-field">
                                    @foreach($userRoles as $userRole)
                                        <option
                                                value="{{ $userRole['id'] }}"
                                                @if($userRole['id'] == $profile['role'])
                                                selected
                                                @endif
                                        >
                                            {{ $userRole['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn--shadowed-light btn--medium btn--160 mt-15">Save</button>
                </form>
            </div>

            <div class="dashboard-group">
                <h2 class="h4 headline-mb">Referrer:</h2>
                <form action="">
                    @if(!is_null($referrer))
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="field-label" for="field3">Email:</label>
                                    <input class="input-field" type="email" name="email" id="field3" value="{{ $referrer['email'] }}"
                                           readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="field-label" for="field4">Name:</label>
                                    <input class="input-field" type="text" name="f-name" id="field4" value="{{ $referrer['name'] }}"
                                           readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="field-label" for="field5">Role:</label>
                                    <input class="input-field" type="text" name="role" id="field5" value="{{ $referrer['role'] }}" readonly>
                                </div>
                            </div>
                        </div>
                    @endif
                </form>
            </div>

            <div class="dashboard-group">
                <h2 class="h4 headline-mb">Referals:</h2>
                @if(count($referrals) > 0)
                    <div class="table-responsive-500">
                        <table class="table-black table">
                            <thead>
                            <tr>
                                <th width="50%" class="col-left">Name</th>
                                <th width="50%" class="col-left">Status</th>
                            </tr>
                            </thead>
                            <tbody class="no-borders no-hover">
                            @foreach($referrals as $referral)
                                <tr>
                                    <td class="col-left">
                                        {{ $referral['name'] }}
                                    </td>
                                    <td class="col-left">
                                        {{ $referral['status'] }}
                                    </td>
                                </tr>

                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="dashboard-group">
                <h2 class="h4 headline-mb">Uploaded Documents:</h2>
                <div class="table-responsive-500">
                    <table class="table-black table">
                        <tbody class="no-borders no-hover">
                        <td class="col-left">
                            @if(count($idDocuments) > 0)
                                <form class="user-documents">
                                    <input type="hidden" name="document-type" value="{{ \App\Models\Document::DOCUMENT_TYPE_IDENTITY }}">

                                    @foreach($idDocuments as $document)
                                        <p><a href="{{ $document }}" target="_blank"> View </a></p>
                                    @endforeach

                                    @if(!$idDocumentsApproved)
                                        <button type="submit" class="btn btn--shadowed-light btn--medium btn--160">Approve</button>
                                    @endif
                                </form>
                            @endif
                        </td>

                        <td class="col-left">
                            @if(count($addressDocuments) > 0)
                                <form class="user-documents">
                                    <input type="hidden" name="document-type" value="{{ \App\Models\Document::DOCUMENT_TYPE_ADDRESS }}">

                                    @foreach($addressDocuments as $document)
                                        <p><a href="{{ $document }}" target="_blank"> View </a></p>
                                    @endforeach

                                    @if(!$addressDocumentsApproved)
                                        <button type="submit" class="btn btn--shadowed-light btn--medium btn--160">Approve</button>
                                    @endif
                                </form>
                            @endif
                        </td>
                        </tbody>
                    </table>
                </div>
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