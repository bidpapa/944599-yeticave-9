<form class="form container <?php if(count($error) > 0):?>form--invalid<?php endif; ?>" action="login.php" method="post">
    <h2>Вход</h2>
    <div class="form__item <?php if (isset($error['email'])): ?> form__item--invalid <?php endif; ?>">
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" <?php if($login):?>value="<?= htmlspecialchars($login['email']) ?>"<?php endif; ?>>
        <span class="form__error"><?= $error['email'] ?></span>
    </div>
    <div class="form__item form__item--last <?php if (isset($error['password'])): ?> form__item--invalid <?php endif; ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль">
        <span class="form__error"><?= $error['password'] ?></span>
    </div>
    <button type="submit" class="button">Войти</button>
</form>