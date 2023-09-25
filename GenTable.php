<?php
/**
 * Klasa GenTable służy do generowania tabeli HTML na podstawie danych i struktury formularza.
 *
 * @author Paweł Pakos <address @ example.com>
 * @version 1.0
 * @since 1.0
 */
class GenTable {

    private $acl;
    private $title = '';
    private $formStruct = array();
    private $dataOrder = array();

    /**
     * Konstruktor klasy GenTable.
     * @param object $acObj Przypisanie objektu $acl.
     * @param string $tblName Nazwa tabeli.
     * @param string $struct Zmienna struktury formularza.
     */
    public function __construct($aclObj, $tblName, $struct)
    {
        $this->acl = $aclObj;
        $this->title = $tblName;
        $this->openForm($struct);
    }
    
    /**
     * Otwiera formularz na podstawie nazwy .
     *
     * @param string $formName Nazwa formularza.
     */
    public function openForm($formName)
    {
        $formFile = dirname(__FILE__) . '/form.' . $formName . '.php';
        if (file_exists($formFile)) {
            include($formFile);
            $this->formStruct = $form;
        }
    }

    /**
     * Tworzy nagłówki tabeli na podstawie struktury formularza.
     *
     * @param array $formStruct Struktura formularza.
     * @return string Nagłówki tabeli HTML.
     */
    private function tblHeading($formStruct)
    {
        $reOrder = array_fill(0, count($formStruct), 0);
        $this->dataOrder = array_fill(0, count($formStruct), 0);
        $th = '';
        foreach ($formStruct as $key => $def) {
            $ord = $def[0];
            $reOrder[$ord] = array($key, $def[1]);
            $this->dataOrder[$ord] = $key;
        }
        foreach ($reOrder as $k => $def) {
            $th .= '<th>' . $def[1] . '</th>';
        }
        $thead = '<tr>' . $th . '</tr>';
        return $thead;
    }

    /**
     * Tworzy dane tabeli na podstawie dostarczonych danych.
     *
     * @param array $data Dane do umieszczenia w tabeli.
     * @return string Zawartość ciała tabeli HTML.
     */
    private function tblData($data)
    {
        if (count($data) == 0) { // Sprawdza, czy istnieją dane.
            return '<tr><td>brak danych</td></tr>';
        }
        $tbody = '';
        foreach ($data as $entry) { // Tworzy tabelę z danymi.
            $cell = '';
            foreach ($this->dataOrder as $key) {
                $cdata = $entry[$key];
                $cell .= '<td>' . $cdata . '</td>';
            }
            $row = '<tr>' . $cell . '</tr>';
            $tbody .= $row;
        }
        return $tbody;
    }

    /**
     * Buduje tabelę HTML.
     *
     * @param array $data Dane do umieszczenia w tabeli.
     * @return string Gotowa tabela HTML.
     */
    public function build($data)
    {
        if ($this->title != null) {
            $tblCap = '<caption>' . $this->title . '</caption>';
        }
        $thead = $this->tblHeading($this->formStruct);
        $tbody = $this->tblData($data);

        $tbl = '<table>' . $tblCap . $thead . $tbody . '</table>';
        return $tbl;
    }
}

include('firma-krzak.php');
$tbl = new GenTable(null, 'Lista uczestników', 'persons3');
echo $tbl->build($dbData);
?>
