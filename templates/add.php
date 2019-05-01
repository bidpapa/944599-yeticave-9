    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($categories as $category): ?>
                <li class="nav__item">
                    <a href="all-lots.html"><?= $category[1] ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <form enctype="multipart/form-data" class="form form--add-lot container <?php if(count($error) > 0):?>form--invalid<?php endif; ?>" action="add.php" method="post"> <!-- form--invalid -->
        <h2>Добавление лота</h2>
        <div class="form__container-two">
            <div class="form__item <?php if($error['lot-name']):?> form__item--invalid <?php endif; ?>"> <!-- form__item--invalid -->
                <label for="lot-name">Наименование <sup>*</sup></label>
                <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value="<?= $lot['lot-name'] ?>">
                <span class="form__error"><?= $error['lot-name'] ?></span>
            </div>
            <div class="form__item <?php if($error['category']):?>form__item--invalid<?php endif; ?>">
                <label for="category">Категория <sup>*</sup></label>
                <select id="category" name="category">
                    <option value="">Выберите категорию</option>
                    <?= createSelectList($categories, $lot['category']); ?>
                </select>
                <span class="form__error"><?= $error['category'] ?></span>
            </div>
        </div>
        <div class="form__item form__item--wide <?php if($error['message']):?>form__item--invalid<?php endif; ?>">
            <label for="message">Описание <sup>*</sup></label>
            <textarea id="message" name="message" placeholder="Напишите описание лота"><?= $lot['message'] ?></textarea>
            <span class="form__error"><?= $error['message'] ?></span>
        </div>
        <div class="form__item form__item--file">
            <label>Изображение <sup>*</sup></label>
            <div class="form__input-file <?php if($error['lot-img']):?>form__item--invalid<?php endif; ?>">
                <input class="visually-hidden" type="file" id="lot-img" name="lot-img" value="">
                <label for="lot-img">
                    Добавить
                </label>
                <span class="form__error"><?= $error['lot-img'] ?></span>
            </div>
        </div>
        <div class="form__container-three">
            <div class="form__item form__item--small <?php if($error['lot-rate']):?>form__item--invalid<?php endif; ?>">
                <label for="lot-rate">Начальная цена <sup>*</sup></label>
                <input id="lot-rate" type="text" name="lot-rate" placeholder="0" value="<?= $lot['lot-rate'] ?>">
                <span class="form__error"><?= $error['lot-rate'] ?></span>
            </div>
            <div class="form__item form__item--small <?php if($error['lot-step']):?>form__item--invalid<?php endif; ?>">
                <label for="lot-step">Шаг ставки <sup>*</sup></label>
                <input id="lot-step" type="text" name="lot-step" placeholder="0" value="<?= $lot['lot-step'] ?>">
                <span class="form__error"><?= $error['lot-step'] ?></span>
            </div>
            <div class="form__item <?php if($error['lot-date']):?>form__item--invalid<?php endif; ?>">
                <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
                <input class="form__input-date" id="lot-date" type="text" name="lot-date" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?= $lot['lot-date'] ?>">
                <span class="form__error"><?= $error['lot-date'] ?></span>
            </div>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Добавить лот</button>
    </form>
