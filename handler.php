<?php

include 'Habitaciones.php';

if (isset($_POST['submit'])) {

    $inputCheckinDate = $_POST['checkin']; //yyyy-mm-dd to string
    $inputCheckoutDate = $_POST['checkout'];

    $checkinDate = date_create($inputCheckinDate);
    $checkoutDate = date_create($inputCheckoutDate);

    $habNum = $_POST['NumHabitaciones'];
    $numPersonas = $_POST['NumPersonas'];
    $adultNum = $_POST['adultos'];
    $childNum = $_POST['niños'];

    $edadesNiños = $_POST['edades-niños'];

    //crear una lista de habitaciones
    $habitaciones = array(
        new Habitaciones(101, "deluxe", 100, array(new Camas("individual")), false),
        new Habitaciones(102, "normal", 50, array(new Camas("matrimonial")), true, "2024-07-10", "2024-07-15"),
        new Habitaciones(103, "normal", 57, array(new Camas("individual"), new Camas("individual")), false),
        new Habitaciones(104, "normal", 50, array(new Camas("queen")), true, "2024-10-11", "2024-10-15"),
        new Habitaciones(105, "deluxe", 100, array(new Camas("individual"), new Camas("matrimonial")), false),
        new Habitaciones(106, "deluxe", 100, array(new Camas("king")), true, "2024-10-10", "2024-10-15"),
        new Habitaciones(107, "normal", 56, array(new Camas("individual"), new Camas("matrimonial")), true, "2024-07-12", "2024-07-15"),
        new Habitaciones(108, "normal", 50, array(new Camas("queen")), true, "2024-06-16", "2024-06-18"),
        new Habitaciones(201, "deluxe", 100, array(new Camas("individual")), false),
        new Habitaciones(202, "normal", 50, array(new Camas("individual")), false),
        new Habitaciones(203, "normal", 57, array(new Camas("individual")), false),
    );

    //buscar habitaciones validas para crear paquetes
    $habitacionesValidas = buscarHabitaionesDisponibles($habitaciones, $checkinDate, $checkoutDate);

    //resultado
    $paquetes = crearPaquete($habitacionesValidas, $habNum, $adultNum); //Solo cuento a los adultso


    // if(empty($paquetes)){
    //     return;
    // }

    //$serialized_paquetes = serialize($paquetes);

    session_start();
    $_SESSION['paquetes'] = $paquetes;
    $_SESSION['checkin'] = $inputCheckinDate;
    $_SESSION['checkout'] = $inputCheckoutDate;
    $_SESSION['niños'] = $childNum;
    $_SESSION['adultos'] = $adultNum;
    $_SESSION['edades-niños'] = $edadesNiños;

    header('Location: result.php');

    exit;

    // echo 'Posibles paquetes: <br>';
    // foreach ($paquetes as $paquete){
    //     echo 'Paquete: <br>';
    //     foreach ($paquete as $habitacion){
    //         $habitacion->mostrarInfoDeHab();
    //     }
    //     echo '<br>';
    // }


}

function buscarHabitaionesDisponibles($listaDeHabitaciones, $checkinDate, $checkoutDate)
{
    //crear una lista de habitaciones disponibles
    $habitacionesDisponibles = array();

    foreach ($listaDeHabitaciones as $habitacion) {
        //Si la habitacion no esta reservada, agregarla a la lista de habitaciones disponibles
        if (!$habitacion->getReservada()) {
            array_push($habitacionesDisponibles, $habitacion);
        } else //reservada
        {
            //Si la habitacion esta reservada, verificar si la fecha de checkin y checkout no se overlapea con la fecha que quiere el usuario
            if ($checkoutDate <= $habitacion->getCheckInFecha() || $checkinDate >= $habitacion->getCheckOutFecha()) {
                array_push($habitacionesDisponibles, $habitacion);
            }
        }
    }

    return $habitacionesDisponibles;
}

function crearPaquete($habitaciones, $numHab, $numPersonas)
{
    $remainingHab = $numHab;
    $remainingPersonas = $numPersonas; //No cuentes a los niños de mrd
    $paquete = array(); //array de habitaciones

    $paquetes = array(); //array de array de habitaciones


    //Sortear habitaciones por numero de personas de forma ascnedente. Menos personas primero

    for ($i = 0; $i < count($habitaciones); $i++) {
        for ($j = 0; $j < count($habitaciones); $j++) {
            if ($habitaciones[$i]->getPersonas() < $habitaciones[$j]->getPersonas()) {
                $temp = $habitaciones[$i];
                $habitaciones[$i] = $habitaciones[$j];
                $habitaciones[$j] = $temp;
            }
        }
    }

    /* While(habitaciones is not empty AND paquetes count != 3) 
    Funciona para habitaciones de igual num y personas*/
    $i = 0;
    while (!empty($habitaciones) && count($paquetes) != 3 && $i < count($habitaciones)) {
        //Si hab restantes es 0 y personas restantes es 0, el paquete esta echo. Añadir al array de paquetes
        if ($remainingHab == 0 && $remainingPersonas == 0) {
            array_push($paquetes, $paquete);

            //quitar la primera habitacion del array para generar distintas combinaciones
            //Si solo hay una habitacion, quitarla del array
            if ($numHab == 1) {
                array_splice($habitaciones, $i - 1, 1);
            }

            array_shift($habitaciones);

            //re-inizialisar variables
            $paquete = array();
            $i = 0;
            $remainingHab = $numHab;
            $remainingPersonas = $numPersonas;
        }

        //If Rem hab is 0 and rem personas is greater than 0, pop the last element of the array and search for another combination
        if ($remainingHab == 0 && $remainingPersonas > 0) {
            array_pop($paquete);
            $remainingHab++;
            $remainingPersonas += $habitaciones[$i - 1]->getPersonas();
        }

        //If the number of people in the room is less than or equal to the remaining people, add the room to the package
        if ($habitaciones[$i]->getPersonas() <= $remainingPersonas) {
            array_push($paquete, $habitaciones[$i]);
            $remainingHab--;
            $remainingPersonas -= $habitaciones[$i]->getPersonas();
        }
        $i++;

    }

    //Encontrar todas las combinaciones posibles de habitaciones de acuerdo al numero de habitaciones y personas

    // if (empty($paquetes)){
    //     echo "No se encontraron paquetes disponibles <br>";
    // } 

    return $paquetes;


}