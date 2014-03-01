<?php
/**
 *  Clase generadora de codigo de control para la facturacion automatica
 */
class UTILFacturacion {

    function __construct() {

    }

    /**
     *..... description
     *
     * @access public
     * @author Poloche
     * @author polochepu@gmail.com
     * @copyright Mobius IT S.R.L.
     * @copyright http://www.mobius.com.bo
     * @version rc 0.1
     * @date creation 21/07/2009
     */
    public function getCodigoControl($autorizacion, $numerofactura, $llave, $nit, $fecha, $monto) {

    }

    public function generarCodigoControl($nroAutorizacion, $nroFactura, $fecha, $monto, $llave, $nit = '0') {
        //yyyymmdd
        $fecha = str_replace("/", "", $fecha);
        $fecha = str_replace("-", "", $fecha);
        $monto=str_replace(",", ".", $monto);
        $entera =intval($monto);
        $centavos = $monto-$entera;
        if($centavos>=0.5) {
            $entera=$entera+1;
        }
        $monto = $entera;
        $this->obtenerVerhoef ( $nroFactura );
        $nroFactura = $nroFactura .$this->obtenerVerhoef ( $nroFactura );
        $nroFactura = $nroFactura . $this->obtenerVerhoef ( $nroFactura );
        
        $nit = intval($nit);
        $nit = $nit . $this->obtenerVerhoef ( $nit );
        $nit = $nit . $this->obtenerVerhoef ( $nit );

        $fecha = $fecha . $this->obtenerVerhoef ( $fecha );
        $fecha = $fecha . $this->obtenerVerhoef ( $fecha );

        //$monto = floor($monto);
//        $monto = round ( $monto, 0 );
        $monto = $monto . $this->obtenerVerhoef ( $monto );
        $monto = $monto . $this->obtenerVerhoef ( $monto );

        $total = $nroFactura + $nit + $fecha + $monto;

        for($i = 0; $i <= 4; $i ++) {

            $ver [$i] = $this->obtenerVerhoef ( $total );
            //echo $ver[$i];
            //echo "<br>";
            $total = $total . $ver [$i];
        }
        $inicio = 0;
        $nroAutorizacion .= substr ( $llave, $inicio, $ver [0] + 1 );
        $inicio = $inicio + $ver [0] + 1;
        $nroFactura .= substr ( $llave, $inicio, $ver [1] + 1 );
        $inicio = $inicio + $ver [1] + 1;
        $nit .= substr ( $llave, $inicio, $ver [2] + 1 );
        $inicio = $inicio + $ver [2] + 1;
        $fecha .= substr ( $llave, $inicio, $ver [3] + 1 );
        $inicio = $inicio + $ver [3] + 1;
        $monto .= substr ( $llave, $inicio, $ver [4] + 1 );

        $concatenado = $nroAutorizacion . $nroFactura . $nit . $fecha . $monto;

        $llaveCifrado = $llave . $ver [0] . $ver [1] . $ver [2] . $ver [3] . $ver [4];

        $cadenaLarga = $this->cifrarMensajeRC4 ( $concatenado, $llaveCifrado, 'no' );
        $cadenaLargaM = str_split ( $cadenaLarga );
        $totalASCII=0;
        $parcial1=0;
        $parcial2=0;
        $parcial3=0;
        $parcial4=0;
        $parcial5=0;
        for($i = 0; $i < strlen ( $cadenaLarga ); $i ++) {
            $totalASCII = $totalASCII + ord ( $cadenaLargaM [$i] );
        }
        for($i = 0; $i < strlen ( $cadenaLarga ); $i += 5) {
            $parcial1 = $parcial1 + ord ( $cadenaLargaM [$i] );
        }
        for($j = 1; $j < strlen ( $cadenaLarga ); $j += 5) {
            $parcial2 = $parcial2 + ord ( $cadenaLargaM [$j] );
        }
        for($k = 2; $k < strlen ( $cadenaLarga ); $k += 5) {
            $parcial3 = $parcial3 + ord ( $cadenaLargaM [$k] );
        }
        for($l = 3; $l < strlen ( $cadenaLarga ); $l += 5) {
            $parcial4 = $parcial4 + ord ( $cadenaLargaM [$l] );
        }
        for($m = 4; $m < strlen ( $cadenaLarga ); $m += 5) {
            $parcial5 = $parcial5 + ord ( $cadenaLargaM [$m] );
        }

        $mul1 = floor ( ($totalASCII * $parcial1) / ($ver [0] + 1) );
        $mul2 = floor ( ($totalASCII * $parcial2) / ($ver [1] + 1) );
        $mul3 = floor ( ($totalASCII * $parcial3) / ($ver [2] + 1) );
        $mul4 = floor ( ($totalASCII * $parcial4) / ($ver [3] + 1) );
        $mul5 = floor ( ($totalASCII * $parcial5) / ($ver [4] + 1) );

        $totalMul = $mul1 + $mul2 + $mul3 + $mul4 + $mul5;

        $totalB64 = $this->obtenerBase64 ( $totalMul );

        $codigoControl = $this->cifrarMensajeRC4 ( $totalB64, $llaveCifrado, 'si' );
        return $codigoControl;

    }
    function obtenerVerhoef($cifra) {
        $mul = array (array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ), array (1, 2, 3, 4, 0, 6, 7, 8, 9, 5 ),
                array (2, 3, 4, 0, 1, 7, 8, 9, 5, 6 ), array (3, 4, 0, 1, 2, 8, 9, 5, 6, 7 ),
                array (4, 0, 1, 2, 3, 9, 5, 6, 7, 8 ), array (5, 9, 8, 7, 6, 0, 4, 3, 2, 1 ),
                array (6, 5, 9, 8, 7, 1, 0, 4, 3, 2 ), array (7, 6, 5, 9, 8, 2, 1, 0, 4, 3 ),
                array (8, 7, 6, 5, 9, 3, 2, 1, 0, 4 ), array (9, 8, 7, 6, 5, 4, 3, 2, 1, 0 ) );
        $per = array (array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ), array (1, 5, 7, 6, 2, 8, 3, 0, 9, 4 ),
                array (5, 8, 0, 3, 7, 9, 6, 1, 4, 2 ), array (8, 9, 1, 6, 0, 4, 3, 5, 2, 7 ),
                array (9, 4, 5, 3, 1, 2, 6, 8, 7, 0 ), array (4, 2, 8, 6, 5, 7, 3, 9, 0, 1 ),
                array (2, 7, 9, 3, 8, 0, 6, 4, 1, 5 ), array (7, 0, 4, 6, 9, 1, 3, 2, 5, 8 ) );

        $Inv = array (0, 4, 3, 2, 1, 5, 6, 7, 8, 9 );
        $Check = 0;
        $invertido = $this->strReverse ( $cifra );
        $longitud = count ( $invertido );
        for($i = 0; $i < $longitud; $i ++) {
//            $posPer= (($i + 1) % 8);
//            if($posPer!="-") {
//                $posl = $invertido ["$i"];
//                $posk=$per ["$posPer"] ["$posl"];
//                $Check = $mul ["$Check"] ["$posk"];
//            }
            $Check = $mul [$Check] [$per [(($i + 1) % 8)] [$invertido [$i]]];
        }
        return $Inv ["$Check"];
    }
    function cifrarMensajeRC4($mensajee, $keyy, $guion = 'si') {

        $X = 0;
        $Y = 0;
        $Index1 = 0;
        $Index2 = 0;
        $MensajeCifrado = "";
        $LargoCadena = strlen ( $keyy );
        $LargoCadenaMensaje = strlen ( $mensajee );
        $mensaje = str_split ( $mensajee );
        $key = str_split ( $keyy );

        for($I = 0; $I <= 255; $I ++) {
            $State [$I] = $I;
        }
        for($I = 0; $I <= 255; $I ++) {
            $Index2 = (ord ( $key [$Index1] ) + $State [$I] + $Index2) % 256;
            $aux = $State [$Index2];
            $State [$Index2] = $State [$I];
            $State [$I] = $aux;
            $Index1 = ($Index1 + 1) % $LargoCadena;
        }

        for($I = 0; $I <= $LargoCadenaMensaje - 1; $I ++) {
            $X = ($X + 1) % 256;
            $Y = ($State [$X] + $Y) % 256;
            //echo $State[$X]+$Y;
            $aux2 = $State [$X];
            $State [$X] = $State [$Y];
            $State [$Y] = $aux2;

            $aux3 = $State [($State [$X] + $State [$Y]) % 256];

            $aux4 = ord ( $mensaje [$I] );
            $NMen = $aux4 ^ $aux3;
            if ($guion == 'si') {
                $MensajeCifrado = $MensajeCifrado . "-" . $this->rellenaCero ( dechex ( $NMen ) );
            } else {
                $MensajeCifrado = $MensajeCifrado . $this->rellenaCero ( dechex ( $NMen ) );
            }

        }
        //return  $MensajeCifrado;
        $MensajeCifrado = strtoupper ( $MensajeCifrado );
        if ($guion == 'si') {
            return substr ( $MensajeCifrado, 1, strlen ( $MensajeCifrado ) - 1 );
        } else {
            return $MensajeCifrado;
        }
    }
    function obtenerBase64($numero) {
        $Diccionario = array ('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D',
                'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
                'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j',
                'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
                '+', '/' );
        $Cociente = 1;
        $palabra = "";
        $Resto = 0;

        while ( $Cociente > 0 ) {
            $Cociente = $numero / 64;
            $Cociente = floor ( $Cociente );
            $Resto = $numero % 64;
            $palabra = $Diccionario [$Resto] . $palabra;
            $numero = $Cociente;
        }
        return $palabra;
    }
    function rellenaCero($numero) {
        if (strlen ( $numero ) == 1) {
            return "0" . $numero;
        } else {
            return $numero;
        }
    }
    function strReverse($cadena) {
        $ma = str_split ( $cadena );
        $mai = array_reverse ( $ma );
        return $mai;
    }
}

?>