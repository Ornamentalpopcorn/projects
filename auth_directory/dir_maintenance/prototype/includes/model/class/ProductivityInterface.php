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
    public function displayResult($data_type, $md_code, $month, $query) ;
    // STEP 1
    // STEP 1

    // public function checkFetchData($id, $data_id, $data_parameter, $data_parameter_constraint, $data_value);
}


?>
