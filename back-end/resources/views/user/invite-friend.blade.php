@extends('layouts.user')

@section('main-menu')
    <li><a href="profile">Profile</a></li>
    <li><a href="profile-settings">Profile Settings</a></li>
    <li class="current-menu-item"><a href="invite-friend">Refer a Friend</a></li>
    <li><a href="wallet">Wallet</a></li>
    <li><a href="debit-card">ZANTEPAY Debit Card</a></li>
@endsection

@section('content')

    <main class="main main-dashboard">
        <div class="container">
            <div class="row dashboard-group">
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-lg-11">
                            <h2 class="h4 headline-mb">Invite friends and earn 20% commission!</h2>
                            <p>Each holder of ZanteCoin (ZNX) tokens will be entitled to a referral commission, paid weekly; this will be
                                constituted of 20% net transaction revenue.</p>

                            <div class="row row-bottom dashboard-group-md">
                                <div class="col-xl-8 col-md-7">
                                    <div class="field-group">
                                        <label class="field-label" for="friend-email">Invite via email:</label>
                                        <input class="input-field" type="email" name="email" id="friend-email"
                                               placeholder="Add email address...">
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-5">
                                    <button id="invite-friend" type="button"
                                            class="mb-7 field-btn btn btn--shadowed-light btn--medium btn--full-w">Send
                                    </button>
                                </div>
                            </div>

                            <div class="row row-bottom dashboard-group-md">
                                <div class="col-xl-8 col-md-7">
                                    <div class="field-group">
                                        <label class="field-label" for="field2">Referral link:</label>
                                        <input class="input-field" type="text" name="referral" value="{{ $referralLink }}" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-5">
                                    <button id="copy-link" type="button"
                                            class="mb-7 field-btn btn btn--shadowed-light btn--medium btn--full-w">Copy Link
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="dashboard-social">
                        <span class="field-label">Invite via social media:</span>
                        <div class="row">
                            <div class="col-sm-4 pr-0">
                                <a href="http://www.facebook.com/sharer.php?u={{$referralLink}}&p[title]=Join Me" id="fb-share" class="field-btn btn btn--facebook btn--medium btn--full-w" target="_blank">
                                    <i></i>Facebook
                                </a>
                            </div>
                            <div class="col-sm-4 pr-0">
                                <a href="https://plus.google.com/share?url={{$referralLink}}" id="google-share" class="field-btn btn btn--google btn--medium btn--full-w" target="_blank">
                                    <i></i>Google
                                </a>
                            </div>
                            <div class="col-sm-4 pr-0">
                                <a href="https://twitter.com/intent/tweet?text=Join%20Me%20:&url={{$referralLink}}" id="tw-share" class="field-btn btn btn--twitter btn--medium btn--full-w" target="_blank">
                                    <i></i>Twitter
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="border-right-image dashboard-group">
                        <img src="/images/refer-friend.jpg" alt="Refer a friend">
                    </div>
                </div>
            </div>

            <div class="dashboard-inv">
                <h2 class="h4 headline-mb">Sent invitations: </h2>
            </div>
            <div class="table-responsive-500">
                <table id="invites-list" class="inv-table table-black">
                    <thead>
                    <tr>
                        <th colspan="2">Name</th>
                        <th>Status</th>
                        <th>Bonus (ZNX)</th>
                        <th>Commission (ETH)</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($invitedUsers as $invitedUser)
                        <tr id="{{ $invitedUser['email'] }}">
                            <td width="100" class="col-center">
                                <div class="thumb-60">
                                    <img src="{{ $invitedUser['avatar'] }}" alt={{ $invitedUser['name'] }}>
                                </div>
                            </td>
                            <td> {{ $invitedUser['name'] }} </td>
                            <td>
                                <span class="primary-color">{{ $invitedUser['status'] }}</span>
                            </td>
                            <td style="min-width: 100px">{{ $invitedUser['bonus'] }}</td>
                            <td style="min-width: 100px">{{ $invitedUser['commission'] }}</td>
                            <td width="160" class="col-center"><a href="" class="send-link resend-invitation">Resend</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Save profile confirmation -->
    <div class="logon-modal mfp-hide" id="profile-modal">
        <div class="logon-modal-container">
            <h3 class="h4">SAVED!</h3>
            <div class="logon-modal-text">
                <p>Your profile successfully saved.</p>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="/js/user_refer_friend.js" type="text/javascript"></script>
@endsection