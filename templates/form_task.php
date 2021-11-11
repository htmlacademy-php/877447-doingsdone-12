<div class="content">
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

    <a class="button button--transparent button--plus content__side-button" href="add_project.php">Добавить проект</a>
  </section>

  <main class="content__main">
    <h2 class="content__main-heading">Добавление задачи</h2>

    <form class="form" action="add_task.php" method="post" autocomplete="off" enctype="multipart/form-data">
      <div class="form__row">
        <label class="form__label" for="name">Название <sup>*</sup></label>

        <input class="form__input <?php isset($errors['name']) ? print 'form__input--error' : print ''; ?>" type="text" name="name" id="name" value="<?= htmlspecialchars(getPostVal('name')); ?>" placeholder="Введите название">
        <p class="form__message"><?= $errors['name'] ?></p>
      </div>

      <div class="form__row">
        <label class="form__label" for="project">Проект <sup>*</sup></label>

        <select class="form__input form__input--select <?php isset($errors['project']) ? print 'form__input--error' : print ''; ?>" name="project" id="project">
          <?php foreach ($projects as $project) : ?>
            <option value=<?= $project['id']; ?> <?= (int)getPostVal('project') === (int)$project['id'] ? "selected" : ""; ?>><?= htmlspecialchars($project['project_title']); ?></option>
          <?php endforeach; ?>
        </select>
        <p class="form__message"><?= $errors['project'] ?></p>
      </div>

      <div class="form__row">
        <label class="form__label" for="date">Дата выполнения</label>

        <input class="form__input form__input--date <?php isset($errors['date']) ? print 'form__input--error' : print ''; ?>" type="text" name="date" id="date" value="<?= htmlspecialchars(getPostVal('date')); ?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
        <p class="form__message"><?= $errors['date'] ?></p>
      </div>

      <div class="form__row">
        <label class="form__label" for="file">Файл</label>

        <div class="form__input-file">
          <input class="visually-hidden" type="file" name="file" id="file" value="<?= isset($_POST['file']) ? ($_POST['file']) : '';  ?>">

          <label class="button button--transparent <?php isset($errors['file']) ? print 'form__input--error' : print ''; ?>" for="file">
            <span>Выберите файл</span>
          </label>

          <p class="form__file-name">
            <?= isset($_FILES['file']) ? ($_FILES['file']['name']) : '';  ?>
          </p>
          <p class="form__message"><?= $errors['file'] ?></p>

        </div>
      </div>

      <div class="form__row form__row--controls">
        <input class="button" type="submit" name="submit" value="Добавить">
      </div>
    </form>
    <p class="error-message"><?php !empty($errors) ? print 'Пожалуйста, исправьте ошибки в форме' : ''; ?></p>
  </main>
</div>
</div>
</div>
