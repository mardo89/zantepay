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
            <div class="card-steps-wrap">
                <h2 class="h4">Pre-order ZANTEPAY debit card</h2>
                <div class="card-steps">
                    <ul>
                        <li>
                            <span>Select a design</span>
                            <div class="right-triangle"></div>
                        </li>
                        <li>
                            <div class="left-triangle"></div>
                            <span>Upload documents</span>
                            <div class="right-triangle"></div>
                        </li>
                        <li class="current-step">
                            <div class="left-triangle"></div>
                            <span>Verify address</span>
                            <div class="right-triangle"></div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 offset-xl-3">
                    <form id="dc_address">
                        <div class="step2-container">
                            <div class="mb-15">Your address proof documents:</div>
                            <!-- you can use this plugin https://github.com/danielm/uploader for example-->
                            <!-- please add/remove .dragable class for .drag-drop-area when you dragging element -->
                            <div class="drag-drop-area">
                                <div class="drag-drop-container">
                                    <div class="drag-drop-ico"></div>
                                    <div class="drag-drop-text">Click to open explorer or <br> drag and drop your verification file here</div>
                                    <label>
                                        <span class="btn btn--shadowed-light btn--medium">Choose file</span>
                                        <input id="address-files" type="file" name="files[]" multiple="multiple" title='Click to add Files'>
                                    </label>
                                    <div class="drag-drop-text">Accepted file formats: png, jpeg, pdf. <br> The document should not be bigger than 4 Mb.</div>
                                </div>
                            </div>
                            <div class="text-center mt-15">
                                <div class="checkbox">
                                    <input type="checkbox" name="confirm" id="check1"><label for="check1" class="text-sm">Verify later</label>
                                </div>
                            </div>
                            <div class="card-submit">
                                <div class="card-submit-wrap">
                                    <a href="/user/debit-card-documents" class="card-back">Done</a>
                                    <button class="btn btn--shadowed-light btn--medium btn--160" type="submit">
                                        Next
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

@endsection

@section('scripts')
    <script src="/js/user_debit_card.js" type="text/javascript"></script>
@endsection