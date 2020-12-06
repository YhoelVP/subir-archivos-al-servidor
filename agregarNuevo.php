<?php
    error_reporting(~E_NOTICE);
    require_once('conexion.php');

    if (isset($_POST['btnsave'])) {
        $userName = $_POST['user_name'];
        $userJob = $_POST['user_job'];
        $imgFile = $_FILES['user_image']['name'];
        $tmpDir = $_FILES['user_image']['tmp_name'];
        $imgSize = $_FILES['user_image']['size'];

        if (empty($userName)) {
            $errMSG = 'Ingrese la marca';

        } else if (empty($userJob)) {
            $errMSG = 'Ingrese el tipo';

        } else if (empty($imgFile)) {
            $errMSG = 'Seleccione el archivo de imagen';

        } else {
            $upload_dir = './imagenes/';

            if (!file_exists($upload_dir)) mkdir($upload_dir);
            $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION));
            $validExtension = array('jpeg', 'png', 'jpg', 'gif');
            $userpic = rand(1000, 1000000) . '' . $imgExt;
            if (in_array($imgExt, $validExtension)) {
                if ($imgSize < 1000000) {
                    move_uploaded_file($tmpDir, $upload_dir . $userpic);
                } else {
                    $errMSG = 'Su archivo es muy grande';
                }
            } else {
                $errMSG = 'El tipo de archivo elegido no es permitido';
            }
        }

        if (!isset($errMSG)) {
            $sent = $pdo->prepare('INSERT INTO tbl_imagen (img_marca, img_tipo, img_img) VALUES(:uname, :ujob, :upic)');
            $sent->bindParam(':uname', $userName);
            $sent->bindParam(':ujob', $userJob);
            $sent->bindParam(':upic', $userpic);
            
            if ($sent->execute()) {
                $successMsg = 'Nuevo registro insertado correctamente...';
                header('refresh:3; index.php');
            } else {
                $errMSG = 'Error al insertar registro...';
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Subir archivos</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Styles bootstrap.css -->
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./bootstrap/css/bootstrap-theme.min.css">

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
            <h1 class="h3">
                Agregar nueva imagen
                <a href="./index.php" class="btn btn-default">
                    <span class="glyphicon glyphicon-eye-open"></span>
                    &nbsp; Mostrar todo
                </a>
            </h1>
        </div>
        <?php if (isset($errMSG)): ?>
            <div class="alert alert-danger">
                <span class="glyphicon glyphicon-info-sing"></span>
                <strong><?php echo $errMSG; ?></strong>
            </div>
        <?php elseif (isset($successMsg)): ?>
            <div class="alert alert-success">
                <span class="glyphicon glyphicon-info-sing"></span>
                <strong><?php echo $successMsg; ?></strong>
            </div>
        <?php endif ?>

        <form method="POST" enctype="multipart/form-data" class="form-horizontal">
            <table class="table-bordered table-responsive">
                <tr>
                    <td>
                        <label class="control-label">Marca</label>
                    </td>
                    <td>
                        <input 
                            type="text"
                            class="form-control"
                            name="user_name"
                            placeholder="Ingrese la marca"
                            value="<?php echo $userName; ?>"
                        >
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class="control-label">Modelo</label>
                    </td>
                    <td>
                        <input 
                            type="text"
                            class="form-control"
                            name="user_job"
                            placeholder="Ingrese el modelo"
                            value="<?php echo $userJob; ?>"
                        >
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class="control-label">Imagen</label>
                    </td>
                    <td>
                        <input 
                            type="file"
                            class="form-control"
                            name="user_image"
                            accept="image/*"
                            placeholder="Ingrese la marca"
                            value="<?php echo $userName; ?>"
                        >
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button
                            type="submit"
                            name="btnsave"
                            class="btn btn-default"
                        >
                            <span class="glyphicon glyphicon-save"></span>
                            &nbsp; Guardar imagen
                        </button>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <!-- Archivo js de bootstrap -->
    <script src="./bootstrap/js/bootstrap.min.js"></script>
</body>
</html>