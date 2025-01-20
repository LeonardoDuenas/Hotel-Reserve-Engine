<!DOCTYPE html>
<html lang="en">

<head>
    <title>Motor de Reservas</title>
    <link rel="stylesheet" type="text/css" href="styles/mainForm.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Philosopher:ital,wght@0,400;0,700;1,400;1,700&display=swap"
        rel="stylesheet">
</head>

<body>
    <?php include_once 'header.php'; ?>

    <div class="content">
        <div class="avisos" onclick="openDialog()">
            <h2 id="aviso-importante">Importante !</h2>
        </div>


        <dialog id="importante-dialog">
            <p>Importante!</p>
            <p>Todos los precios son en USD. Los cargos se pagan en la moneda local a la tasa de cambio aplicable del
                hotel. Según las regulaciones de Perú, se agregará un 18% de IVA a las facturas de los residentes
                peruanos y a las de los no residentes que se hospeden más de 60 días.</p>
            <br>
            <p>Asu vez, esta pagina no genera una reserva. Mas bien genera una solicitud que prodremos culminar con
                usted via correo.</p>
            <br>
            <p>Gracias por contar con Sueños del Inka.</p>

            <button id="close-dialog-button" onclick="closeDialog()">X</button>
        </dialog>

        <div class="formulario">
            <form method="post" action="handler.php" onsubmit="fillEdadesDeNiños()">

                <div class="fecha-entrada">
                    <label for="checkin">Fecha de Entrada:</label>
                    <input type="date" id="checkin" name="checkin" onchange="validarFechas()" required>
                </div>

                <br>
                <div class="fecha-salida">
                    <label for="checkout">Fecha de Salida:</label>
                    <input type="date" id="checkout" name="checkout" oninput="validarFechas()" required>
                </div>

                <br>

                <div class="num-habitaciones">
                    <label for="NumHabitaciones">Numero de habitaciones:</label>
                    <input type="number" id="NumHabitaciones" name="NumHabitaciones" required>
                </div>

                <br>

                <div class="num-personas">
                    <label for="NumPersonas">Numero de Personas:</label>
                    <input type="number" id="NumPersonas" name="NumPersonas"
                        onchange="dynamicInput(this.id, 'adultos-niños')" required>
                </div>

                <br>

                <div id="adultos-niños" style="display:none;">
                    <label for="adultos">Numero de Adultos:</label>
                    <input type="number" id="adultos" name="adultos" onchange="validarPersonas()" required>
                    <br>
                    <br>
                    <label for="niños">Numero de Niños:</label>
                    <input type="number" id="niños" name="niños" oninput="validarPersonas()"
                        onchange="dynamicInput(this.id, 'edad-niños'); createSliders();" required>
                    <br>
                    <span style="font-size: 13px;">(0 - 4 años)</span>

                    <div id="edad-niños" style="display:none;"></div>
                    <!--
                    <div id="edad-niños" style="display:none;">
                        <p>Aqui iria los sliders pa la edad de cada niño</p>

                        <input type="range" min="0" max="17" step="1" value="9">

                        <p>Edad niño: </p>
                    </div>
                    -->

                </div>

                <br>

                <!-- This is a hidden input that will store the ages of the kids. -->
                <input id="hidden-edades-niños" type="hidden" name="edades-niños">

                <input type="submit" value="Buscar" name="submit" id="submitButton">

            </form>
        </div>
    </div>

    <?php include_once 'footer.php'; ?>
    <script>

        //When a number of kids is inputted, I want to create a slider for every kid's age.
        //make the slider show the input to the side as it changes.
        //maybe not Abstract this
        /*
        function dynamicInput() {
            var numPersonas = document.getElementById("NumPersonas").value;
            var div = document.getElementById("adultos-niños");
            if (numPersonas > 0) {
                div.style.display = "block";
            } else {
                div.style.display = "none";
            }
        }
        */

        function fillEdadesDeNiños() {
            const sliders = document.getElementsByClassName("slider-edades");
            const hiddenInput = document.getElementById("hidden-edades-niños");

            for (let i = 0; i < sliders.length; i++) {
                hiddenInput.value += sliders[i].value + ", ";
            }
        }

        function createSliders() {
            const numNinos = document.getElementById("niños").value;
            const div = document.getElementById("edad-niños");

            div.innerHTML = "";

            if (numNinos > 10) {
                alert("El numero maximo de niños es 10");
                document.getElementById("niños").value = "";
                return;
            }

            for (let i = 0; i < numNinos; i++) {
                const slider = document.createElement("input");
                slider.type = "range";
                slider.min = "0";
                slider.max = "17";
                slider.step = "1";
                slider.value = "9";
                slider.className = "slider-edades";
                slider.oninput = function () {
                    const p = document.getElementById("edad-niños").getElementsByTagName("p")[i];
                    p.innerHTML = "Edad niño " + (i + 1) + " : " + slider.value;
                }

                const p = document.createElement("p");
                p.innerHTML = "Edad niño " + (i + 1) + ": ";

                div.appendChild(p);
                div.appendChild(slider);
            }

        }

        function dynamicInput(inputId, divId) {
            var inputVal = document.getElementById(inputId).value;
            var divToShow = document.getElementById(divId);
            if (inputVal > 0) {
                divToShow.style.display = "block";

            } else {
                divToShow.style.display = "none";
            }
        }

        function validarFechas() {
            var checkin = document.getElementById("checkin").value;
            var checkout = document.getElementById("checkout").value;
            if (checkin == "" || checkout == "") {
                return false;
            }

            if (checkin > checkout) {
                alert("La fecha de entrada no puede ser mayor a la fecha de salida");
                document.getElementById("checkin").value = "";
                document.getElementById("checkout").value = "";
            }
        }

        function validarPersonas() {
            var numPersonas = document.getElementById("NumPersonas").value;
            var numAdultos = document.getElementById("adultos").value;
            var numNinos = document.getElementById("niños").value;


            if (numNinos == "") {
                return false;
            }

            if (numAdultos == "") {
                return false;
            }

            intNumPersonas = parseInt(numPersonas, 10);
            intNumAdultos = parseInt(numAdultos, 10);
            intNumNinos = parseInt(numNinos, 10);

            if (intNumPersonas !== intNumAdultos + intNumNinos) {
                alert("El numero de personas no coincide con el numero de adultos y niños");
                document.getElementById("adultos").value = "";
                document.getElementById("niños").value = "";
            }
        }

        function openDialog() {
            var dialog = document.getElementById("importante-dialog");
            dialog.showModal();
        }

        function closeDialog() {
            var dialog = document.getElementById("importante-dialog");
            dialog.close();
        }
    </script>

</body>

</html>