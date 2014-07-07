<h2>Sell all the shares of one of your stocks.</h2>
<form action="sell.php" method="post">
    <fieldset>
        <div class="form-group">
            <select name="to_sell" id="stock_select" class="form-control">

            <option value="">Choose stock</option>
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