@extends('layouts.admin')

@section('main-menu')
    <li><a href="users">Users</a></li>
    <li><a href="wallet">Wallet</a></li>
    <li class="current-menu-item"><a href="newsletter">Newsletter</a></li>
@endsection

@section('content')

    <main class="main main-dashboard">
        <div class="container">
            <a href="newsletter/import" class="mb-20 btn btn--medium btn--shadowed-light" target="_blank"> Import to
                CSV </a>

            <div class="table-responsive-200">
                <table id="events-list" class="inv-table table-black">
                    <thead>
                    <tr>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Joined</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($newsletters as $newsletter)
                        <tr>
                            <td>{{ $newsletter['email'] }}</td>
                            <td>{{ $newsletter['name'] }}</td>
                            <td>{{ $newsletter['joined'] }}</td>
                        </tr>
                    @endforeach
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
