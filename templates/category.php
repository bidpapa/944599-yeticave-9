<div class="container">
    <section class="lots">
        <?php if ($lots): ?><h2>Все лоты в категории <span><?= $lots[0]['category'] ?></span></h2><?php  endif; ?>
        <?php if ($lots): ?>
        <ul class="lots__list">
            <?php foreach ($lots as $lot): ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?= $lot['image'] ?>" width="350" height="260" alt="<?= $lot['name'] ?>">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?= $lot['category'] ?></span>
                    <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?= $lot['id_lot'] ?>"><?= $lot['name'] ?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?= $lot['start_price'] ?><b class="rub">р</b></span>
                        </div>
                        <div class="lot__timer timer <?php if(timeToEndLessOneHour($lot['end_date'])): ?>timer--finishing<?php endif; ?>">
                            <?= timeToEnd($lot['end_date']) ?>
                        </div>
                    </div>
                </div>
            </li>
            <?php endforeach;?>
        </ul>
        <?php else: ?>
            Активных лотов в данной категории нет.
        <?php  endif; ?>
    </section>
    <?= include_template('../pagination.php', [
      'lots' => $lots,
      'pages' => $pages,
      'pages_count' => $pages_count,
      'cur_page' => $cur_page
    ]); ?>
</div>