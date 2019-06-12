<div class="container">
    <section class="lots">
        <h2>Результаты поиска по запросу «<span><?= htmlspecialchars($_GET['search']) ?></span>»</h2>
        <?php if ($lots): ?>
        <ul class="lots__list">
            <?php foreach ($lots as $lot): ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?= $lot['image'] ?>" width="350" height="260" alt="<?= htmlspecialchars($lot['name']) ?>">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?= $lot['category'] ?></span>
                    <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?= $lot['id'] ?>"><?= htmlspecialchars($lot['name']) ?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?= $lot['start_price'] ?><b class="rub">р</b></span>
                        </div>
                        <div class="lot__timer timer <?php if(timeToEndLessOneHour($lot['time'])): ?>timer--finishing<?php endif; ?>">
                            <?php if(!timeToEnd($lot['end_date'])): ?>
                            Окончен
                            <?php else: ?>
                                <?= timeToEnd($lot['end_date']); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </li>
            <?php endforeach;?>
        </ul>
        <?php else: ?>
        Ничего не найдено по вашему запросу.
        <?php  endif; ?>
    </section>
    <?= include_template('../pagination.php', [
      'pages' => $pages,
      'pages_count' => $pages_count,
      'cur_page' => $cur_page
    ]); ?>
</div>