@extends('vendor.redeem-codes.layouts.app')
@section('content')
<div class="container">
	<div class="col-sm-offset-2 col-sm-8">
		<div class="panel panel-default">
			<div class="panel-heading">
				Redeem Code Added
			</div>
			<div class="panel-body">
				<div id="codes" class="col-sm-12">
					@foreach($codes as $v)
					{{ $v }}<br />
					@endforeach
				</div>
				<div class="col-sm-6" style="margin-top: 20px">
					<a id="select-all" class="btn btn-default pull-left">Select All</a>
				</div>
				<div class="col-sm-6" style="margin-top: 20px">
					<a class="btn btn-default pull-right" href="{{ url('redeem_codes') }}">Back</a>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script>
	$(document).ready(function() {
		var selected = false;
		$("#select-all").click(function() {
			if (selected) {
				if (document.selection) {
					document.selection.empty();
				} else if (window.getSelection) {
					window.getSelection().removeAllRanges();
				}
			} else {
				if (document.body.createTextRange) {
					var range = document.body.createTextRange();
					range.moveToElementText(document.getElementById('codes'));
					range.select();
				} else if (window.getSelection) {
					var selection = window.getSelection();
					var range = document.createRange();
					range.selectNodeContents(document.getElementById('codes'));
					selection.removeAllRanges();
					selection.addRange(range);
				}
			}
			selected = !selected;
		});
	}); 
</script>
@endsection
