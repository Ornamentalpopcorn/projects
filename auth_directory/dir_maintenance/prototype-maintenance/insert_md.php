<?Php

$sql = "SET @crediting_date = '2018-01-01';";
$sql .= " INSERT INTO md_profile_list_by_class
(
  md_name,
  md_code,
  status,
  lba_rebate_code,
  crediting_date
)
SELECT CASE WHEN md_name = 'N/A' THEN old_name ELSE md_name END ,
       md_code,
       status,
       lba_rebate,
       @crediting_date
FROM md_profile_area_list
WHERE month = 'January'
AND year = '2018'
AND status = 'NON-PROS'
GROUP BY lba_rebate, md_code
            UNION
SELECT CASE WHEN md_name = 'N/A' THEN old_name ELSE md_name END ,
       md_code,
       status,
       lba_rebate,
       @crediting_date
FROM md_profile_area_list
WHERE month = 'January'
AND year = '2018'
AND status IN ('JEDI', 'PADAWAN', 'IPG')
GROUP BY lba_rebate, md_code
            UNION
SELECT CASE WHEN md_name = 'N/A' THEN old_name ELSE md_name END ,
       md_code,
       status,
       lba_rebate,
       @crediting_date
FROM md_profile_area_list
WHERE month = 'January'
AND year = '2018'
AND status IN ('PROS')
GROUP BY lba_rebate, md_code
";

$sql = "INSERT INTO md_profile_list_by_class
(
  md_name,
  md_code,
  status,
  lba_rebate_code,
  crediting_date
)
SELECT md_name  ,
       md_code,
       status,
       @crediting_date
FROM salesdata_senior_precomputed_by_branch
WHERE month = 'January'
AND year = '2018'
AND md_name LIKE '%,%'
AND md_code NOT IN (
  SELECT DISTINCT md_code
  FROM md_profile_area_list
  WHERE month = 'January'
  AND year = '2018'
)
GROUP BY lba_rebate_code, md_name";

$sql = "INSERT INTO md_profile_list_by_class
(
  md_name,
  md_code,
  status,
  lba_rebate_code,
  crediting_date
)
SELECT md_name,
       md_code,
       status,
       @crediting_date
FROM salesdata_senior_precomputed_by_branch
WHERE month = 'January'
AND year = '2018'
AND md_name NOT LIKE '%,%'
AND md_code NOT IN (
  SELECT DISTINCT md_code
  FROM md_profile_area_list
  WHERE month = 'January'
  AND year = '2018'
)
GROUP BY lba_rebate_code, md_name";

$sql = "INSERT INTO md_profile_list_by_class
(
  md_name,
  md_code,
  status,
  lba_rebate_code,
  crediting_date
)
SELECT md_name,
       md_code,
       status,
       @crediting_date
FROM salesdata_senior_precomputed_by_branch
WHERE month = 'January'
AND year = '2018'
AND md_name LIKE '%,%'
AND md_code NOT IN (
      SELECT DISTINCT md_code
      FROM md_profile_area_list
      WHERE month = 'January'
      AND year = '2018'
)
GROUP BY lba_rebate_code, md_name";

?>
