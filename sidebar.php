<?php
use Ibd\Ksiazki;

$ksiazki = new Ksiazki();
$lista = $ksiazki->pobierzBestsellery();
//var_dump($lista);
?>

<div class="col-md-2">
    <h1>Bestsellery</h1>

    <ul class="list-group">
        <?php foreach ($lista as $ks) : ?>
              <li class="list-group-item" onclick="location='ksiazki.szczegoly.php?id=<?= $ks['id'] ?>'">

                  <?php if (!empty($ks['zdjecie'])) : ?>
                      <img src="zdjecia/<?= $ks['zdjecie'] ?>" alt="<?= $ks['tytul'] ?>" class="img-thumbnail" />
                  <?php else : ?>
                      brak zdjęcia
                  <?php endif; ?></br>

                 <span class="small"><strong><?= $ks['tytul'] ?></strong></span> </br>


                  <span class="small"><?= $ks['imie']." ".$ks['nazwisko'] ?></span></br>

              </li>
        <?php endforeach; ?>
    </ul>
</div>