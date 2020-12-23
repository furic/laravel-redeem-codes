@extends('vendor.redeem-codes.layouts.app')
@section('content')
<div class="container">
	<div class="col-sm-offset-2 col-sm-8">
		<div class="panel panel-default">
			<div class="panel-heading">
				Edit Redeem Code - {{ $redeemCode->code }}
			</div>
			<div class="panel-body">
				<!-- New Redeem Code Form -->
				<form action="{{ route('redeem-codes.update', $redeemCode->id) }}" method="POST" class="form-horizontal">
					{{ method_field('PUT') }}
					{{ csrf_field() }}
					<div class="form-group">
						<label for="code" class="col-sm-3 control-label">Redeem Code</label>
						<div class="col-sm-9">
							<input type="text" id="code" class="form-control" placeholder="{{ $redeemCode->code }}" disabled>
						</div>
					</div>
					<div class="form-group">
						<label for="redeem-code-reusable" class="col-sm-3 control-label">Reusable</label>
						<div class="col-sm-9">
							<input type="checkbox" name="reusable" value="1" id="redeem-code-reusable" class="form-control" {{ $redeemCode->reusable ? 'checked' : '' }}>
						</div>
					</div>
					<div class="form-group">
						<label for="redeem-code-redeemed" class="col-sm-3 control-label">Redeemed</label>
						<div class="col-sm-9">
							<input type="checkbox" name="redeemed" value="1" id="redeem-code-redeemed" class="form-control" {{ $redeemCode->redeemed ? 'checked' : '' }}>
						</div>
					</div>
					@if ($redeemCodesInEvent->count() > 1)
					<div class="alert alert-info">
						Changing info below also affect Redeem Code {{ $redeemCodesInEvent->implode('code', ', ') }}
					</div>
					@endif
					<div class="form-group">
						<label for="redeem-code-description" class="col-sm-3 control-label">Description (Optional)</label>
						<div class="col-sm-9">
							<input type="text" name="description" id="redeem-code-description" class="form-control" value="{{ $redeemCode->event->name }}">
						</div>
					</div>
					<div class="form-group" id="rewards">
						<label for="redeem-code-reward-type-1" class="col-sm-3 control-label">Rewards</label>
						@foreach ($redeemCode->rewards as $i => $reward)
						<div id="reward-{{ $i }}">
							<div class="col-sm-4 {{ $i > 0 ? 'col-sm-offset-3' : '' }}">
								<select name="reward_types[]" id="redeem-code-reward-type-{{ $i + 1 }}" class="form-control">
									<option value="1" {{ $reward->type == 0 ? 'selected' : '' }}>Coin</option>
									<option value="2" {{ $reward->type == 1 ? 'selected' : '' }}>Gem</option>
									<option value="4" {{ $reward->type == 2 ? 'selected' : '' }}>Remove Ads</option>
									<option value="7" {{ $reward->type == 100 ? 'selected' : '' }}>Character</option>
									<option value="10" {{ $reward->type == 999 ? 'selected' : '' }}>Energy</option>
									<option value="18" {{ $reward->type == 999 ? 'selected' : '' }}>World</option>
									<option value="22" {{ $reward->type == 999 ? 'selected' : '' }}>Revive</option>
								</select>
							</div>
							<div class="col-sm-5">
								<input type="number" name="reward_amounts[]" min="1" id="redeem-code-reward-amount-1" class="form-control" value="{{ $reward->amount }}">
							</div>
						</div>
						@endforeach
						<div id="reward-{{ $redeemCode->rewards->count() }}"></div>
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
						<div class="col-sm-offset-1 col-sm-10">
							<button type="submit" class="btn btn-primary btn-block">
								<i class="fa fa-edit"></i> Edit
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
			$('#redeem-code-count-row').toggle(!this.checked);
		});
		var i = {{ $redeemCode->rewards->count() }};
		$('#add-reward').click(function() {
			$('#reward-' + i).html(`
			<div class="col-sm-4 col-sm-offset-3">
				<select name="reward_types[]" class="form-control">
					<option value="1" selected="selected">Coins</option>
					<option value="2">Gems</option>
					<option value="4">Remove Ads</option>
					<option value="7">Character</option>
					<option value="10">Energy</option>
					<option value="18">World</option>
					<option value="22">Revive</option>
				</select>
			</div>
			<div class="col-sm-5">
				<input type="number" name="reward_amounts[]" min="1" class="form-control">
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