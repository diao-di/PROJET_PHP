<?php
include 'functions.php';
// Connectez-vous à la base de données MySQL
$pdo = pdo_connect_mysql();
// Obtenir la page via la requête GET (paramètre d'URL: page), si elle n'existe pas, la page est définie par défaut sur 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
// Nombre d'enregistrements à afficher sur chaque page
$records_per_page = 10;
// Préparez l'instruction SQL et obtenez les enregistrements de notre table de contacts, LIMIT déterminera la page
$stmt = $pdo->prepare('SELECT * FROM contact ORDER BY id LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
// Récupérer les enregistrements afin que nous puissions les afficher dans notre modèle.
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
//var_dump($contacts);
// Obtenez le nombre total de contacts, afin que nous puissions déterminer s'il devrait y avoir un bouton suivant et précédent
$num_contacts = $pdo->query('SELECT COUNT(*) FROM contact')->fetchColumn();
?>
<?=template_header('Read')?>

<div class="content read">
	<h2>Lire les contacts</h2>
	<a href="create.php" class="create-contact">Ajouter un contact</a>
	<table>
        <thead>
            <tr>
                <td>id</td>
                <td>Nom</td>
                <td>Email</td>
                <td>Téléphone</td>
                <td>Title</td>
                <td>Created</td>
                <td>Photo</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contacts as $contact): ?>
            <tr>
                <td><?=$contact['id']?></td>
                <td><?=$contact['name']?></td>
                <td><?=$contact['email']?></td>
                <td><?=$contact['phone']?></td>
                <td><?=$contact['title']?></td>
                <td><?=$contact['created']?></td>
                <td><?= '<img src="./image/',$contact['file'],'"'; ?></td>

                
                <td class="actions">
                    <a href="update.php?id=<?=$contact['id']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="delete.php?id=<?=$contact['id']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
	<div class="pagination">
		<?php if ($page > 1): ?>
		<a href="read.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
		<?php endif; ?>
		<?php if ($page*$records_per_page < $num_contacts): ?>
		<a href="read.php?page=<?=$page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
		<?php endif; ?>
	</div>
</div>

<?=template_footer()?>