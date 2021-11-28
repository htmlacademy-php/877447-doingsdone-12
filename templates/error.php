<div>
  <p class="text-error">
    <?php
    if (isset($_GET['submit-search'])) {
        print("Ничего не найдено по вашему запросу");
    } else {
        print("Здесь пока что нет задач.");
    }
    ?>
  </p>
</div>
