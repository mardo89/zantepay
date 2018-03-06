@extends('manager.profile')

@section('main-menu')
    <li class="current-menu-item"><a href="users">Users</a></li>
    <li><a href="wallet">Wallet</a></li>
@endsection

@section('main-menu')
    <li class="current-menu-item"><a href="users">Users</a></li>
    <li><a href="wallet">Wallet</a></li>
@endsection

@section('change-role')
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
@endsection

@section('remove-user')
    <input id="remove-user" class="mt-20 btn btn--medium btn--shadowed-light" type="button" value="Delete User" />
@endsection

@section('admin-popups')

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


@endsection