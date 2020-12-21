@extends('vendor.redeem-codes.layouts.app')
@section('content')
@if (session('message'))
<div class="alert alert-info">
	{{ session('message') }}
</div>
@endif
@if (session('success'))
<div class="alert alert-success">
	{{ session('success') }}
</div>
@endif
@if (session('danger'))
<div class="alert alert-danger">
	{{ session('danger') }}
</div>
@endif
<div class="container">
	<div class="col-sm-offset-2 col-sm-8">
		<div class="panel panel-default">
			<div class="panel-heading">
				New Redeem Code
			</div>
			<div class="panel-body">
				<!-- New Redeem Code Form -->
				<form action="{{ route('redeem-codes.store') }}" method="POST" class="form-horizontal">
					<!-- Redeem Prefix -->
					<div class="form-group">
						<label for="redeem-code-prefix" class="col-sm-3 control-label">Code Prefix (Optional)</label>
						<div class="col-sm-9">
							<input type="text" name="prefix" id="redeem-code-prefix" maxlength="11" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label for="redeem-code-reusable" class="col-sm-3 control-label">Reusable</label>
						<div class="col-sm-9">
							<input type="checkbox" name="reusable" value="1" id="redeem-code-reusable" class="form-control">
						</div>
					</div>
					<div class="form-group" id="redeem-code-count-row">
						<label for="redeem-code-count" class="col-sm-3 control-label">Count</label>
						<div class="col-sm-9">
							<input type="number" name="count" value="1" max="500" id="redeem-code-count" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label for="redeem-code-description" class="col-sm-3 control-label">Description (Optional)</label>
						<div class="col-sm-9">
							<input type="text" name="description" id="redeem-code-description" class="form-control">
						</div>
					</div>
					<div class="form-group" id="rewards">
						<label for="redeem-code-reward-type-1" class="col-sm-3 control-label">Rewards</label>
						<div id="reward-0">
							<div class="col-sm-4">
								<select name="reward_types[]" id="redeem-code-reward-type-1" class="form-control">
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
								<input type="number" name="reward_amounts[]" min="1" id="redeem-code-reward-amount-1" class="form-control">
							</div>
						</div>
						<div id="reward-1"></div>
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
								<i class="fa fa-plus"></i> Add
							</button>
						</div>
					</div>
					{{ csrf_field() }}
				</form>
			</div>
		</div>
	</div>
	@if (count($redeemCodes) > 0)
	<div class="col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				Current Redeem Codes
			</div>
			<div class="panel-body">
				<table class="table table-striped task-table">
                    <!-- Table Headings -->
                    <thead>
                        <th>Redeem Code</th>
                        <th>Description</th>
                        <th align="center">#Rewards</th>
                        <th align="center">Reusable</th>
                        <th align="center">Redeemed</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                    </thead>
                    <!-- Table Body -->
                    <tbody>
                        @foreach ($redeemCodes as $redeemCode)
						<tr>
							<td class="table-text">
								<div>{{ $redeemCode->code }}</div>
							</td>
							<td class="table-text">
								<div>{{ $redeemCode->description }}</div>
							</td>
							<td class="table-text" align="center">
								<div>{{ $redeemCode->rewards->count() }}</div>
							</td>
							<td class="table-text" align="center">
								<div>
								@if ($redeemCode->reusable)
								<i class="fa fa-check"></i>
								@endif
								</div>
							</td>
							<td class="table-text" align="center">
								<div>
								@if ($redeemCode->redeemed)
								<i class="fa fa-check"></i>
								@endif
								</div>
							</td>
							<td align="right">
								<a href="{{ route('redeem-codes.edit', $redeemCode->id) }}" class="btn btn-default">
									<i class="fa fa-btn fa-edit"></i> Edit
								</a>
							</td>
							<td align="right">
								@if ($redeemCode->redeemed)
								<form action="{{ route('redeem-codes.update', $redeemCode->id) }}" method="POST">
									{{ method_field('PUT') }}
									{{ csrf_field() }}
									<input type="hidden" name="redeemed" value="0">
									<button type="submit" class="btn btn-danger">
										<i class="fa fa-btn fa-undo"></i> Reset Redeemed
									</button>
								</form>
								@endif
							</td>
							<td align="right">
								<form action="{{ route('redeem-codes.destroy', $redeemCode->id) }}" method="POST">
									{{ method_field('DELETE') }}
									{{ csrf_field() }}
									<button type="submit" class="btn btn-danger">
										<i class="fa fa-btn fa-trash"></i> Delete
									</button>
								</form>
							</td>
						</tr>
                        @endforeach
                    </tbody>
                </table>
			</div>
		</div>
	</div>
	@endif
</div>
@endsection
@section('scripts')
<script>
	$(document).ready(function() {
		$('#redeem-code-reusable').change(function() {
			$('#redeem-code-count-row').toggle(!this.checked);
		});
		var i = 1;
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