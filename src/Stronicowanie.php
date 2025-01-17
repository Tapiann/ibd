<?php

namespace Ibd;

class Stronicowanie
{
    /**
     * Instancja klasy obsługującej połączenie do bazy.
     *
     * @var Db
     */
    private Db $db;

    /**
     * Liczba rekordów wyświetlanych na stronie.
     *
     * @var int
     */
    private int $naStronie = 5;

    /**
     * Aktualnie wybrana strona.
     *
     * @var int
     */
    private int $strona = 0;

    /**
     * Dodatkowe parametry przekazywane w pasku adresu (metodą GET).
     *
     * @var array
     */
    private array $parametryGet = [];

    /**
     * Parametry przekazywane do zapytania SQL.
     *
     * @var array
     */
    private array $parametryZapytania;

    public function __construct(array $parametryGet , array $parametryZapytania = [])
    {
        $this->db = new Db();
        $this->parametryGet = $parametryGet;
        $this->parametryZapytania = $parametryZapytania;

        if (!empty($parametryGet['strona'])) {
            $this->strona = (int)$parametryGet['strona'];
        }
    }

    /**
     * Dodaje do zapytania SELECT klauzulę LIMIT.
     *
     * @param string $select
     * @return string
     */
    public function dodajLimit(string $select): string
    {
        return sprintf('%s LIMIT %d, %d', $select, $this->strona * $this->naStronie, $this->naStronie);
    }

    /**
     * Generuje linki do wszystkich podstron.
     *
     * @param string $select Zapytanie SELECT
     * @param string $plik Nazwa pliku, do którego będą kierować linki
     * @return string
     */

    public function pobierzLiczbeKsiazek(string $select): string {
        $rekordow = $this->db->policzRekordy($select, $this->parametryZapytania);
        $pierwszaPozycja = $this->strona * $this->naStronie + 1;
        if ($pierwszaPozycja > $rekordow){
            $pierwszaPozycja = 0;
        }
        $ostatniaPozycja = $this->strona * $this->naStronie + $this->naStronie;
        if($ostatniaPozycja > $rekordow) {
            $ostatniaPozycja = $rekordow;
        }

        $liczbaKsiazek = sprintf("Wyświetlono %d - %d z %d rekordów",
        $pierwszaPozycja,
        $ostatniaPozycja,
        $rekordow);
        return $liczbaKsiazek;
    }


    public function pobierzLinki(string $select, string $plik): string
    {
        $rekordow = $this->db->policzRekordy($select, $this->parametryZapytania);
        $liczbaStron = ceil($rekordow / $this->naStronie);
        $parametry = $this->_przetworzParametry();


        $linki = "<nav><ul class='pagination'>";
        if (0 != $this->strona){
            $linki .= sprintf(
                "<li class='page-item '><a href='%s?%s&strona=%d' class='page-link'>%s</a></li>",
                $plik,
                $parametry,
                0,
                'początek'
            );
        } else {
            $linki .= sprintf(
                "<li class='page-item active'><a class='page-link'>%s</a></li>",
                'początek'
            );
        }

        if (0 != $this->strona){
            $linki .= sprintf(
                "<li class='page-item'><a href='%s?%s&strona=%d' class='page-link'>%s</a></li>",
                $plik,
                $parametry,
                $this->strona-1,
                'poprzednia'
            );
        } else {
            $linki .= sprintf(
                "<li class='page-item inactive'><a class='page-link'>%s</a></li>",
                'poprzednia'
            );
        }

        for ($i = 0; $i < $liczbaStron; $i++) {
            if ($i == $this->strona) {
                $linki .= sprintf("<li class='page-item active'><a class='page-link'>%d</a></li>", $i + 1);
            } else {
                $linki .= sprintf(
                    "<li class='page-item'><a href='%s?%s&strona=%d' class='page-link'>%d</a></li>",
                    $plik,
                    $parametry,
                    $i,
                    $i + 1
                );
            }
        }

        if ($liczbaStron-1 != $this->strona){
            $linki .= sprintf(
                "<li class='page-item'><a href='%s?%s&strona=%d' class='page-link'>%s</a></li>",
                $plik,
                $parametry,
                $this->strona+1,
                'następna'
            );
        } else {
            $linki .= sprintf(
                "<li class='page-item inactive'><a class='page-link'>%s</a></li>",
                'następna'
            );
        }

        if ($liczbaStron-1 != $this->strona){
            $linki .= sprintf(
                "<li class='page-item '><a href='%s?%s&strona=%d' class='page-link'>%s</a></li>",
                $plik,
                $parametry,
                $liczbaStron-1,
                'koniec'
            );
        } else {
            $linki .= sprintf(
                "<li class='page-item active'><a class='page-link'>%s</a></li>",
                'koniec'
            );
        }

        $linki .= "</ul></nav>";



        return $linki;
    }

    /**
     * Przetwarza parametry wyszukiwania.
     * Wyrzuca zbędne elementy i tworzy gotowy do wstawienia w linku zestaw parametrów.
     *
     * @return string
     */
    private function _przetworzParametry(): string
    {
        $temp = [];
        $usun = ['szukaj', 'strona'];
        foreach ($this->parametryGet as $kl => $wart) {
            if (!in_array($kl, $usun))
                $temp[] = "$kl=$wart";
        }

        return implode('&', $temp);
    }
}
