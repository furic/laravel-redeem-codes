@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col-sm-offset-2 col-sm-8">
		<div class="panel panel-default">
			<div class="panel-heading">
				New Redeem Code
			</div>
			<div class="panel-body">
				<!-- New Redeem Code Form -->
				<form action="{{ url('redeem_codes') }}" method="PUT" class="form-horizontal">
					<div class="form-group">
						<label for="redeem-code-prefix" class="col-sm-3 control-label">Redeem Code</label>
						<div class="col-sm-9">
							{{ $redeemCode->code }}
						</div>
					</div>
					<div class="form-group">
						<label for="redeem-code-reusable" class="col-sm-3 control-label">Reusable</label>
						<div class="col-sm-9">
							<input type="checkbox" name="reusable" value="" id="redeem-code-reusable" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label for="redeem-code-description" class="col-sm-3 control-label">Description (Optional)</label>
						<div class="col-sm-9">
							<input type="text" name="description" id="redeem-code-description" class="form-control" value="{{ $redeemCode->code }}">
						</div>
					</div>
					<div class="form-group" id="rewards">
						<label for="redeem-code-reward-type-1" class="col-sm-3 control-label">Rewards</label>
						<div class="col-sm-4">
							<select name="reward_types[]" id="redeem-code-reward-type-1" class="form-control">
								<option value="4">Coins</option>
								<option value="5" selected="selected">Gems</option>
								<option value="12">Maps</option>
							</select>
						</div>
						<div class="col-sm-5">
							<input type="number" name="reward_amounts[]" min="1" id="redeem-code-reward-amount-1" class="form-control">
						</div>
						<div id="reward-1"></div>
					</div>
					<div class="form-group">
						<label for="redeem-code-reusable" class="col-sm-3 control-label">Redeemed</label>
						<div class="col-sm-9">
							<input type="checkbox" name="reusable" value="" id="redeem-code-reusable" class="form-control" value="{{ $redeemCode->redeemed }}">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-4 col-sm-offset-3">
							<a id="add-reward" class="btn btn-default pull-left">Add Reward</a>
						</div>
						<div class="col-sm-5">
							<a id='delete-reward' class="pull-right btn btn-default">Delete Reward</a>
						</div>
					</div>
					<!-- Add Redeem Code Button -->
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-6">
							<button type="submit" class="btn btn-default">
								<i class="fa fa-plus"></i> Add
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script>
	$(document).ready(function() {
		$('#redeem-code-reusable').change(function() {
			console.log(this.checked);
			$('#redeem-code-count-row').toggle(!this.checked);
		});
		var i = 1;
		$('#add-reward').click(function() {
			console.log('clicked');
			$('#reward-' + i).html(`
			<div class="col-sm-4 col-sm-offset-3">
				<select name="reward_types[]" id="redeem-code-reward-type-` + (i + 1) + `" class="form-control">
					<option value="4">Coins</option>
					<option value="5" selected="selected">Gems</option>
					<option value="12">Maps</option>
				</select>
			</div>
			<div class="col-sm-5">
				<input type="number" name="reward_amounts[]" min="1" id="redeem-code-reward-amount-` + (i + 1) + `" class="form-control">
			</div>
			`);
			$('#rewards').append('<div id="reward-' + (i + 1) + '"></div>');
			i++;
		});
		$('#delete-reward').click(function() {
			if (i > 1) {
				$('#reward-' + (i - 1)).html('');
				i--;
			}
		});
	});
</script>
@endsection
