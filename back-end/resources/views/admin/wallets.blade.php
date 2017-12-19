@extends('layouts.admin')

@section('main-menu')
    <li><a href="users">Users</a></li>
    <li class="current-menu-item"><a href="wallets">Wallets</a></li>
@endsection

@section('content')

    <main class="main main-dashboard">
        <div class="container">
            <form action="">
                <div class="dashboard-group-sm">
                    <div class="row">
                        <div class="col-lg-6 col-sm-4 mb-20">
                            <h2 class="h4 headline-mb">Debit Cards:</h2>
                            <div class="row">
                                @foreach($debitCards as $debitCard)
                                    <div class="col-lg-4">
                                        <div class="checkbox">
                                            <input type="checkbox" name="dc-filter" id="{{ "dc" . $debitCard['id'] }}"
                                                   value="{{ $debitCard['name'] }}" checked>
                                            <label for="{{ "dc" . $debitCard['id'] }}">{{ $debitCard['name'] }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dashboard-group-sm">
                    <h2 class="h4 headline-mb">Search by email:</h2>
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <input class="input-field search-field wallets-search" type="text" name="search-by-email" id="field1">
                                <a href="" class="search-cross"></a>
                            </div>
                        </div>
                    </div>
                </div>

            </form>

            <div class="table-responsive-500">
                <table id="users-list" class="table-black table">
                    <thead>
                    <tr>
                        <th>Email</th>
                        <th width="200">Debit Card</th>
                        <th width="200">ZTX</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr id="{{ $user['id'] }}">
                            <td>
                                <a class="primary-color" href="{{ $user['walletLink'] }}" target="_blank">{{ $user['email'] }}</a>
                            </td>
                            <td> {{ $user['debitCard'] }} </td>
                            <td> {{ $user['ztx'] }} </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>

@endsection