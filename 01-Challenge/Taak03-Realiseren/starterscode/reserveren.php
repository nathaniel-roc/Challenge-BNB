<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserveren</title>
</head>

<body>

    <?php
    if (!isset($_GET['id']) ?? $_GET['id'] == null) {
    ?>
        <h1>No imput</h1>


</body>

</html>

<?php
    } else {
        require "database.php";
        $stmt = $conn->prepare('SELECT * FROM homes WHERE id = :id');
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->execute();
        $DataInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($DataInfo as $info) :
            $name = $info['name'];
            $pricePP = $info['price_p_p_p_n'];
            $priceBD = $info['price_bed_sheets'];
            $priceBR = $info['price_bike_rental'];
        endforeach;
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>

    <body>
        <div class="book">
            <h3>Reservering maken voor <?= $name ?></h3>
            <form action="reserveren.php" method="GET">
                <input name="id" type="hidden" value="<?= $_GET['id'] ?>">
                <div class="form-control">
                    <label for="aantal_personen">Aantal personen</label>
                    <input type="number" name="aantal_personen" id="aantal_personen">
                </div>
                <div class="form-control">
                    <label for="aantal_dagen">Aantal dagen</label>
                    <input type="number" name="aantal_dagen" id="aantal_dagen">
                </div>
                <div class="form-control">
                    <h5>Beddengoed</h5>
                    <label for="beddengoed_ja">Ja</label>
                    <input type="radio" id="beddengoed_ja" name="beddengoed" value="ja">
                    <label for="beddengoed_nee">Nee</label>
                    <input type="radio" id="beddengoed_nee" name="beddengoed" value="nee">
                </div>
                <button>Reserveer huis</button>
        </div>
        </form>
        <?php
        if (!empty($_GET['aantal_personen']) && !empty($_GET['aantal_dagen']) && !empty($_GET['beddengoed'])) {
            $price = $_GET['aantal_personen'] * $pricePP * $_GET['aantal_dagen'];
            if ($_GET['beddengoed'] == 'ja') {
                $total_price = $price + $_GET['aantal_personen'] * $priceBD;
                echo "<p>de totale prijs is €$total_price</p>";
            } else {
                echo "<p>de totale prijs is €$price</p>";
            }
        } else {
            echo "niet alles in ingevuld";
        }
        ?>
    </body>

    </html>

<?php } ?>