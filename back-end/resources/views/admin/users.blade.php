@extends('layouts.admin')

@section('main-menu')
    <li class="current-menu-item"><a href="users">Users</a></li>
    <li><a href="wallet">Wallet</a></li>
@endsection

@section('content')

    <main class="main main-dashboard">
        <div class="container">
            <form action="">
                <div class="dashboard-group">
                    <div class="row">
                        <div class="col-lg-5 col-sm-4 mb-20">
                            <h2 class="h4 headline-mb">User role:</h2>
                            <div class="row">
                                @foreach($roles as $role)
                                    <div class="col-lg-4">
                                        <div class="checkbox">
                                            <input type="checkbox" name="role-filter" id="{{ "rf" . $role['id'] }}" value="{{ $role['name'] }}" checked>
                                            <label for="{{ "rf" . $role['id'] }}">{{ $role['name'] }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-4 mb-20 col-vertical-line-right">
                            <h2 class="h4 headline-mb">Is referrer:</h2>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="checkbox">
                                        <input type="checkbox" name="referrer-filter" id="check6" value="1" checked>
                                        <label for="check6">Yes</label>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="checkbox">
                                        <input type="checkbox" name="referrer-filter" id="check7" value="0" checked>
                                        <label for="check7">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dashboard-group">
                    <div class="row">

                        <div class="col-lg-12 col-sm-4 mb-20">
                            <h2 class="h4 headline-mb">User status:</h2>
                            <div class="row">
                                @foreach($statuses as $status)
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="checkbox">
                                            <input type="checkbox" name="status-filter" id="{{ "sf" . $status['id'] }}" value="{{ $status['name'] }}" checked>
                                            <label for="{{ "sf" . $status['id'] }}">{{ $status['name'] }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>

                <div class="dashboard-group">
                    <h2 class="h4 headline-mb">Search by email:</h2>
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <input class="input-field search-field" type="text" name="search-by-email" id="field1">
                                <a href="" class="search-cross"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="table-responsive-500">
                <table id="users-list" class="inv-table table-black">
                    <thead>
                    <tr>
                        <th colspan="2">Email</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th width="100">Referrer</th>
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
                                <a class="primary-color" href="{{ $user['profileLink'] }}">{{ $user['email'] }}</a>
                            </td>
                            <td> {{ $user['name'] }} </td>
                            <td> {{ $user['role'] }} </td>
                            <td> {{ $user['status'] }} </td>
                            <td>
                                @if ($user['isReferrer'])
                                    YES
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

@section('scripts')
    <script src="/js/admin_users.js" type="text/javascript"></script>
@endsection