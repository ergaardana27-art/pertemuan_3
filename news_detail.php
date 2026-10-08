
<?php

require_once 'config/database.php';

// Validasi ID berita dari URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    http_response_code(400);
    exit('ID berita tidak valid.');
}


$stmt = $conn->prepare(
    "SELECT judul, isi, tanggal_publish
     FROM berita
     WHERE id = ?"
);

$stmt->bind_param('i', $id);
$stmt->execute();

$result = $stmt->get_result();
$news = $result->fetch_assoc();

// Periksa apakah berita ditemukan
if (!$news) {
    http_response_code(404);
    exit('Berita tidak ditemukan.');
}

// Atur judul halaman
$pageTitle = $news['judul'] . ' - Telkom University';

// Tampilkan header
require 'includes/header.php';
?>

<section class="section">
    <article class="container article-body">
        <span class="eyebrow">Detail Berita</span>

        <h1>
            <?= htmlspecialchars($news['judul']) ?>
        </h1>

        <p class="meta">
            <?= date('d M Y', strtotime($news['tanggal_publish'])) ?>
        </p>

        <p>
            <?= nl2br(htmlspecialchars($news['isi'])) ?>
        </p>

        <a class="btn btn-outline" href="news.php">
            Kembali ke berita
        </a>
    </article>
</section>

<?php require 'includes/footer.php'; ?>