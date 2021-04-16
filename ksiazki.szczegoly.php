<?php

// jesli nie podano parametru id, przekieruj do listy książek
if(empty($_GET['id'])) {
    header("Location: ksiazki.lista.php");
    exit();
}

$id = (int)$_GET['id'];

include 'header.php';

use Ibd\Ksiazki;

$ksiazki = new Ksiazki();
$dane = $ksiazki->pobierz($id);
?>

    <h2><?=$dane['tytul']?></h2>

    <p>
        <a href="ksiazki.lista.php"><i class="fas fa-chevron-left"></i> Powrót</a>
    </p>

    <div class="row">
        <div class="w-50 p-3">
            <?php if (!empty($dane['zdjecie'])) : ?>
                <img src="zdjecia/<?= $dane['zdjecie'] ?>" alt="<?= $dane['tytul'] ?>" class="img-thumbnail" />
            <?php else : ?>
                brak zdjęcia
            <?php endif; ?>
        </div>
        <div class="w-50 p-3">
            <h4>Opis:</h4>
            <p class="small"><?= $dane['opis'] ?></p>
            <p class="small">Liczba stron: <?= $dane['liczba_stron'] ?></p>
            <p class="small">ISBN: <?= $dane['isbn'] ?></p>
            <p class="medium bg-info text-white p-2 w-25"><strong>Cena: </strong> <?= $dane['cena'] ?></p>
        </div>
    </div>


<?php include 'footer.php'; ?>