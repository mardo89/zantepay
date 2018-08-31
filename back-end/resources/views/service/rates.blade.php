@extends('layouts.admin')

@section('main-menu')
	<li><a href="mail-events">Mail Events</a></li>
	<li><a href="verification">Verification</a></li>
	<li class="current-menu-item"><a href="rates">Rates</a></li>
@endsection

@section('content')

	<main class="main main-dashboard">
		<div class="container">

			<form id="update_rates_frm">

				<h2 class="h4 headline-mb">ZPAY rates:</h2>

				<div class="row">

					<div class="col-md-4">
						<div class="form-group">
							<label class="field-label" for="field3">ETH</label>
							<input class="input-field" type="text" name="eth_rate" id="field3" value="{{ $ethRate }}">
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="field-label" for="field3">USD</label>
							<input class="input-field" type="text" name="usd_rate" id="field3" value="{{ $usdRate }}">
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="field-label" for="field3">EURO</label>
							<input class="input-field" type="text" name="euro_rate" id="field3" value="{{ $euroRate }}">
						</div>
					</div>

					<div class="col-lg-2">
						<button type="submit" class="mb-20 btn btn--medium btn--shadowed-light btn--full-w">
							Update Rate
						</button>
					</div>

				</div>

			</form>

		</div>
	</main>

@endsection

@section('popups')

	<!-- Update rates confirmation -->
	<div class="logon-modal mfp-hide" id="confirm-rates-update-modal">
		<div class="logon-modal-container">
			<h3 class="h4">UPDATED!</h3>
			<div class="logon-modal-text">
				<p>ZPAY rates successfully updated.</p>
			</div>
		</div>
	</div>

@endsection

@section('scripts')
	<script src="/js/service-rates.js" type="text/javascript"></script>
@endsection