<?php

interface ProductivityInterface extends ProductivityCommandInterface
{
    public function computeResult();
    public function sourceData();

    public function fetchSalesData($id, $data_id, $sale_type);
    public function prepareData($source_id, $given_id, $sql);

    public function performEquation($equation);

    // public function checkFetchData($id, $data_id, $data_parameter, $data_parameter_constraint, $data_value);
}


?>
