<?php
require_once '../config/db.php';
require_once '../class/Kategori.php';

$database = new Database();
$db =$database->getConnection();
$kategori = new Kategori($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {$kategori->create($_POST['nama_kategori'],$_POST['deskripsi']);
    } elseif (isset($_POST['update'])) {
        $kategori->update($_POST['id'], $_POST['nama_kategori'],$_POST['deskripsi']);
    } elseif (isset($_POST['delete'])) {
        $kategori->delete($_POST['id']);
    }
    header("Location: kategori.php");
    exit();
}

$kategoriList =$kategori->read();

$isEdit = false;
$editData = null;
if (isset($_GET['edit'])) {$isEdit = true;
    $id_edit =$_GET['edit'];
    foreach ($kategoriList as$k) {
        if ($k['id'] ==$id_edit) {
            $editData =$k;
            break;
        }
    }
}

include 'header.php';
?>

<h2><?= $isEdit ? 'Update Kategori' : 'Manajemen Kategori Makanan Sehat' ?></h2>

<form method="POST" action="kategori.php">
    <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= $editData['id'] ?>">
    <?php endif; ?>

    <input type="text" name="nama_kategori" placeholder="Nama Kategori (misal: Salad)" 
           value="<?= $isEdit ? htmlspecialchars($editData['nama_kategori']) : '' ?>" required>
    
    <input type="text" name="deskripsi" placeholder="Deskripsi Singkat" 
           value="<?= $isEdit ? htmlspecialchars($editData['deskripsi']) : '' ?>" required>
    
    <?php if ($isEdit): ?>
        <button type="submit" name="update" style="background-color: #f39c12;">Update</button>
        <a href="kategori.php" style="padding: 10px 20px; background-color: #95a5a6; color: white; text-decoration: none; border-radius: 4px;">Batal</a>
    <?php else: ?>
        <button type="submit" name="create">Tambah Kategori</button>
    <?php endif; ?>
</form>

<br>
<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Nama Kategori</th>
        <th>Deskripsi</th>
        <th>Aksi</th>
    </tr>
    <?php foreach ($kategoriList as$row): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
        <td><?= htmlspecialchars($row['deskripsi']) ?></td>
        
        <td style="display: flex; gap: 5px;">
            <a href="kategori.php?edit=<?= $row['id'] ?>">
                <button type="button" style="background-color: #f39c12; padding: 5px 10px;">Update</button>
            </a>
            
            <form method="POST" style="margin: 0; padding: 0; box-shadow: none; background: transparent; width: auto;">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" name="delete" onclick="return confirm('Hapus kategori ini?')" style="padding: 5px 10px;">Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php include 'footer.php'; ?>