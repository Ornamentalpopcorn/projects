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
    // public function deleteSource();
    public function checkQuerySyntax($sqlText, $sql);
    public function selectReferenceList($parameter);

    public function createNewSource($auth_code);

    // NOTE: VERSION 2.0 functions created below are created after version 1.0

    public function addToTxtFile($auth_code);
    public function searchPhrase();
    public function formatPhrase();

    // NOTE: STEP 3

    public function saveSource();
    public function displaySourceInfo();

    public function getSalesSource();

    public function getSourceName($sql);

    public function filterSourceEquivalent($source);
    public function fetchSourceEquivalent($source_to_test);
    public function ifPerformQuery();



}


?>
