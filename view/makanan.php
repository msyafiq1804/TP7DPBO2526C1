<?php
require_once '../config/db.php';
require_once '../class/Makanan.php';
require_once '../class/Kategori.php';
require_once '../class/Restoran.php';

$database = new Database();
$db = $database->getConnection();

$makanan = new Makanan($db);
$kategori = new Kategori($db);
$restoran = new Restoran($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $makanan->create($_POST['nama_makanan'], $_POST['kategori_id'], $_POST['restoran_id'], $_POST['kalori'], $_POST['harga']);
    } elseif (isset($_POST['update'])) {
        $makanan->update($_POST['id'], $_POST['nama_makanan'], $_POST['kategori_id'], $_POST['restoran_id'], $_POST['kalori'], $_POST['harga']);
    } elseif (isset($_POST['delete'])) {
        $makanan->delete($_POST['id']);
    }
    header("Location: makanan.php");
    exit();
}

$makananList = $makanan->read();
$kategoriList = $kategori->read();
$restoranList = $restoran->read();

$isEdit = false;
$editData = null;
if (isset($_GET['edit'])) {
    $isEdit = true;
    $id_edit = $_GET['edit'];
    foreach ($makananList as $m) {
        if ($m['id'] == $id_edit) {
            $editData = $m;
            break;
        }
    }
}

include 'header.php';
?>

<h2><?= $isEdit ? 'Update Menu Makanan' : 'Tambah Menu Makanan Baru' ?></h2>

<form method="POST" action="makanan.php">
    <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= $editData['id'] ?>">
    <?php endif; ?>

    <input type="text" name="nama_makanan" placeholder="Nama Menu Makanan" 
           value="<?= $isEdit ? htmlspecialchars($editData['nama_makanan']) : '' ?>" required>
    
    <select name="kategori_id" required>
        <option value="">-- Pilih Kategori --</option>
        <?php foreach ($kategoriList as $k): ?>
            <option value="<?= $k['id'] ?>" 
                <?= ($isEdit && $editData['kategori_id'] == $k['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($k['nama_kategori']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="restoran_id" required>
        <option value="">-- Pilih Tempat Makan --</option>
        <?php foreach ($restoranList as $r): ?>
            <option value="<?= $r['id'] ?>" 
                <?= ($isEdit && $editData['restoran_id'] == $r['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($r['nama_restoran']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input type="number" name="kalori" placeholder="Kalori (kcal)" 
           value="<?= $isEdit ? $editData['kalori'] : '' ?>" required>
    <input type="number" name="harga" placeholder="Harga (Rp)" 
           value="<?= $isEdit ? $editData['harga'] : '' ?>" required>

    <?php if ($isEdit): ?>
        <button type="submit" name="update" style="background-color: #f39c12;">Update</button>
        <a href="makanan.php" style="padding: 10px 20px; background-color: #95a5a6; color: white; text-decoration: none; border-radius: 4px;">Batal</a>
    <?php else: ?>
        <button type="submit" name="create">Tambah Menu</button>
    <?php endif; ?>
</form>

<br>
<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Menu Makanan</th>
        <th>Kategori</th>
        <th>Tempat Makan</th>
        <th>Kalori (kcal)</th>
        <th>Harga (Rp)</th>
        <th>Aksi</th>
    </tr>
    <?php foreach ($makananList as $row): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['nama_makanan']) ?></td>
        <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
        <td><?= htmlspecialchars($row['nama_restoran']) ?></td>
        <td><?= $row['kalori'] ?></td>
        <td><?= number_format($row['harga'], 0, ',', '.') ?></td>
        
        <td style="display: flex; gap: 5px;">
            <a href="makanan.php?edit=<?= $row['id'] ?>">
                <button type="button" style="background-color: #f39c12; padding: 5px 10px;">Update</button>
            </a>
            
            <form method="POST" style="margin: 0; padding: 0; box-shadow: none; background: transparent; width: auto;">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" name="delete" onclick="return confirm('Hapus menu makanan?')" style="padding: 5px 10px;">Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php include 'footer.php'; ?>