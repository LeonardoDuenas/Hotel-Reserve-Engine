<?php

class Camas
{
    private $tipo;

    public function __construct($tipo)
    {
        if ($this->validarCama($tipo)) {
            $this->tipo = $tipo; //matrimonial, individual, king, queen size
        } else {
            echo "Tipo de cama no válido";
        }
    }

    private function validarCama($tipo)
    {
        if ($tipo == "matrimonial" || $tipo == "individual" || $tipo == "king" || $tipo == "queen") {
            return true;
        } else {
            return false;
        }
    }

    public function getTipo()
    {
        return $this->tipo;
    }
}

class Habitaciones
{
    private $numero;
    private $tipo;
    private $precio;
    private $camas;
    private $reservada;
    private $checkInFecha;
    private $checkOutFecha;

    public function __construct($numero, $tipo, $precio, $camas, $reservada, $checkInFecha = "", $checkOutFecha = "")
    {
        $this->numero = $numero;
        $this->tipo = $tipo; //deluxe o normal
        $this->precio = $precio;
        $this->camas = $camas;
        $this->reservada = $reservada;

        $this->checkInFecha = $checkInFecha;
        $this->checkOutFecha = $checkOutFecha;
    }

    //Borrar despues
    public function getNumero()
    {
        return $this->numero;
    }

    public function getReservada()
    {
        return $this->reservada;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function getCamas()
    {
        return $this->camas;
    }

    public function getNombreTipo()
    {
        $nombre = "";

        if ($this->getTipo() == "deluxe") {
            $nombre = "Deluxe ";
        }

        switch ($this->getPersonas()) {
            case 1:
                $nombre .= "Individual";
                break;
            case 2:
                $nombre .= "Matrimonial";
                break;
            case 3:
                $nombre .= "Triple";
                break;
            default:
                $nombre .= "No definido";
                break;
        }

        return $nombre;
    }


    public function getPersonas()
    {
        $personas = 0;
        foreach ($this->camas as $cama) {
            if ($cama->getTipo() == "matrimonial" || $cama->getTipo() == "king" || $cama->getTipo() == "queen") {
                $personas += 2;
            } else {
                $personas += 1;
            }
        }
        return $personas;
    }

    public function getPrecio()
    {
        return $this->precio;
    }

    public function getCheckInFecha()
    {
        //Solo quiero que sea fecha si es que lo llamo en el handler. Asi que la conversion de string a date sera aca

        $this->checkInFecha = date_create($this->checkInFecha);
        return $this->checkInFecha;
    }

    public function getCheckOutFecha()
    {
        //Solo quiero que sea fecha si es que lo llamo en el handler. Asi que la conversion de string a date sera aca

        $this->checkOutFecha = date_create($this->checkOutFecha);
        return $this->checkOutFecha;
    }

    private function disponibilidad()
    {
        if ($this->reservada) {
            return 'true';
        } else {
            return 'false';
        }
    }

    public function mostrarInfoDeHab()
    {
        echo "Habitación número: " . $this->numero . "<br>";
        echo "Tipo de habitación: " . $this->tipo . "<br>";
        echo "Precio: " . $this->precio . "<br>";
        echo count($this->camas) . " Camas: <br>";
        foreach ($this->camas as $cama) {
            echo "Tipo de cama: " . $cama->getTipo() . "<br>";
        }
        echo "reservada: " . $this->disponibilidad() . "<br>";
    }
}