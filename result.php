<?php
include_once 'Habitaciones.php';
session_start();


// echo 'Posibles paquetes: <br>';
//     foreach ($_SESSION['paquetes'] as $paquete){
//         echo 'Paquete: <br>';
//         foreach ($paquete as $habitacion){
//             $habitacion->mostrarInfoDeHab();
//         }
//         echo '<br>';
//     }

//Funcion para hallar la foto de la habitacion
function hallarFoto($numDePersonas)
{
    $foto = '';
    switch ($numDePersonas) {
        case 1:
            $foto = 'simple.jpg';
            break;
        case 2:
            $foto = 'doble.jpg';
            break;
        case 3:
            $foto = 'triple.jpg';
            break;
        default:
            $foto = 'simple.jpg';
            break;
    }
    return $foto;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Motor de Reservas</title>
    <link rel="stylesheet" type="text/css" href="styles/result.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Philosopher:ital,wght@0,400;0,700;1,400;1,700&display=swap"
        rel="stylesheet">
</head>

<body>
    <?php include_once 'header.php'; ?>
    <div class="content">
        <?php
        if (empty($_SESSION['paquetes'])) {
            echo '<h1>No hay paquetes disponibles</h1>';
            echo '<p>No hay habitaciones disponibles en estos momentos</p>';
            echo '<a href="index.php"><div class="button">Volver</div></a>';
            exit;
        } else {

            $numDePaquete = 1;
            $precioTotal = 0;
            echo '<div class="paquetes">';
            echo '<h1>Posibles paquetes:</h1>';
            foreach ($_SESSION['paquetes'] as $paquete) {
                $paqueteLastIndex = count($paquete) - 1;
                echo '<h2>Paquete ' . $numDePaquete . ':</h2>';
                echo '<div class="paquete" id="paquete' . $numDePaquete . '">';
                $numDePaquete++;
                echo '<div class="img-container-paquete">';
                echo '<img src="images/' . hallarFoto($paquete[$paqueteLastIndex]->getPersonas()) . '" alt="Foto de habitacion">';
                echo '</div>';
                echo '<div class="paquete-content">';
                foreach ($paquete as $habitacion) {
                    echo '<p> Habitacion ' . $habitacion->getNombreTipo() . '</p>';
                    foreach ($habitacion->getCamas() as $cama) {
                        echo '<h5> Cama ' . $cama->getTipo() . '</h5>';
                    }
                    $precioTotal += $habitacion->getPrecio();

                    echo '</br>';
                }
                echo '<p>Precio total: $' . $precioTotal . '</p>';
                echo '</div>';
                echo '</div>';
            }
        }
        echo '</div>';

        ?>

        <button id="boton_solicitar" disabled onclick="mostrarFormularioSolicitud()">Solicitar</button>


        <!--Formulario de solicitud  -> solo se debe mostrar si se ha seleccionado un paquete y despues del boton de solicitar
        Nombres, Apellidos, email, Pais de Residencia, Telf-->

        <!-- div should be inside the form-->

        <div class="formulario-solicitud">

            <form action="solicitud_paquete.php" method="POST" id="formularioSolicitud">
                <fieldset>
                    <legend>Formulario de solicitud</legend>

                    <div class="campos-container">

                        <div class="left">
                            <div class="campos">
                                <label for="nombres">Nombre y Apellido:</label>
                                <input type="text" name="nombresSolicitante" id="nombresSolicitud" required>
                            </div>

                            <!--
                            <div class="campos">
                                <label for="apellidos">Apellidos:</label>
                                <input type="text" name="apellidoSolicitante" id="apellidoSolicitud" required>
                            </div>
                            -->

                            <div class="campos">
                                <label for="email">E-mail:</label>
                                <input type="text" name="correoSolicitante" id="correoSolicitud" required>
                            </div>
                        </div>

                        <div class="right">

                            <div class="campos">
                                <label for="pais">Pais de Residencia:</label>
                                <input type="text" name="paisSolicitante" id="paisSolicitud" required>
                            </div>

                            <div class="campos">
                                <label for="telefono">Telefono:</label>
                                <input type="text" name="telefonoSolicitante" id="telefonoSolicitud" required>
                            </div>

                        </div>
                    </div>

                    <input type="hidden" name="paqueteSolicitante" id="inputSolicitudPaquete" required>

                    <input type="hidden" name="fechas"
                        value="<?php echo $_SESSION['checkin'] . ' - ' . $_SESSION['checkout']; ?>" required>

                    <input type="hidden" name="niños" value="<?php echo $_SESSION['niños'] ?>" required>

                    <input type="hidden" name="adultos" value="<?php echo $_SESSION['adultos'] ?>" required>

                    <input type="hidden" name="edades-niños" value="<?php echo $_SESSION['edades-niños'] ?>" required>

                    <div class="input-enviar">
                        <input type="submit" value="Enviar" id="enviar_solicitud" name="solicitudEnviada"
                            onclick="return validarFormulario()">
                    </div>

                </fieldset>
            </form>
        </div>

    </div>

    <?php include_once 'footer.php'; ?>

</body>

</html>

<script>
    const divs = document.querySelectorAll('.paquete');
    let selectedDivId = '';

    // Add click event listener to each div
    divs.forEach(div => {
        div.addEventListener('click', function () {

            if (selectedDivId !== '' && selectedDivId !== this.id) {
                const selectedDiv = document.getElementById(selectedDivId);
                selectedDiv.style.border = 'none';
            }

            this.style.border = '3px solid';
            this.style.borderColor = 'yellow';
            selectedDivId = this.id;

            document.getElementById('boton_solicitar').disabled = false;

            //poner los datos en un input en el form
            document.getElementById('inputSolicitudPaquete').value = getDatosDePaquete().join(' ');
        });
    });

    //show the form
    function mostrarFormularioSolicitud() {
        document.querySelector('.formulario-solicitud').style.display = 'flex';
    }

    //consigue los datos del paquete seleccionado
    function getDatosDePaquete() {
        const paquete = document.getElementById(selectedDivId);
        const elementos = paquete.querySelectorAll('p', 'h5');
        const datos = Array.from(elementos).map(element => element.textContent);
        return datos;
    }

    //Validacion ejemplo (numero de telefono)
    //todos usan regex, sueltan una alerta y no permiten que el form sea enviado
    function validarFormulario() {
        return validarTelefono() && validarNombreApellido() && validarCorreo() && validarPais();
    }


    function validarTelefono() {
        var telefono = document.getElementById('telefonoSolicitud').value;
        var telfPattern = /^[0-9]+$/;

        if (!telfPattern.test(telefono)) {
            alert("Telefono invalido");
            return false;
        }

        return true;
    }

    function validarNombreApellido() {
        var nombre = document.getElementById('nombresSolicitud').value;
        var nombrePattern = /^[a-zA-Z]+$/;

        if (!nombrePattern.test(nombre)) {
            alert("Nombre invalido");
            return false;
        }

        return true;
    }

    function validarCorreo() {
        var correo = document.getElementById('correoSolicitud').value;
        var correoPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

        if (!correoPattern.test(correo)) {
            alert("Correo invalido");
            return false;
        }

        return true;
    }

    function validarPais() {
        var pais = document.getElementById('paisSolicitud').value;
        var paisPattern = /^[a-zA-Z]+(?: [a-zA-Z]+)*$/;

        if (!paisPattern.test(pais)) {
            alert("Pais invalido");
            return false;
        }

        return true;
    }

</script>