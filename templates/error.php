<div>
  <p class="text-error">
    <?php
    if (isset($_GET['submit-search'])) {
        print("Ничего не найдено по вашему запросу");
    } else {
        print("Ошибка 404. Страница не найдена.");
    }
    ?>

  </p>
</div>
