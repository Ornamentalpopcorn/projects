<?php

interface ProductivityInterface extends ProductivityCommandInterface
{
    public function computeResult();
    public function sourceData();

    public function fetchSalesData($id, $data_id, $sale_type);
    public function prepareData($source_id, $given_id, $sql);

    public function performEquation($equation);

    // STEP 1
    // STEP 1
    public function displayResult($md_code, $month, $query) ;
    // STEP 1
    // STEP 1

    //STEP 3
    public function applyToReport();

    // STEP 4
    public function translateQuery($query_placement, $sql); 
    public function getSetSales($query_placement, $sub_query_placement, $source_id, $sql);
    public function performComputation($syntax_to_perform);

    // public function getSubSetSales($query_placement, $sql);
    // STEP 4

    // public function checkFetchData($id, $data_id, $data_parameter, $data_parameter_constraint, $data_value);
}


?>
