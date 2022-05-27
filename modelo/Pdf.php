<?php
include_once 'Venta.php';
include_once 'VentaProducto.php';
include_once 'Cliente.php';

function getHtml($id_venta){
    $venta=new Venta();
    $venta_producto=new VentaProducto();
    $cliente = new Cliente();
    $venta->buscar_id($id_venta);
    $venta_producto->ver($id_venta);
    $plantilla='
    <body>
    <header class="clearfix">
      <div id="logo">
        <img src="../img/logo.png" width="60" height="60">
      </div>
      <h1>COMPROBANTE </h1>
      <div id="company" class="clearfix">
        <div id="negocio">PUESTO DE SALUD</div>
        <div>Direccion Numero ###,<br /> Aldea 20 de Agosto, Malacatan, San Marcos</div>
        <div>(344) 342234</div>
        <div><a href="centrodesalud20deagosto"></a></div>
      </div>';
      foreach ($venta->objetos as $objeto) {
        if (empty($objeto->id_cliente)) {
          $cliente_nombre=$objeto->cliente;
          $cliente_dni=$objeto->dni;
        }
        else{
            $cliente->buscar_datos_cliente($objeto->id_cliente);
            foreach ($cliente->objetos as $cli) {
                $cliente_nombre=$cli->nombre.' '.$cli->apellidos;
                $cliente_dni=$cli->dni;
            }
          
        }
      $plantilla.='
    
      <div id="project">
        <div><span>Codigo de Venta: </span>'.$objeto->id_venta.'</div>
        <div><span>Cliente: </span>'.$cliente_nombre.'</div>
        <div><span>DNI: </span>'.$cliente_dni.'</div>
        <div><span>Fecha y Hora: </span>'.$objeto->fecha.'</div>
        <div><span>Vendedor: </span>'.$objeto->vendedor.'</div>
      </div>';
      }
    $plantilla.='
    </header>
    <main>
      <table>
        <thead>
          <tr>
           
            <th class="service">Producto</th>
            <th class="service">Concentracion</th>
            <th class="service">Adicional</th>
            <th class="service">Laboratorio</th>
            <th class="service">Presentacion</th>
            <th class="service">Tipo</th>
            <th class="service">Cantidad</th>
            <th class="service">Precio</th>
            <th class="service">Subtotal</th>
          </tr>
        </thead>
        <tbody>';
        foreach ($venta_producto->objetos as $objeto) {
         
          $plantilla.='<tr>
            
            <td class="servic">'.$objeto->producto.'</td>
            <td class="servic">'.$objeto->concentracion.'</td>
            <td class="servic">'.$objeto->adicional.'</td>
            <td class="servic">'.$objeto->laboratorio.'</td>
            <td class="servic">'.$objeto->presentacion.'</td>
            <td class="servic">'.$objeto->tipo.'</td>
            <td class="servic">'.$objeto->cantidad.'</td>
            <td class="servic">'.$objeto->precio.'</td>
            <td class="servic">'.$objeto->subtotal.'</td>
          </tr>';
        }
        $calculos= new Venta();
        $calculos->buscar_id($id_venta);
        foreach ($calculos->objetos as $objeto) {
          $igv=$objeto->total*0.18;
          $sub=$objeto->total-$igv;
          
          $plantilla.='
          <tr>
            <td colspan="8" class="grand total"></td>
            <td class="grand total">S/.'.$sub.'</td>
          </tr>
          <tr>
            <td colspan="8" class="grand total"></td>
            <td class="grand total">S/.'.$igv.'</td>
          </tr>
          <tr>
            <td colspan="8" class="grand total"></td>
            <td class="grand total">S/.'.$objeto->total.'</td>
          </tr>';

        }
       $plantilla.='
        </tbody>
      </table>
      <div id="notices">
        
      </div>
    </main>
    <footer>
    </footer>
  </body>';
    return $plantilla;
}