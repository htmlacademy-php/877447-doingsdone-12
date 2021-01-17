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
