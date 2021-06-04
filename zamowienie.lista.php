<?php
require_once 'vendor/autoload.php';

use Ibd\Zamowienia;
include 'header.php';
$zamowienia = new Zamowienia();
if(empty($_SESSION['id_uzytkownika'])) {
    header("Location: index.php");
}


$listaZamowien = $zamowienia->pobierzWszystkie($_SESSION['id_uzytkownika']);


?>

<h2>Lista zamówień</h2>

<table class="table table-striped table-condensed">
    <thead>
    <tr>
        <th>Nr zamówenia</th>
        <th>Tytuł</th>
        <th>Autor</th>
        <th>Cena PLN</th>
        <th>Liczba sztuk</th>
        <th>Cena razem</th>
        <th>Data</th>
    </tr>
    </thead>

    <?php if(count($listaZamowien)>0): ?>
        <tbody>
            <?php foreach($listaZamowien as $zamowienie): ?>
                <tr>
                    <td><?= $zamowienie['id_zamowienia'] ?></td>
                    <td><?= $zamowienie['tytul'] ?></td>
                    <td><?= $zamowienie['id_autora'] ?></td>
                    <td><?= $zamowienie['cena'] ?></td>
                    <td><?= $zamowienie['liczba_sztuk'] ?></td>
                    <td><?= $zamowienie['cena'] * $zamowienie['liczba_sztuk'] ?></td>
                    <td><?= $zamowienie['data_dodania'] ?></td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    <?php else: ?>
        <tr><td colspan="8" style="text-align: center">Brak zamówień.</td></tr>
    <?php endif; ?>
</table>
<?php include 'footer.php'; ?>