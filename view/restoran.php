<?php
require_once '../config/db.php';
require_once '../class/Restoran.php';

$database = new Database();
$db = $database->getConnection();
$restoran = new Restoran($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $restoran->create($_POST['nama_restoran'], $_POST['alamat'], $_POST['jam_operasional']);
    } elseif (isset($_POST['update'])) {
        $restoran->update($_POST['id'], $_POST['nama_restoran'], $_POST['alamat'], $_POST['jam_operasional']);
    } elseif (isset($_POST['delete'])) {
        $restoran->delete($_POST['id']);
    }
    header("Location: restoran.php");
    exit();
}

$restoranList = $restoran->read();

$isEdit = false;
$editData = null;
if (isset($_GET['edit'])) {
    $isEdit = true;
    $id_edit = $_GET['edit'];
    foreach ($restoranList as $r) {
        if ($r['id'] == $id_edit) {
            $editData = $r;
            break;
        }
    }
}

include 'header.php';
?>

<h2><?= $isEdit ? 'Update Tempat Makan' : 'Manajemen Tempat Makan Sekitar UPI' ?></h2>

<form method="POST" action="restoran.php">
    <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= $editData['id'] ?>">
    <?php endif; ?>

    <input type="text" name="nama_restoran" placeholder="Nama Tempat Makan" 
           value="<?= $isEdit ? htmlspecialchars($editData['nama_restoran']) : '' ?>" required>
    
    <input type="text" name="alamat" placeholder="Alamat (misal: Gegerkalong / Setiabudhi)" 
           value="<?= $isEdit ? htmlspecialchars($editData['alamat']) : '' ?>" required>
           
    <input type="text" name="jam_operasional" placeholder="Jam Operasional" 
           value="<?= $isEdit ? htmlspecialchars($editData['jam_operasional']) : '' ?>" required>
    
    <?php if ($isEdit): ?>
        <button type="submit" name="update" style="background-color: #f39c12;">Update</button>
        <a href="restoran.php" style="padding: 10px 20px; background-color: #95a5a6; color: white; text-decoration: none; border-radius: 4px;">Batal</a>
    <?php else: ?>
        <button type="submit" name="create">Tambah Tempat Makan</button>
    <?php endif; ?>
</form>

<br>
<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Nama Restoran / Kantin</th>
        <th>Alamat</th>
        <th>Jam Operasional</th>
        <th>Aksi</th>
    </tr>
    <?php foreach ($restoranList as $row): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['nama_restoran']) ?></td>
        <td><?= htmlspecialchars($row['alamat']) ?></td>
        <td><?= htmlspecialchars($row['jam_operasional']) ?></td>
        
        <td style="display: flex; gap: 5px;">
            <a href="restoran.php?edit=<?= $row['id'] ?>">
                <button type="button" style="background-color: #f39c12; padding: 5px 10px;">Update</button>
            </a>
            
            <form method="POST" style="margin: 0; padding: 0; box-shadow: none; background: transparent; width: auto;">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" name="delete" onclick="return confirm('Hapus data restoran?')" style="padding: 5px 10px;">Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php include 'footer.php'; ?>