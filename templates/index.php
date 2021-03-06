<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <?php
        foreach ($categories as $category):
            ?>
            <li class="promo__item promo__item--<?= $category[2] ?>">
                <a class="promo__link" href="category.php?id=<?= $category[0] ?>"><?= $category[1] ?></a>
            </li>
        <?php
        endforeach;
        ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <?php
        foreach ($adverts as $advert => $key):
            ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?= htmlspecialchars($key['url']) ?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?= htmlspecialchars($key['category']) ?></span>
                    <h3 class="lot__title">
                        <a class="text-link" href="lot.php?id=<?= $key['id'] ?>"><?= htmlspecialchars($key['name']) ?></a>
                    </h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?= formatNumber($key['price']) ?></span>
                        </div>
                        <div class="lot__timer timer <?php if(timeToEndLessOneHour($key['time'])): ?>timer--finishing<?php endif; ?>">
                        <?= timeToEnd($key['time']) ?>
                        </div>
                    </div>
                </div>
            </li>
        <?php
        endforeach;
        ?>
    </ul>
</section>