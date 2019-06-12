<section class="rates container">
    <h2>Мои ставки</h2>
    <?php if(!$bids): ?><span>Вы еще не делали ставок на сайте</span><?php endif; ?>
    <table class="rates__list">
        <?php foreach ($bids as $bid): ?>
        <tr class="rates__item <?php if(!timeToEnd($bid['end_date']) && isWinningBid($bid['amount'], $bid['max'])): ?>rates__item--win<?php elseif(!timeToEnd($bid['end_date'])): ?>rates__item--end<?php endif; ?>">
            <td class="rates__info">
                <div class="rates__img">
                    <img src="../<?= $bid['image'] ?>" width="54" height="40" alt="<?= $bid['lot_name'] ?>">
                </div>
                <div>
                <h3 class="rates__title"><a href="lot.php?id=<?= $bid['id'] ?>"><?= $bid['lot_name'] ?></a></h3>
                <?php if (!timeToEnd($bid['end_date']) && isWinningBid($bid['amount'], $bid['max'])): ?><p><?= $bid['contact'] ?></p><? endif; ?>
                </div>
            </td>
            <td class="rates__category">
                <?= $bid['category_name'] ?>
            </td>
            <td class="rates__timer">
                <?php if(!timeToEnd($bid['end_date']) && isWinningBid($bid['amount'], $bid['max'])): ?>
                <div class="timer timer--win">Ставка выиграла</div>
                <?php elseif(!timeToEnd($bid['end_date'])): ?>
                <div class="timer timer--end">Торги окончены</div>
                <?php else: ?>
                <div class="timer <?php if(timeToEndLessOneHour($bid['end_date'])): ?>timer--finishing<?php endif; ?>">
                    <?= timeToEnd($bid['end_date']) ?>
                </div>
                <?php endif; ?>
            </td>
            <td class="rates__price">
                <?= $bid['amount'] ?> р
            </td>
            <td class="rates__time">
                <?= $timer->timeAfterBet($bid['creation_date']) ?>
            </td>
        </tr>
        <? endforeach; ?>
    </table>
</section>