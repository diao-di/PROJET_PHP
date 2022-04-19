<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Vérifiez si l'identifiant du contact existe, par exemple update.php?id=1 obtiendra le contact avec l'identifiant 1
if (isset($_GET['id'])) {
    if (!empty($_POST)) {
        // Cette partie est similaire à create.php, mais à la place, nous mettons à jour un enregistrement et n'insérons pas
        $id = isset($_POST['id']) ? $_POST['id'] : NULL;
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
        $title = isset($_POST['title']) ? $_POST['title'] : '';
        $created = isset($_POST['created']) ? $_POST['created'] : date('Y-m-d H:i:s');
        $photo = isset($_POST['photo']) ? $_POST['photo'] : '';

        // Update the record
        $stmt = $pdo->prepare('UPDATE contact SET id = ?, name = ?, email = ?, phone = ?, title = ?, created = ?, photo = ? WHERE id = ?');
        $stmt->execute([$id, $name, $email, $phone, $title, $created, $photo, $_GET['id']]);
        $msg = 'Mis à jour avec succés!';
    }
    // Obtenir le contact à partir du tableau des contacts
    $stmt = $pdo->prepare('SELECT * FROM contact WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$contact) {
        exit('Le contact n\'existe pas avec cet ID!');
    }
} else {
    exit('Aucun identifiant spécifié!');
}
?>

<?=template_header('Read')?>

<div class="content update">
    <h2>Mis à jour Contact #<?=$contact['id']?></h2>
    <form action="update.php?id=<?=$contact['id']?>" method="post">
        <label for="id">ID</label>
        <label for="name">Nom</label>
        <input type="text" name="id" placeholder="1" value="<?=$contact['id']?>" id="id">
        <input type="text" name="name" placeholder="nom" value="<?=$contact['name']?>" id="name">
        <label for="email">Email</label>
        <label for="phone">Téléphone</label>
        <input type="text" name="email" placeholder="xxx@example.com" value="<?=$contact['email']?>" id="email">
        <input type="text" name="phone" placeholder="xxxxxxxxxxxxx" value="<?=$contact['phone']?>" id="phone">
        <label for="title">Titre</label>
        <label for="created">Created</label>
        <input type="text" name="title" placeholder="Employee" value="<?=$contact['title']?>" id="title">
        <input type="datetime-local" name="created" value="<?=date('Y-m-d\TH:i', strtotime($contact['created']))?>" id="created">
        <label for="photo">Photo</label>
        <input type="file" name="photo" placeholder="photo" value="<?=$contact['photo']?>" id="photo">
        <input type="submit" value="Update">
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>