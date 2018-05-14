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
            <div class="card-steps-wrap text-center p-t-60 row">
                <div class="col-xl-8 offset-xl-2 col-lg-10 offset-lg-1">
                    <h2 class="h4">Current pre-order is currently available for EU countries only. To get a card pre-order bonus you have to be a citizen of listed countries and get your verification approved.</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2">
                    <form id="dc_country">
                        <div class="step0-container">
                            <div class="text-center mt-15">
                                <div class="form-group">
                                    <select name="card-country" class="input-field">
                                        <option selected disabled>Select your country</option>
                                        <option value="Austria">Austria</option>
                                        <option value="Belgium">Belgium</option>
                                        <option value="Bulgaria">Bulgaria</option>
                                        <option value="Croatia">Croatia</option>
                                        <option value="Cyprus">Cyprus</option>
                                        <option value="Czech Republic">Czech Republic</option>
                                        <option value="Denmark">Denmark</option>
                                        <option value="Estonia">Estonia</option>
                                        <option value="Finland">Finland</option>
                                        <option value="France">France</option>
                                        <option value="Germany">Germany</option>
                                        <option value="Greece">Greece</option>
                                        <option value="Hungary">Hungary</option>
                                        <option value="Ireland">Ireland</option>
                                        <option value="Italy">Italy</option>
                                        <option value="Latvia">Latvia</option>
                                        <option value="Lithuania">Lithuania</option>
                                        <option value="Luxembourg">Luxembourg</option>
                                        <option value="Malta">Malta</option>
                                        <option value="Netherlands">Netherlands</option>
                                        <option value="Poland">Poland</option>
                                        <option value="Portugal">Portugal</option>
                                        <option value="Romania">Romania</option>
                                        <option value="Slovakia">Slovakia</option>
                                        <option value="Slovenia">Slovenia</option>
                                        <option value="Spain">Spain</option>
                                        <option value="Sweden">Sweden</option>
                                        <option value="United Kingdom (UK)">United Kingdom (UK)</option>
                                    </select>
                                </div>
                                <div class="card-submit">
                                    <div class="card-submit-wrap">
                                        <input class="btn btn--shadowed-light btn--medium btn--160" type="submit" value="Next">
                                    </div>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

@endsection

@section('scripts')
    <script src="/js/user_debit_card.js" type="text/javascript"></script>
@endsection