<section class="lot-item container">
    <h2><?= $lot_info['name'] ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="../<?= $lot_info['image'] ?>" width="730" height="548"
                     alt="<?= $lot_info['name'] ?>">
            </div>
            <p class="lot-item__category">Категория: <span><?= $lot_info['category_name'] ?></span>
            </p>
            <p class="lot-item__description"><?= $lot_info['description'] ?></p>
        </div>
        <div class="lot-item__right">
            <div class="lot-item__state">
                <div class="lot-item__timer timer <?php if(timeToEndLessOneHour($lot_info['time'])): ?>timer--finishing<?php endif; ?>">
                    <?= $lot_info['status'] ?>
                </div>
                <div class="lot-item__cost-state">
                    <div class="lot-item__rate">
                        <span class="lot-item__amount">Текущая цена</span>
                        <span class="lot-item__cost"><?= formatNumber($lot_info['price']) ?></span>
                    </div>
                    <?php
                    if (timeToEnd($lot_info['time']) !== false):
                    ?>
                    <div class="lot-item__min-cost">
                        Мин. ставка <span><?= formatNumber($lot_info['price']+$lot_info['bid_step']) ?></span>
                    </div>
                    <?php
                    endif;
                    ?>
                </div>
                <?php
                if ($show_bet_block !== false):
                ?>
                <form class="lot-item__form"
                      action="lot.php?id=<?= $lot_info['id'] ?>" method="post"
                      autocomplete="off">
                    <p
                      class="lot-item__form-item form__item <?php if ($error['cost']): ?> form__item--invalid <?php endif; ?>">
                        <label for="cost">Ваша ставка</label>
                        <input id="cost" type="text" name="cost"
                               placeholder="<?= formatNumber($lot_info['price']+$lot_info['bid_step']) ?>">
                        <span
                          class="form__error"><?= $error['cost'] ?></span>
                    </p>
                    <button type="submit" class="button">Сделать ставку</button>
                </form>
                <?php
                endif;
                ?>
            </div>
            <div class="history">
                <h3>История ставок (<span><?= count($bets)?></span>)</h3>
                <table class="history__list">
                    <?php foreach ($bets as $bet): ?>
                    <tr class="history__item">
                        <td class="history__name"><?= $bet['name'] ?></td>
                        <td class="history__price"><?= formatNumber($bet['amount']) ?></td>
                        <td class="history__time"><?= $timer->timeAfterBet($bet['creation_date']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</section>