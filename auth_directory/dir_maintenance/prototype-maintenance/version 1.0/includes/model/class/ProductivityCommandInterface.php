<?php

interface ProductivityCommandInterface
{
    public function querySelect($sql);
    public function displaySourceList();
    public function checkStepsList();

    public function checkReferenceToUseToGetLBA($lba_rebate_code);
    public function buildQuery($source_id, $id, $data_id, $sale_type, $crediting_date);
    public function filterText($text);
    public function selectDataSourcesList();

    public function insertSource();
    public function deleteSource();
    public function checkQuerySyntax($sqlText, $sql);
    public function selectReferenceList($parameter);

    public function createNewSource();




}


?>
