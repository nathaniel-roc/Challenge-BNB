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
        <?php
        require 'database.php';

        if (!isset($conn)) { //deze if-statement checked of er een database-object aanwezig is. Kun je laten staan.
            return;
        }

        $database_gegevens = null;
        $poolIsChecked = false;
        $bathIsChecked = false;

        $sql = "SELECT * FROM homes"; //Selecteer alle huisjes uit de database

        if (isset($_GET['filter_submit'])) {

            if ($_GET['faciliteiten'] == "ligbad") { // Als ligbad is geselecteerd filter dan de zoekresultaten
                $bathIsChecked = true;

                $sql = "SELECT * FROM homes WHERE bath_present > 0"; // query die zoekt of er een BAD aanwezig is.
            }

            if ($_GET['faciliteiten'] == "zwembad") {
                $poolIsChecked = true;

                $sql = "SELECT * FROM homes WHERE pool_present > 0"; // query die zoekt of er een ZWEMBAD aanwezig is.
            }
        }


        if (is_object($conn->query($sql))) { //deze if-statement controleert of een sql-query correct geschreven is en dus data ophaalt uit de DB
            $database_gegevens = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC); //deze code laten staan
        }


        if (isset($_POST['gekozen_huis']) || $_POST != null) {
        }

        ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Document</title>

            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
            <!-- Make sure you put this AFTER Leaflet's CSS -->
            <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
            <link href="css/index.css" rel="stylesheet">
        </head>

        <body>
            <NAV class="topnav">
                <h1>Challenge BNB</h1>
            </NAV>
            <main>
                <div class="left">
                    <div id="mapid"></div>
                </div>
                <div class="right">
                    <div class="filter-box">
                        <form class="filter-form" action='index.php' method="GET">
                            <div class="form-control">
                                <a href="index.php">Reset Filters</a>
                            </div>
                            <div class="form-control">
                                <label for="ligbad">Ligbad</label>
                                <input type="radio" id="ligbad" name="faciliteiten" value="ligbad" <?php if ($bathIsChecked) echo 'checked' ?>>
                            </div>
                            <div class="form-control">
                                <label for="zwembad">Zwembad</label>
                                <input type="radio" id="zwembad" name="faciliteiten" value="zwembad" <?php if ($poolIsChecked) echo 'checked' ?>>
                            </div>
                            <button type="submit" name="filter_submit">Filter</button>
                        </form>
                        <div class="homes-box">
                            <?php if (isset($database_gegevens) && $database_gegevens != null) : ?>
                                <?php foreach ($database_gegevens as $huisje) : ?>
                                    <h4>
                                        <?php echo $huisje['name']; ?>
                                    </h4>

                                    <p class='text'>
                                        <?php echo $huisje['description'] ?>
                                    </p>
                                    <div class="kenmerken">
                                        <h6>Kenmerken</h6>
                                        <ul>

                                            <?php
                                            if ($huisje['bath_present'] ==  1) {
                                                echo "<li>Er is ligbad!</li>";
                                            }
                                            ?>


                                            <?php
                                            if ($huisje['pool_present'] ==  1) {
                                                echo "<li>Er is zwembad!</li>";
                                            }
                                            ?>

                                        </ul>
                                        <button type=button onClick="parent.location='index.php?id=<?= $huisje['id'] ?>'" value='reserveer'>reserveer</button>
                                    </div><br>

                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            </main>
            <footer>
                <p>© Nathaniel de Waal - ROCVA <br>
                    <a href="mailto:nathaniel.dewaal@student.rocva.nl">nathaniel.dewaal@student.rocva.nl</a>
                </p>
            </footer>
            <script src="js/map_init.js"></script>
            <script>
                // De verschillende markers moeten geplaatst worden. Vul de longitudes en latitudes uit de database hierin
                var coordinates = [
                    [52.44902, 4.61001],
                    [52.99864, 6.64928],
                    [52.30340, 6.36800],
                    [50.89720, 5.90979]
                ];

                var bubbleTexts = [
                    "IJmuiden Cottage",
                    "Assen Bungalow",
                    "Espelo Entree",
                    "Weustenrade Woning"
                ];
            </script>
            <script src="js/place_markers.js"></script>
        </body>

        </html>


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
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>

        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
        <!-- Make sure you put this AFTER Leaflet's CSS -->
        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
        <link href="css/index.css" rel="stylesheet">
    </head>

    <body>
        <NAV class="topnav">
            <h1><a href="index.php">Challenge BNB</a></h1>
        </NAV>
        <h3 class="center">Reservering maken voor <?= $name ?></h3>
        <div class="book">
            <form action="index.php" method="GET">
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
                echo "<p>niet alles in ingevuld</p>";
            }
            ?>
        </div>
        <footer>
            <p>© Nathaniel de Waal - ROCVA <br>
                <a href="mailto:nathaniel.dewaal@student.rocva.nl">nathaniel.dewaal@student.rocva.nl</a>
            </p>
        </footer>
    </body>

    </html>

<?php } ?>