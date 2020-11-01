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
				<form action="{{ url('redeem_codes') }}" method="POST" class="form-horizontal">
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
							<input type="checkbox" name="reusable" value="" id="redeem-code-reusable" class="form-control">
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
						<div class="col-sm-4">
							<select name="reward_types[]" id="redeem-code-reward-type-1" class="form-control">
								<option value="0" selected="selected">Coins</option>
								<option value="1">Gems</option>
								<option value="12">Maps</option>
								<option value="100">Multiplay Kills</option>
								<option value="999">Remove Ads</option>
							</select>
						</div>
						<div class="col-sm-5">
							<input type="number" name="reward_amounts[]" min="1" id="redeem-code-reward-amount-1" class="form-control">
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
                                	<form action="{{ url('redeem_code') . '/' . $redeemCode->id }}" method="GET">
							            <button type="submit" class="btn btn-default">
							            	<i class="fa fa-btn fa-edit"></i> Edit
							            </button>
							        </form>
								</td>
								<td align="right">
									@if ($redeemCode->redeemed)
                                	<form action="{{ url('redeem_code') . '/' . $redeemCode->id }}" method="POST">
							            <button type="submit" class="btn btn-danger">
							            	<i class="fa fa-btn fa-sync"></i> Reset Redeemed
							            </button>
									</form>
									@endif
								</td>
                                <td align="right">
                                	<form action="{{ url('redeem_code') . '/' . $redeemCode->id }}" method="POST">
							            <input name="_method" type="hidden" value="DELETE">
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
