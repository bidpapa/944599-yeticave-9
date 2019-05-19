<?php if ($pages_count > 1): ?>
    <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev">
            <a <?php if ($cur_page > 1): ?>href="/search.php?search=<?= htmlspecialchars($_GET['search']); ?>&page=<?= $cur_page-1; ?>" <?php endif; ?>>
                Назад
            </a>
        </li>
        <?php foreach ($pages as $page): ?>
            <li class="pagination-item <?php if ($page == $cur_page): ?>pagination-item-active<?php endif; ?>">
                <a href="/search.php?search=<?= htmlspecialchars($_GET['search']); ?>&page=<?= $page; ?>"><?= $page; ?></a>
            </li>
        <?php endforeach; ?>
        <li class="pagination-item pagination-item-next">
            <a <?php if ($cur_page < $pages_count): ?>href="/search.php?search=<?= htmlspecialchars($_GET['search']); ?>&page=<?= $cur_page+1; ?>" <?php endif; ?>>
                Вперед
            </a>
        </li>
    </ul>
<?php endif; ?>