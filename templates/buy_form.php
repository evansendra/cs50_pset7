<form role="form" method="post" action="buy.php">
	<fieldset>
		<label for="stock_symbol_in">Stock information</label>
		<div class="form-group">
			<input id="stock_symbol_in" name="symbol" class="form-control" type="text" size="7" placeholder="symbol" />
			<input class="form-control" name="shares" type="text" size="6" placeholder="shares" />
		</div>
		<button type="submit" class="btn btn-default">Buy</button>
	</fieldset>
</form>