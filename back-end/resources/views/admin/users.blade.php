@extends('layouts.admin')

@section('main-menu')
    <li class="current-menu-item"><a href="users-list">Users</a></li>
@endsection

@section('content')

    <main class="main main-dashboard">
        <div class="container">
            <form id="filter-section">

                <div class="dashboard-group-sm">
                    <h2 class="h4 headline-mb">USER ROLE:</h2>

                    <div class="row">
                        @foreach($roles as $role)
                            <div class="col-lg-2 col-md-3 col-sm-4">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <input type="checkbox" name="role-filter" id="{{ "rf" . $role['id'] }}" value="{{ $role['name'] }}" checked>
                                        <label for="{{ "rf" . $role['id'] }}" class="text-sm">{{ $role['name'] }}</label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="dashboard-group-sm">
                    <h2 class="h4 headline-mb">USER STATUS:</h2>

                    <div class="row">
                        @foreach($statuses as $status)
                            <div class="col-lg-4 col-sm-4">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <input type="checkbox" name="status-filter" id="{{ "st" . $status['id'] }}" value="{{ $status['name'] }}" checked>
                                        <label for="{{ "st" . $status['id'] }}" class="text-sm">{{ $status['name'] }}</label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="dashboard-group-sm">
                    <h2 class="h4 headline-mb">IS REFERRER:</h2>

                    <div class="row">
                        <div class="col-lg-2 col-sm-4">
                            <div class="form-group">
                                <div class="checkbox">
                                    <input type="checkbox" name="referrer-filter" id="re1" value="0" checked>
                                    <label for="re1" class="text-sm">YES</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-sm-4">
                            <div class="form-group">
                                <div class="checkbox">
                                    <input type="checkbox" name="referrer-filter" id="re1" value="1" checked>
                                    <label for="re1" class="text-sm">NO</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dashboard-group-sm">
                    <h2 class="h4 headline-mb">SEARCH BY EMAIL:</h2>

                    <div class="row">
                        <div class="col-lg-6 col-sm-6">
                            <div class="form-group">
                                <input class="input-field" type="text" name="search-by-email" maxlength="50">
                            </div>
                        </div>
                    </div>
                </div>

            </form>


            <div class="table-responsive-500">
                <table class="table table-black">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Referrer</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr id="{{ $user['id'] }}">
                            <td width="100" class="col-center">
                                <div class="thumb-60">
                                    <img src="{{ $user['avatar'] }}" alt="{{ $user['name'] }}">
                                </div>
                            </td>

                            <td>
                                <a class="primary-color" href="{{ $user['profileLink'] }}" target="_blank">{{ $user['email'] }}</a>
                            </td>

                            <td> {{ $user['name'] }} </td>


                            <td width="150">
                                {{ $user['role'] }}
                            </td>

                            <td width="150">
                                {{ $user['status'] }}
                            </td>

                            <td width="50">
                                @if ($user['referrerLink'] != '')
                                    <a class="primary-color" href="{{ $user['referrerLink'] }}" target="_blank">{{ $user['referrerEmail'] }}</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>

@endsection