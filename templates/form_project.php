<div class="content">
  <section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>

    <nav class="main-navigation">
      <ul class="main-navigation__list">
        <?php foreach ($projects as $project) : ?>
          <li class="main-navigation__list-item <?= ($project['id'] === ($_GET['project_id'] ?? '')) ? 'main-navigation__list-item--active' : '' ?>">
            <a class="main-navigation__list-item-link" href="/index.php?project_id=<?= $project['id']; ?>"><?= htmlspecialchars($project['project_title']); ?></a>
            <span class="main-navigation__list-item-count"><?= $project['c_tasks'] ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <a class="button button--transparent button--plus content__side-button" href="add_project.php">Добавить проект</a>
  </section>

  <main class="content__main">
    <h2 class="content__main-heading">Добавление проекта</h2>

    <form class="form" action="add_project.php" method="post" autocomplete="off">
      <div class="form__row">
        <label class="form__label" for="project_name">Название <sup>*</sup></label>

        <input class="form__input <?php isset($errors['name']) ? print 'form__input--error' : print ''; ?>" type="text" name="name" id="project_name" value="<?= htmlspecialchars(getPostVal('name')); ?>" placeholder="Введите название проекта">
        <p class="form__message"><?= $errors['name'] ?? ''; ?></p>
      </div>

      <div class="form__row form__row--controls">
        <input class="button" type="submit" name="submit" value="Добавить">
      </div>
    </form>
  </main>
</div>
