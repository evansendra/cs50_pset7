<h2>Sell all the shares of one of your stocks.</h2>

<?php if ($sold_stock): ?>
    <p class="bg-success notification" onclick="$(this).hide()">
        You sold all your <?= $sold_stock ?> for $<?= number_format($cash_made, 2)?> <br />
        <span class="small">(click to dismiss)</span>
    </p>
<?php endif ?>

<form action="sell.php" method="post">
    <fieldset>
        <div class="form-group">
            <select name="to_sell" id="stock_select" class="form-control">

            <option value="" selected>Choose stock</option>
            <?php foreach ($stocks as $stock): ?>

            <?php $symbol = $stock["stock"]; ?>
                <option value="<?php echo htmlspecialchars($symbol); ?>">
                    <?= htmlspecialchars($symbol)?> (<?= htmlspecialchars($stock["shares"])?>)
                </option>

            <?php endforeach ?>

            </select>
        </div>
        <div class="form-group">
            <button type="submit" name="submit" value="Submit" class="btn btn-default">Sell</button>
        </div>
    </fieldset>
</form>