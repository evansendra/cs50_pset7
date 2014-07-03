<div>
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

<!--
<code>
		<?php print_r($positions); ?>
	</code>
-->
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
</div>
<div>
    <a href="logout.php">Log Out</a>
</div>
