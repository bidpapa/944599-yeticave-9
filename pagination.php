<?php if ($pages_count > 1): ?>
    <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev">
            <?php if ($lots): ?>
            <a <?php if ($cur_page > 1): ?>href="/category.php?id=<?= htmlspecialchars($_GET['id']); ?>&page=<?= $cur_page-1; ?>" <?php endif; ?>>
                Назад
            </a>
            <?php else: ?>
            <a <?php if ($cur_page > 1): ?>href="/search.php?search=<?= htmlspecialchars($_GET['search']); ?>&page=<?= $cur_page-1; ?>" <?php endif; ?>>
                Назад
            </a>
            <?php endif; ?>
        </li>
        <?php foreach ($pages as $page): ?>
            <li class="pagination-item <?php if ($page == $cur_page): ?>pagination-item-active<?php endif; ?>">
        <?php if ($lots): ?>
            <a href="/category.php?id=<?= htmlspecialchars($_GET['id']); ?>&page=<?= $page; ?>"><?= $page; ?></a>
        <?php else: ?>
            <a href="/search.php?search=<?= htmlspecialchars($_GET['search']); ?>&page=<?= $page; ?>"><?= $page; ?></a>
        <?php endif; ?>
            </li>
        <?php endforeach; ?>
        <li class="pagination-item pagination-item-next">
             <?php if ($lots): ?>
                <a <?php if ($cur_page < $pages_count): ?>href="/category.php?id=<?= htmlspecialchars($_GET['id']); ?>&page=<?= $cur_page+1; ?>" <?php endif; ?>>
                Вперед
                </a>
            <?php else: ?>
            <a <?php if ($cur_page < $pages_count): ?>href="/search.php?search=<?= htmlspecialchars($_GET['search']); ?>&page=<?= $cur_page+1; ?>" <?php endif; ?>>
                Вперед
            </a>
             <?php endif; ?>
        </li>
    </ul>
<?php endif; ?>