<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Vérifier que l'ID de contact existe
if (isset($_GET['id'])) {
    // Sélectionnez l'enregistrement qui va être supprimé
    $stmt = $pdo->prepare('SELECT * FROM contact WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$contact) {
        exit('Le contact n\'existe pas avec cet ID!');
    }
    // Assurez-vous que l'utilisateur confirme avant la suppression
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            // L'utilisateur a cliqué sur le bouton "Oui", supprimer l'enregistrement
            $stmt = $pdo->prepare('DELETE FROM contact WHERE id = ?');
            $stmt->execute([$_GET['id']]);
            $msg = 'Vous avez supprimé le contact!';
        } else {
            // L'utilisateur a cliqué sur le bouton "Non", le redirige vers la page de lecture
            header('Location: read.php');
            exit;
        }
    }
} else {
    exit('Aucun identifiant spécifié!');
}
?>
<?=template_header('Delete')?>

<div class="content delete">
    <h2>Effacer ce contact #<?=$contact['id']?></h2>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php else: ?>
    <p>Êtes-vous sûr de vouloir supprimer le contact #<?=$contact['id']?>?</p>
    <div class="yesno">
        <a href="delete.php?id=<?=$contact['id']?>&confirm=yes">Oui</a>
        <a href="delete.php?id=<?=$contact['id']?>&confirm=no">Non</a>
    </div>
    <?php endif; ?>
</div>

<?=template_footer()?>