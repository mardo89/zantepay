@extends('layouts.admin')

@section('main-menu')
    <li class="current-menu-item"><a href="users-list">Users</a></li>
@endsection

@section('content')

    <main class="main main-dashboard">
        <div class="container">
            <input type="hidden" id="user-profile-id" value="{{ $profile['id'] }}">

            <form id="user-profile">
                <div class="dashboard-group-sm">
                    <h2 class="h4 headline-mb">Profile:</h2>
                    <div class="row">
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="field-label" for="field1">Email:</label>
                                <input class="input-field" type="text" name="email" value="{{ $profile['email'] }}" readonly>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="field-label" for="field2">Name:</label>
                                <input class="input-field" type="text" name="name" value="{{ $profile['name'] }}" readonly>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="field-label" for="field2">Role:</label>
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

                </div>

                <button type="submit" class="btn btn--shadowed-light btn--medium btn--160">Save</button>
            </form>

            <div class="dashboard-group-sm">
                <h2 class="h4 headline-mb">Referrer:</h2>
                <div class="row">
                    @if(is_null($referrer))
                        <div class="col-lg-4 col-sm-6">
                            This user has no referrer
                        </div>
                    @else
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="field-label" for="field1">Email:</label>
                                <input class="input-field" type="text" name="email" value="{{ $referrer['email'] }}" readonly>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="field-label" for="field2">Name:</label>
                                <input class="input-field" type="text" name="name" value="{{ $referrer['name'] }}" readonly>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="field-label" for="field2">Role:</label>
                                <input class="input-field" type="text" name="name" value="{{ $referrer['role'] }}" readonly>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="dashboard-group-sm">
                <h2 class="h4 headline-mb">Referals:</h2>
                <div class="table-responsive-500">
                    <table id="invites-list" class="inv-table table-black">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($referrals as $referral)
                            <tr>
                                <td> {{ $referral['name'] }} </td>
                                <td> {{ $referral['status'] }} </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="dashboard-group-sm">
                <h2 class="h4 headline-mb">Debit card:</h2>
                <div class="row">
                    <div class="col-lg-6 col-sm-6">
                        @if ($debitCard === \App\Models\DebitCard::DESIGN_WHITE)
                            <img src="/images/wh-card.jpg" srcset="/images/wh-card@2x.jpg 2x" alt="White Visa Debit Card">
                        @elseif($debitCard === \App\Models\DebitCard::DESIGN_RED)
                            <img src="/images/red-card.jpg" srcset="/images/red-card@2x.jpg 2x" alt="Red Visa Debit Card">
                        @else
                            No Debit Card selected
                        @endif
                    </div>
                    <div class="col-lg-6 col-sm-6">
                        Wallet
                    </div>
                </div>

            </div>

            <div class="dashboard-group-sm">
                <h2 class="h4 headline-mb">Uploaded documents:</h2>
                <div class="row">

                    <div class="col-lg-6 col-sm-6">
                        <form class="user-documents">
                            <input type="hidden" name="document-type" value="{{ \App\Models\Document::DOCUMENT_TYPE_IDENTITY }}">

                            @foreach($idDocuments as $document)
                                <p><a href="{{ $document }}" target="_blank"> View </a></p>
                            @endforeach

                            <button type="submit" class="btn btn--shadowed-light btn--medium btn--160">Approve</button>
                        </form>
                    </div>

                    <div class="col-lg-6 col-sm-6">
                        <form class="user-documents">
                            <input type="hidden" name="document-type" value="{{ \App\Models\Document::DOCUMENT_TYPE_ADDRESS }}">

                            @foreach($addressDocuments as $document)
                                <p><a href="{{ $document }}" target="_blank"> View </a></p>
                            @endforeach

                            <button type="submit" class="btn btn--shadowed-light btn--medium btn--160">Approve</button>
                        </form>
                    </div>

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

    <div class="logon-modal mfp-hide" id="document-modal">
        <div class="logon-modal-container">
            <h3 class="h4">SAVED!</h3>
            <div class="logon-modal-text">
                <p>Documents successfully confirmed.</p>
            </div>
        </div>
    </div>

@endsection