<?php
error_reporting(~E_NOTICE);
require_once('conexion.php');

if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    $sent_edit = $pdo->prepare('SELECT img_marca, img_tipo, img_img FROM tbl_imagen WHERE img_id = :uid');
    $sent_edit->execute(array(':uid' => $id));
    $editRow = $sent_edit->fetch(PDO::FETCH_ASSOC);
    extract($editRow);
} else {
    header('Location: ./index.php');
}

if (isset($_POST['btn_save_updates'])) {
    $userName = $_POST['user_name'];
    $userJob = $_POST['user_job'];
    $imgFile = $_FILES['user_image']['name'];
    $tmpDir = $_FILES['user_image']['tmp_name'];
    $imgSize = $_FILES['user_image']['size'];

    if ($imgFile) {
        $upload_dir = 'imagenes/';
        $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION));
        $validExtension = array('jpeg', 'png', 'jpg', 'gif');
        $userpic = rand(1000, 1000000) . '' . $imgExt;
        
        if (in_array($imgExt, $validExtension)) {
            if ($imgSize < 1000000) {
                unlink($upload_dir . $editRow['img_img']);
                move_uploaded_file($tmpDir, $upload_dir . $userpic);

            } else {
                $errMSG = 'Su archivo es muy grande';
            }
        } else {
            $errMSG = 'El tipo de archivo elegido no es permitido';
        }
    } else {
        $userpic = $editRow['img_img'];
    }

    if (!isset($errMSG)) {
        $sent = $pdo->prepare('UPDATE tbl_imagen SET img_marca = :uname, img_tipo = :ujob, img_img = :upic WHERE img_id = :uid');
        $sent->bindParam(':uname', $userName);
        $sent->bindParam(':ujob', $userJob);
        $sent->bindParam(':upic', $userpic);
        $sent->bindParam(':uid', $id);

        if ($sent->execute()) {
?>
            <script>
                alert('Archivo editado correctamente');
                window.location.href="index.php";
            </script>
<?php
        } else {
            $errMSG = 'Los datos no fueron actualizados';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Subir archivos</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Styles bootstrap.css -->
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./bootstrap/css/bootstrap-theme.min.css">

    <!-- Styles -->
    <link rel="stylesheet" href="style.css">

    <!-- Styles bootstrap.js -->
    <script src="./bootstrap/js/jquery.min.js"></script>
</head>
<body>
    <div class="nav navbar-default navbar-static-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <a href="index.php" class="navbar-brand" title="Inicio">Inicio</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="page-header">
            <h1 class="h">
                Actualización producto
                <a href="./index.php" class="btn btn-default">
                    Mostrar todos los modelos
                </a>
            </h1>
        </div>
        <div class="clearfix"> <!-- Div vacío --> </div>
        <form method="POST" enctype="multipart/form-data" class="form-horizontal">
            <?php if (isset($errMSG)): ?>
                <div class="alert alert-danger">
                    <span class="glyphicon glyphicon-info-sing"></span>
                    &nbsp; <?php echo $errMSG; ?>
                </div>
            <?php endif ?>

            <table class="table-bordered table-responsive">
                <tr>
                    <td>
                        <label for="marca" class="control-label">Marca:</label>
                    </td>
                    <td>
                        <input type="text" name="userName" class="form-control" required=" " value="<?php echo $Imagen_Marca; ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="tipo" class="control-label">Tipo:</label>
                    </td>
                    <td>
                        <input type="text" name="userJob" class="form-control" required=" " value="<?php echo $Imagen_Tipo; ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="tipo" class="control-label">Imágen:</label>
                    </td>
                    <td>
                        <p>
                            <img src="imagenes/<?php echo $Imagen_Img ?>" alt="" width="150" height="150">
                        </p>
                        <input type="file" name="userImage" class="input-group" required=" " accept="image/*">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button type="submit" name="btn_save_updates" class="btn btn-default">
                            <span class="glyphicon glyphicon-save"></span>
                            Actualizar
                        </button>
                        <a href="index.php" class="btn btn-default">
                            <span class="glyphicon glyphicon-backward"></span>
                            Cancelar
                        </a>
                    </td>
                    <td>
                        <input type="text" name="userJob" class="form-control" required=" " value="<?php echo $Imagen_Tipo; ?>">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>