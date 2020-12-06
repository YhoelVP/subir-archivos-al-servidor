<?php
    require_once('conexion.php');
    if (isset($_GET['delete_id'])) {
        $sent_select = $pdo->prepare('SELECT img_img FROM tbl_imagen WHERE img_id =:uid');
        $sent_select->execute(array(':uid'=> $_GET['delete_id']));
        $imgRow = $sent_select->fetch(PDO::FETCH_ASSOC);

        unlink('imagenes/' . $imgRow['img_img']);

        $sent_delete = $pdo->prepare('DELETE FROM tbl_imagen WHERE img_id =:uid');
        $sent_delete->bindParam(':uid', $_GET['delete_id']);
        $sent_delete->execute();

        header('Location: index.php');
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Subir imágenes al servidor</title>
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
            <h1 class="h2">
                Mostrar todos
                <a href="agregarNuevo.php" class="btn btn-default">
                    <span class="glyphicon glyphicon-plus"></span>
                    &nbsp; Agregar nuevo
                </a>
            </h1>
        </div>
        <br>
        <div class="row">
            <?php
                $sent = $pdo->prepare('SELECT img_id, img_marca, img_tipo, img_img FROM tbl_imagen ORDER BY img_id DESC;');
                $sent->execute();

                if ($sent->rowCount() > 0) {
                    while ($row = $sent->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
            ?>
            <div class="col-xs-3">
                <p class="page-header"><?php echo $img_marca . '&nbsp;/&nbsp;' . $img_tipo;?></p>
                <img 
                    src="imagenes/<?php echo $row['img_img']; ?>" 
                    class="img-rounded"
                    width="250px" 
                    height="250px" 
                    alt="Imagen principal"
                >
                <p class="page-header">
                    <span>
                        <a 
                            href="editarImagen.php?edit_id=<?php echo $row['img_id']; ?>" 
                            title="click for edit"
                            class="btn btn-info"
                            onclick="return confirm('¿Estás seguro de editar el archivo?')"
                        >
                            <span class="glyphicon glyphicon-edit"></span>
                            Editar
                        </a>
                        <a 
                            href="?delete_id=<?php echo $row['img_id']; ?>"
                            title="click for delete"
                            class="btn btn-danger"
                            onclick="return confirm('¿Estás seguro de eliminar el archivo?')"
                        >
                            <span class="glyphicon glyphicon-remove-circle"></span>
                            Eliminar
                        </a>
                    </span>
                </p>
            </div>

            <?php 
                    }
                } else {
            ?>
            <div class="col-xs-12">
                <div class="alert alert-warning">
                    <span class="glyphicon glyphicon-info-sing"></span>
                    &nbsp; Datos no encontrados...
                </div>
            </div>
            <?php } ?>
        </div>
    </div>

    <!-- Archivo js de bootstrap -->
    <script src="./bootstrap/js/bootstrap.min.js"></script>
</body>
</html>