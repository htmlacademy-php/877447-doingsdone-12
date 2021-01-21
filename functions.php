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

function get_date_diff($date)
{
  $cur_date = time();
  $quantity_seconds_in_hour = 3600;

  $task_date = strtotime($date);
  return floor(($task_date - $cur_date) / $quantity_seconds_in_hour);
};
