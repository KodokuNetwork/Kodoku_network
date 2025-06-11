<?php
require_once 'config/database.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$limit = 4;

$query = $newsConn->prepare("SELECT * FROM news ORDER BY created_at DESC LIMIT ? OFFSET ?");
$query->bind_param("ii", $limit, $offset);
$query->execute();
$result = $query->get_result();

$items = [];

while ($row = $result->fetch_assoc()) {
    ob_start(); ?>
    <div class="col-12 col-md-6 col-lg-6">
      <div class="kodoku-new-card text-white text-center">
        <img src="<?= !empty($row['image']) ? 'assets/images/news/' . htmlspecialchars($row['image']) : 'assets/images/placeholder.png' ?>"
             class="news-img mb-3" alt="News Image">
        <h5 class="kodoku-card-title fw-bold text-break px-2 mt-0 mb-3"><?= htmlspecialchars($row['title']) ?></h5>
        <a href="news_detail.php?id=<?= $row['id'] ?>" class="btn btn-outline-secondary btn-sm">Baca Selengkapnya</a>
      </div>
    </div>
    <?php $items[] = ob_get_clean();
}

echo json_encode($items);

