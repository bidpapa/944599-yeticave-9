<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
            <li class="nav__item">
                <a href="all-lots.html"><?= $category[1] ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<section class="lot-item container">
    <h2><?=  $response_code ?> Страница не найдена</h2>
    <p>Данной страницы не существует на сайте.</p>
</section>