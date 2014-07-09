<div>
		<?php if ( empty($positions) ): ?>
			<p>You don't have any stocks right now.  Maybe you want to <a href="buy.php">buy some</a>
				or <a href="quote.php">get a quote</a></p>
		<?php else: ?>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Stock</th>
						<th>Name</th>
						<th>Shares</th>			
						<th>Price</th>
						<th>Total</th>
					</tr>
				</thead>

				<tbody>

					<?php foreach ($positions as $position): ?>
						<tr>
							<td><?= $position["symbol"] ?></td>
							<td><?= $position["name"] ?></td>
							<td><?= $position["shares"] ?></td>
							<td>$<?= $position["price"] ?></td>
							<td>$<?= $position["total"] ?></td>
						</tr>
					<?php endforeach ?>
					</tbody>

				<tr>
					<td colspan="4">CASH</td>
					<td>$<?= $balance ?></td>
				</tr>

			</table>
		<?php endif ?>
					
</div>
<div>
    <a href="logout.php">Log Out</a>
</div>
