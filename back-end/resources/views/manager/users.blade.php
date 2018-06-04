@extends('layouts.admin')

@section('main-menu')
    <li class="current-menu-item"><a href="users">Users</a></li>
@endsection

@section('content')

    <main class="main main-dashboard">
        <div class="container">
            <form id="search_user_frm">

                <div class="dashboard-group">
                    <div class="row">
                        <div class="col-lg-5 col-sm-4 mb-20">
                            <h2 class="h4 headline-mb">User role:</h2>
                            <div class="row">
                                @foreach($roles as $role)
                                    <div class="col-lg-4">
                                        <div class="checkbox">
                                            <input type="checkbox" name="role-filter" id="{{ "rf" . $role['id'] }}"
                                                   value="{{ $role['id'] }}" checked>
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

                        <div class="col-lg-4 col-sm-4 mb-20 col-vertical-line-right">
                            <h2 class="h4 headline-mb">Total users:</h2>
                            <div class="row">
                                <div class="col-lg-12">
                                    <h1>{{$usersCount}}</h1>
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
                                            <input type="checkbox" name="status-filter" id="{{ "sf" . $status['id'] }}"
                                                   value="{{ $status['id'] }}" checked>
                                            <label for="{{ "sf" . $status['id'] }}">{{ $status['name'] }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>

                <div class="dashboard-group">
                    <div class="row">
                        <div class="col-lg-6">

                            <h2 class="h4 headline-mb">Date interval:</h2>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <div class="date-picker-wrap">
                                            <input class="input-field date-picker-inp" type="text" name="date_from_filter" data-toggle="datepicker" placeholder="From">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <div class="date-picker-wrap">
                                            <input class="input-field date-picker-inp" type="text" name="date_to_filter" data-toggle="datepicker" placeholder="To">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-lg-6">

                            <h2 class="h4 headline-mb">Search users:</h2>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input class="input-field search-field" type="text" name="search-by-email" id="field1" placeholder="Email / Name">
                                        <a href="" class="search-cross"></a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <div class="dashboard-group">
                    <div class="row">
                        <div class="col-lg-3">
                            <button type="submit" class="mb-20 btn btn--medium btn--shadowed-light btn--full-w">
                                Search
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="table-responsive-500">
                <table id="users-list" class="inv-table table-black">
                    <thead>
                    <tr>
                        <th class="sort" colspan="2">Email <span class="caret"></span></th>
                        <th class="sort">Name <span class="caret"></span></th>
                        <th class="sort">Registered <span class="caret"></span></th>
                        <th>Role</th>
                        <th>Status</th>
                        <th width="100">Referrer</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <nav class="text-center mt-20">
                <ul class="pagination">
                </ul>
            </nav>

        </div>
    </main>

@endsection

@section('scripts')
    <script src="/js/admin_users.js" type="text/javascript"></script>
@endsection