<?php
function mysqli_result($result, $row, $field=0)
{
   mysqli_data_seek($result, $row);
   return mysqli_fetch_array($result)[$field];
}
?>