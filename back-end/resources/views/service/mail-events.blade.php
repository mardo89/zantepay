@extends('layouts.admin')

@section('main-menu')
    <li class="current-menu-item"><a href="mail-events">Mail Events</a></li>
    <li><a href="verification">Verification</a></li>
@endsection

@section('content')

    <main class="main main-dashboard">
        <div class="container">

            <form id="search_mail_events_frm">

                <div class="dashboard-group">
                    <div class="row">
                        <div class="col-lg-12 col-sm-4 mb-20">
                            <h2 class="h4 headline-mb">Email type:</h2>
                            <div class="row">
                                @foreach($eventTypes as $eventType)
                                    <div class="col-lg-4">
                                        <div class="checkbox">
                                            <input type="checkbox" name="type-filter" id="{{ "tf" . $eventType['id'] }}"
                                                   value="{{ $eventType['id'] }}" checked>
                                            <label for="{{ "tf" . $eventType['id'] }}">{{ $eventType['name'] }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dashboard-group">
                    <div class="row">

                        <div class="col-lg-12 col-sm-4 mb-20">
                            <h2 class="h4 headline-mb">Email status:</h2>
                            <div class="row">
                                @foreach($eventStatuses as $eventStatus)
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="checkbox">
                                            <input type="checkbox" name="status-filter" id="{{ "sf" . $eventStatus['id'] }}"
                                                   value="{{ $eventStatus['id'] }}" checked>
                                            <label for="{{ "sf" . $eventStatus['id'] }}">{{ $eventStatus['name'] }}</label>
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
                <table id="events-list" class="inv-table table-black">
                    <thead>
                    <tr>
                        <th class="sort">Date <span class="caret"></span></th>
                        <th >Event</th>
                        <th>To</th>
                        <th>Status</th>
                        <th width="160"></th>
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
    <script src="/js/service-mail-events.js" type="text/javascript"></script>
@endsection