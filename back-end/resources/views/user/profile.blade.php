@extends('layouts.user')

@section('main-menu')
    <li class="current-menu-item"><a href="profile">Profile</a></li>
    <li><a href="profile-settings">Verify Account</a></li>
    <li><a href="wallet">Wallet</a></li>
    <li><a href="debit-card">ZANTEPAY Debit Card</a></li>
@endsection

@section('content')

    <main class="main main-dashboard">
        <div class="container">
            <form id="user-profile">

                <div class="dashboard-group-sm">
                    <h2 class="h4 headline-mb">Main information:</h2>
                    <div class="row">
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group profile_first_name">
                                <label class="field-label" for="field1">First name:</label>
                                <input class="input-field" type="text" name="f-name" maxlength="100"
                                       value="{{ $user->first_name }}">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group profile_middle_name">
                                <label class="field-label" for="field12">Middle name:</label>
                                <input class="input-field" type="text" name="m-name" maxlength="100" value="">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group profile_last_name">
                                <label class="field-label" for="field2">Last name:</label>
                                <input class="input-field" type="text" name="l-name" maxlength="100"
                                       value="{{ $user->last_name }}">
                            </div>
                        </div>
                        <div class="col-lg-3 hidden-lg"></div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group profile_email">
                                <label class="field-label" for="field3">Email:</label>
                                <input class="input-field" type="email" name="email" value="{{ $user->email }}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group">
                                <label class="field-label" for="field23">Area code:</label>
                                <select name="area-code" class="input-field">
                                    @foreach($codes as $code)
                                        <option
                                                value="{{ $code['id'] }}"
                                                @if($code['id'] == $user->area_code)
                                                selected
                                                @endif
                                        >
                                            {{ $code['code'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group profile_phone_number">
                                <label class="field-label" for="field4">Phone number:</label>
                                <input class="input-field" type="text" name="tel" maxlength="20"
                                       value="{{ $user->phone_number }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dashboard-group-sm">
                    <h2 class="h4 headline-mb">Address:</h2>
                    <div class="row">
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group profile_country">
                                <label class="field-label">Country:</label>
                                @if($profile->countryName)

                                    <input class="input-field" type="text" name="country_name" value="{{ $profile->countryName }}" readonly>

                                @else

                                    <select name="country" class="input-field">
                                        @foreach($countries as $country)
                                            <option
                                                    value="{{ $country['id'] }}"
                                                    @if($country['id'] == $profile->country_id)
                                                    selected
                                                    @endif
                                            >
                                                {{ $country['name'] }}
                                            </option>
                                        @endforeach
                                    </select>

                                @endif
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group profile_state">
                                <label class="field-label">State / County:</label>
                                <select name="state" class="input-field">
                                    @foreach($states as $state)
                                        <option
                                                value="{{ $state['id'] }}"
                                                @if($state['id'] == $profile->state_id)
                                                selected
                                                @endif
                                        >
                                            {{ $state['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group profile_city">
                                <label class="field-label" for="field5">City:</label>
                                <input class="input-field" type="text" name="city" maxlength="100"
                                       value="{{ $profile->city }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group profile_address">
                                <label class="field-label" for="field6">Address:</label>
                                <input class="input-field" type="text" name="address" value="{{ $profile->address }}">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group profile_postcode">
                                <label class="field-label" for="field7">Postcode:</label>
                                <input class="input-field" type="text" name="post-code" maxlength="10"
                                       value="{{ $profile->post_code }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dashboard-group-sm">
                    <h2 class="h4 headline-mb">Identification:</h2>
                    <div class="row">
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group profile_passport">
                                <label class="field-label" for="field8">Passport / Government ID:</label>
                                <input class="input-field" type="text" name="government" maxlength="50"
                                       value="{{ $profile->passport_id }}">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group profile_expiration_date">
                                <label class="field-label" for="field9">Passport / ID expiry date:</label>
                                <div class="date-picker-wrap">
                                    <input class="input-field date-picker-inp" type="text" name="expiry"
                                           data-toggle="datepicker"
                                           value="{{ $profile->passportExpDate }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group profile_birth_date">
                                <label class="field-label" for="field10">Date of birth:</label>
                                <div class="date-picker-wrap">
                                    <input class="input-field date-picker-inp" type="text" name="birth"
                                           data-toggle="datepicker"
                                           value="{{ $profile->birthDate }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group profile_birth_country">
                                <label class="field-label" for="field11">Country of birth:</label>
                                <select name="country-birth" class="input-field">
                                    @foreach($countries as $country)
                                        <option
                                                value="{{ $country['id'] }}"
                                                @if($country['id'] == $profile->birth_country_id)
                                                selected
                                                @endif
                                        >
                                            {{ $country['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <button id="save-profile" type="button" class="btn btn--shadowed-light btn--medium btn--160">Save
                </button>

            </form>

            <div class="dashboard-group-sm p-t-60 mt-40">
                <h2 class="h4 mb-20">Close Account:</h2>
                <form id="frm_close_account">
                    <div class="checkbox checkbox--top mb-30">
                        <input type="checkbox" name="close-account-confirm" id="check21"><label for="check21">I
                            understand that by clicking this checkbox, I am willing to close my ZANTEPAY account. All
                            data related with this account will be deleted forever and it will not be possible to
                            recover it (debit card, tokens etc). By closing account you agree with above mentioned
                            terms.</label>
                    </div>
                    <button type="submit" class="btn btn--shadowed-light btn--medium btn--160">Close Account</button>
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
                <p>Your profile successfully saved.</p>
            </div>
        </div>
    </div>

    <!-- Close account confirmation -->
    <div class="logon-modal mfp-hide" id="close-account-modal">
        <div class="logon-modal-container">
            <h3 class="h4">CLOSED!</h3>
            <div class="logon-modal-text">
                <p>Your account successfully closed.</p>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="/js/user_profile.js" type="text/javascript"></script>
@endsection