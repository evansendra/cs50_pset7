<div>
	<?php if ( empty($transactions) ): ?>
		<p>You haven't made any transactions yet.  Maybe it's time to <a href="buy.php">buy something</a>
			or <a href="quote.php">get a quote</a>?</p>
	<?php else: ?>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Date</th>
					<th>Transaction</th>
					<th>Stock</th>
					<th>Shares</th>			
					<th>Price Per Share</th>
					<th>Transaction Total</th>
					<th>Balance</th>
				</tr>
			</thead>

			<tbody>

				<?php foreach ($transactions as $transaction): 

						if ($transaction["is_buy"] == "purchase")
							$color_class = "danger";
						else
							$color_class = "success";
				?>
					<tr class="<?= $color_class ?>">
						<td><?= $transaction["date"] ?></td>
						<td><?= $transaction["is_buy"] ?></td>
						<td><?= $transaction["symbol"] ?></td>
						<td><?= $transaction["shares"] ?></td>
						<td><?= $transaction["price_per_share"] ?></td>
						<td><?= $transaction["total"] ?></td>
						<td><?= $transaction["balance"] ?></td>
						
					</tr>
				<?php endforeach ?>
				</tbody>

			<tr>
				<td colspan="6">Remaining CASH</td>
				<td><strong><?= $balance ?><strong></td>
			</tr>

		</table>
	<?php endif ?>
					
</div>
<div>
    <a href="logout.php">Log Out</a>
</div>