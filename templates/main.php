  <section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>

    <nav class="main-navigation">
      <ul class="main-navigation__list">
        <?php foreach ($projects as $project) : ?>
          <li class="main-navigation__list-item <?= ($project['id'] == $_GET['project_id']) ? 'main-navigation__list-item--active' : '' ?>">
            <a class="main-navigation__list-item-link" href="/index.php?project_id=<?= $project['id']; ?>"><?= htmlspecialchars($project['project_title']); ?></a>
            <span class="main-navigation__list-item-count"><?= $project['c_tasks'] ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <a class="button button--transparent button--plus content__side-button" href="pages/form-project.html" target="project_add">Добавить проект</a>
  </section>

  <main class="content__main">
    <h2 class="content__main-heading">Список задач</h2>

    <form class="search-form" action="index.php" method="post" autocomplete="off">
      <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

      <input class="search-form__submit" type="submit" name="" value="Искать">
    </form>

    <div class="tasks-controls">
      <nav class="tasks-switch">
        <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
        <a href="/" class="tasks-switch__item">Повестка дня</a>
        <a href="/" class="tasks-switch__item">Завтра</a>
        <a href="/" class="tasks-switch__item">Просроченные</a>
      </nav>

      <label class="checkbox">
        <!--добавить сюда атрибут "checked", если переменная $show_complete_tasks равна единице-->
        <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?php if ($show_complete_tasks == 1) : ?> checked <?php endif; ?>>

        <span class="checkbox__text">Показывать выполненные</span>
      </label>
    </div>

    <table class="tasks">
      <?php
      if (count($tasks) === 0) {
        http_response_code(404);
        print ($error_template);
      } else {
      foreach ($tasks as $task) {
        if ($task['task_status'] == true && $show_complete_tasks == 0) {
          continue;
        }
      ?>
        <tr class="tasks__item task <? echo (intval($task['task_status']) === 1 && $show_complete_tasks == 1) ? 'task--completed' : ''  ?>
        <? echo (intval($task['task_status']) !== 1 && $task['date_deadline'] !== null && get_date_diff($task['date_deadline']) <= $quantity_hours_in_day) ? 'task--important' : '' ?>">

          <td class="task__select">
            <label class="checkbox task__checkbox">
              <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="1">
              <span class="checkbox__text"><?= htmlspecialchars($task['task_title']); ?></span>
            </label>
          </td>

          <td class="task__file">
          <?php if (!empty($task['file'])):?>
          <a class="download-link" href="<?=$task['file']?>"><?= substr($task['file'], 9); ?></a>
          <?php endif; ?>
          </td>
          <td class="task__date"><?= htmlspecialchars($task['date_deadline']); ?></td>
        </tr>
      <?php }
      } ?>
      <!--показывать следующий тег <tr/>, если переменная $show_complete_tasks равна единице-->
    <?php if ($show_complete_tasks == 1) : ?>
        <!-- <tr class="tasks__item task task--completed">
          <td class="task__select">
            <label class="checkbox task__checkbox">
              <input class="checkbox__input visually-hidden" type="checkbox" checked>
              <span class="checkbox__text">Записаться на интенсив "Базовый PHP"</span>
            </label>
          </td>
          <td class="task__date">10.10.2019</td>
          <td class="task__controls"></td>
        </tr> -->
      <?php endif; ?>
    </table>
  </main>
