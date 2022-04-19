<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Vérifiez si les données POST ne sont pas vides
if (!empty($_POST)) {

     $file = $_FILES['file'];

     $fileName = $_FILES['file']['name'];
     $fileTmpName = $_FILES['file']['tmp_name'];
     $fileSize = $_FILES['file']['size'];
     $fileError = $_FILES['file']['error'];
     $fileType = $_FILES['file']['type'];

     $fileExt = explode('.',$fileName);
     $fileActualExt = strtolower(end($fileExt));


     $allowed = array('jpg', 'jpeg', 'png', 'pdf');

     if(in_array($fileActualExt, $allowed)){
        if($fileError === 0){
            if($fileSize < 1000000){
              $fileNameNew = uniqid('', true).".".$fileActualExt;
              $fileDestination = './image/'.$fileNameNew;
              move_uploaded_file($fileTmpName, $fileDestination);
              header("Location: index.php");
            }else{
               echo "Ce type fichier est tres volumeux pour etre telecharger dans notre site Web";
            }    
      }else{
          echo "Ce type de fichier n'est pas autoriser ici ! ";
      }
        }else{
            echo "Vous ne pouvez telecharger ce ty pe de fichier ! ";
        }
     
    



    // Données de publication non vides insérer un nouvel enregistrement
    // Configurez les variables qui vont être insérées, nous devons vérifier si les variables POST existent sinon nous pouvons les vider par défaut
    $id = isset($_POST['id']) && !empty($_POST['id']) && $_POST['id'] != 'auto' ? $_POST['id'] : NULL;
    // Vérifiez si la variable POST "nom" existe, sinon la valeur par défaut est vide, fondamentalement la même pour toutes les variables
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $created = isset($_POST['created']) ? $_POST['created'] : date('Y-m-d H:i:s');
    $file = isset($_POST['file']) ? $_POST['file'] : '';

    // Insérer un nouvel enregistrement dans la table des contacts
    $stmt = $pdo->prepare('INSERT INTO contact VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$id, $name, $email, $phone, $title, $created, $fileNameNew]);
    //var_dump($_id);
    // Message de sortie
    $msg = 'Créer avec succès!';
}
?>
<?=template_header('Create')?>

<div class="content update">
    <h2>Créer un Contact</h2>
    <form enctype="multipart/form-data" action="create.php" method="post">

    <?php
 
 ?>

        <label for="id">ID</label>
        <label for="name">Nom</label>
        <input type="text" name="id" placeholder="26" value="auto" id="id">
        <input type="text" name="name" placeholder="nom" id="name">
        <label for="email">Email</label>
        <label for="phone">Téléphone</label>
        <input type="text" name="email" placeholder="xxxx@example.com" id="email">
        <input type="text" name="phone" placeholder="xxxxxxxxxxxxxx" id="phone">
        <label for="title">Titre</label>
        <label for="created">Created</label>
        <input type="text" name="title" placeholder="titre" id="title">
        <input type="datetime-local" name="created" value="<?=date('Y-m-d\TH:i')?>" id="created">
        <label for="file">Photo</label>
        <input type="file" name="file" placeholder="file" size="50">
        <input type="submit" value="Create">
        
       
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>