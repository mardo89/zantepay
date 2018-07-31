@extends('layouts.user')

@section('main-menu')
    <li><a href="profile">Profile</a></li>
    <li><a href="profile-settings">Verify Account</a></li>
    <li><a href="wallet">Wallet</a></li>
    <li class="current-menu-item"><a href="debit-card">ZANTEPAY Debit Card</a></li>
@endsection

@section('content')

    <main class="main main-dashboard">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2">
                    <div class="mb-20 text-center">
                        <h2 class="h4">ZANTEPAY FREE debit card pre-order will be available shortly</h2>
                    </div>
                </div>
            </div>

            <div class="p-t-40">
                <div class="row">
                    <div class="col-md-6 col-xl-5 offset-xl-1">
                        <div class="text-center">
                            <img src="/images/wh-card.jpg" srcset="/images/wh-card@2x.jpg 2x" alt="White Debit Card">
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-5">
                        <div class="text-center">
                            <img src="/images/red-card.jpg" srcset="/images/red-card@2x.jpg 2x" alt="Red Debit Card">
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </main>

@endsection

@section('scripts')
    <script src="/js/user_debit_card.js" type="text/javascript"></script>
@endsection