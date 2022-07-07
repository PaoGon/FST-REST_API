<?php
    function calc_date($start_date, $end_date){
        $interval = date_diff(date_create($start_date), date_create($end_date));

        if($interval != false){
            return (int)$interval->format("%a");
        }
        return 0;
    }
?>
