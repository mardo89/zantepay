@extends('layouts.admin')

@section('main-menu')
    <li><a href="mail-events">Mail Events</a></li>
    <li class="current-menu-item"><a href="verification">Verification</a></li>
    <li><a href="rates">Rates</a></li>
@endsection

@section('content')

    <main class="main main-dashboard">
        <div class="container">

            <form id="search_verification_info_frm">

                <div class="dashboard-group">
                    <div class="row">

                        <div class="col-lg-12 col-sm-4 mb-20">
                            <h2 class="h4 headline-mb">Verification status:</h2>
                            <div class="row">
                                @foreach($verificationStatuses as $verificationStatus)
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="checkbox">
                                            <input type="checkbox" name="status-filter" id="{{ "sf" . $verificationStatus['id'] }}"
                                                   value="{{ $verificationStatus['id'] }}" checked>
                                            <label for="{{ "sf" . $verificationStatus['id'] }}">{{ $verificationStatus['name'] }}</label>
                                        </div>
                                    </div>
                                @endforeach
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
                <table id="verification-info" class="inv-table table-black">
                    <thead>
                    <tr>
                        <th class="sort">User <span class="caret"></span></th>
                        <th>Status</th>
                        <th width="120"></th>
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
    <script src="/js/service-verification.js" type="text/javascript"></script>
@endsection