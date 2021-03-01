    <div class="content">
      <section class="content__side">
        <h2 class="content__side-heading">Проекты</h2>

        <nav class="main-navigation">
          <ul class="main-navigation__list">
            <li class="main-navigation__list-item">
              <a class="main-navigation__list-item-link" href="#">Входящие</a>
              <span class="main-navigation__list-item-count">24</span>
            </li>

            <li class="main-navigation__list-item main-navigation__list-item--active">
              <a class="main-navigation__list-item-link" href="#">Работа</a>
              <span class="main-navigation__list-item-count">12</span>
            </li>

            <li class="main-navigation__list-item">
              <a class="main-navigation__list-item-link" href="#">Здоровье</a>
              <span class="main-navigation__list-item-count">3</span>
            </li>

            <li class="main-navigation__list-item">
              <a class="main-navigation__list-item-link" href="#">Домашние дела</a>
              <span class="main-navigation__list-item-count">7</span>
            </li>

            <li class="main-navigation__list-item">
              <a class="main-navigation__list-item-link" href="#">Авто</a>
              <span class="main-navigation__list-item-count">0</span>
            </li>
          </ul>
        </nav>

        <a class="button button--transparent button--plus content__side-button" href="form-project.html">Добавить проект</a>
      </section>

      <main class="content__main">
        <h2 class="content__main-heading">Добавление задачи</h2>

        <form class="form"  action="add.php" method="post" autocomplete="off" enctype="multipart/form-data">
          <div class="form__row">
            <label class="form__label" for="name">Название <sup>*</sup></label>

            <input class="form__input <?php isset($errors['name']) ? print 'form__input--error' : print ''; ?>" type="text" name="name" id="name" value="<? htmlspecialchars(getPostVal('name')); ?>" placeholder="Введите название">
            <p class="form__message"><?= $errors['name'] ?></p>
          </div>

          <div class="form__row">
            <label class="form__label" for="project">Проект <sup>*</sup></label>

            <select class="form__input form__input--select <?php isset($errors['project']) ? print 'form__input--error' : print ''; ?>" name="project" id="project">
              <?php foreach ($projects as $project) : ?>
                <option value="<?= $project['id'] ?>"><?= $project['project_title'] ?></option>
              <?php endforeach; ?>
            </select>
            <p class="form__message"><?= $errors['project'] ?></p>
          </div>

          <div class="form__row">
            <label class="form__label" for="date">Дата выполнения</label>

            <input class="form__input form__input--date <?php isset($errors['date']) ? print 'form__input--error' : print ''; ?>" type="text" name="date" id="date" value="<? getPostVal('date'); ?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
            <p class="form__message"><?= $errors['date'] ?></p>
          </div>

          <div class="form__row">
            <label class="form__label" for="file">Файл</label>

            <div class="form__input-file">
              <input class="visually-hidden" type="file" name="file" id="file" value="">

              <label class="button button--transparent" for="file">
                <span>Выберите файл</span>
              </label>
            </div>
          </div>

          <div class="form__row form__row--controls">
            <input class="button" type="submit" name="submit" value="Добавить">
          </div>
        </form>
      </main>
    </div>
  </div>
</div>

