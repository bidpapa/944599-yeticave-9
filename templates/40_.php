<section class="lot-item container">
    <?php if ($response_code === 404): ?>
    <h2>Ошибка <?=  $response_code ?>. Страница не найдена</h2>
    <p>Данной страницы не существует на сайте.</p>
    <?php endif; ?>
    <?php if ($response_code === 403): ?>
        <h2>Ошибка <?=  $response_code ?>. У вас нет доступа к данной странице</h2>
        <p>Залогиньтесь или зарегистрируйтесь, чтобы добавить лот!</p>
    <?php endif; ?>
</section>