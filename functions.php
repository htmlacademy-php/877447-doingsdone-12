<?php
function get_tasks_summ($array, $title)
{
  $summ_tasks = 0;
  foreach ($array as $item) {
    if ($item['from_project'] == $title) {
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

//  получаем значения из POST-запроса.
function getPostVal($name) {
    return $_POST[$name] ?? "";
}
