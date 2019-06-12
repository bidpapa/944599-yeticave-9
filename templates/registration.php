<form class="form container <?php if(count($error) > 0):?>form--invalid<?php endif; ?>" action="registration.php" method="post" autocomplete="off"> <!-- form--invalid -->
    <h2>Регистрация нового аккаунта</h2>
    <div class="form__item <?php if(isset($error['email'])):?> form__item--invalid <?php endif; ?>"> <!-- form__item--invalid -->
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" <?php if($registration):?>value="<?= htmlspecialchars($registration['email']) ?>"<?php endif; ?>>
        <span class="form__error"><?= $error['email'] ?></span>
    </div>
    <div class="form__item <?php if(isset($error['password'])):?> form__item--invalid <?php endif; ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль" value="">
        <span class="form__error"><?= $error['password'] ?></span>
    </div>
    <div class="form__item <?php if(isset($error['name'])):?> form__item--invalid <?php endif; ?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" placeholder="Введите имя" <?php if($registration):?>value="<?= htmlspecialchars($registration['name']) ?>"<?php endif; ?>>
        <span class="form__error"><?= $error['name'] ?></span>
    </div>
    <div class="form__item <?php if(isset($error['message'])):?> form__item--invalid <?php endif; ?>">
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите как с вами связаться"><?php if($registration):?><?= htmlspecialchars($registration['message']) ?><?php endif; ?></textarea>
        <span class="form__error"><?= $error['message'] ?></span>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="login.php">Уже есть аккаунт</a>
</form>