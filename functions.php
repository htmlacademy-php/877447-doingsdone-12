<?php
function get_tasks_summ($array, $title)
{
    $summ_tasks = 0;
    foreach ($array as $item) {
        if ($item['category'] == $title) {
            $summ_tasks++;
        }
    }
    return $summ_tasks;
};

$cur_date = time();
$quantity_seconds_in_hour = 3600;
$quantity_hours_in_day = 24;
function get_date_diff($date)
{
    if ($date !== null) {
        $task_date = strtotime($data);
        $diff = floor(($task_date - $cur_date) / $quantity_seconds_in_hour);
    }
    return $diff;
};
