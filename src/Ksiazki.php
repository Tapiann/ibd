<?php

namespace Ibd;

class Ksiazki
{
    /**
     * Instancja klasy obsługującej połączenie do bazy.
     *
     * @var Db
     */
    private Db $db;

    public function __construct()
    {
        $this->db = new Db();
    }

    /**
     * Pobiera wszystkie książki.
     *
     * @return array
     */
    public function pobierzWszystkie(): ?array
    {
        $sql = "SELECT ksiazki.*, autorzy.imie, autorzy.nazwisko, kategorie.nazwa 
                FROM ksiazki 
                INNER JOIN autorzy ON autorzy.id=ksiazki.id_autora 
                INNER JOIN kategorie ON kategorie.id=ksiazki.id_kategorii";

        return $this->db->pobierzWszystko($sql);
    }

    /**
     * Pobiera dane książki o podanym id.
     *
     * @param int $id
     * @return array
     */
    public function pobierz(int $id): ?array
    {
        return $this->db->pobierz('ksiazki', $id);
    }

    /**
     * Pobiera najlepiej sprzedające się książki.
     *
     */
    public function pobierzBestsellery()
    {
        $sql = "SELECT ksiazki.id, ksiazki.tytul, ksiazki.zdjecie, autorzy.imie, autorzy.nazwisko FROM ksiazki INNER JOIN autorzy ON autorzy.id=ksiazki.id_autora ORDER BY RAND() LIMIT 5";

        return $this->db->pobierzWszystko($sql);
    }

}
