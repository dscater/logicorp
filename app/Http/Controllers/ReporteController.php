<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Contrato;
use App\Models\Empresa;
use App\Models\Lote;
use App\Models\Programacion;
use App\Models\Urbanizacion;
use App\Models\User;
use App\Models\VentaLote;
use App\Models\Viaje;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use PDF;

class ReporteController extends Controller
{
    public function usuarios()
    {
        return Inertia::render("Reportes/Usuarios");
    }

    public function r_usuarios(Request $request)
    {
        $tipo =  $request->tipo;
        $usuarios = User::where('id', '!=', 1)->where("tipo", "!=", "CLIENTE")->orderBy("paterno", "ASC")->get();

        if ($tipo != 'TODOS') {
            $request->validate([
                'tipo' => 'required',
            ]);
            $usuarios = User::where('id', '!=', 1)->where('tipo', $request->tipo)->orderBy("paterno", "ASC")->get();
        }

        $pdf = PDF::loadView('reportes.usuarios', compact('usuarios'))->setPaper('legal', 'landscape');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('usuarios.pdf');
    }

    public function consolidacion_viajes()
    {
        return Inertia::render("Reportes/ConsolidacionViajes");
    }

    public function r_consolidacion_viajes(Request $request)
    {
        $empresa_id =  $request->empresa_id;
        $asociacion_id =  $request->asociacion_id;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;

        $viajes = Viaje::select("viajes.*")
            ->join("programacions", "programacions.id", "=", "viajes.programacion_id");

        if ($empresa_id != 'todos') {
            $viajes->where("programacions.empresa_id", $empresa_id);
        }

        if ($asociacion_id != 'todos') {
            $viajes->where("programacions.asociacion_id", $asociacion_id);
        }

        if ($fecha_ini && $fecha_fin) {
            $viajes->whereBetween("viajes.fecha_registro", [$fecha_ini, $fecha_fin]);
        }

        $viajes = $viajes->get();

        $pdf = PDF::loadView('reportes.consolidacion_viajes', compact('viajes'))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('consolidacion_viajes.pdf');
    }

    public function consolidacion_viajes_empresas()
    {
        return Inertia::render("Reportes/ConsolidacionViajesEmpresas");
    }

    public function r_consolidacion_viajes_empresas(Request $request)
    {
        $empresa_id =  $request->empresa_id;
        $asociacion_id =  $request->asociacion_id;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;

        $empresas = Empresa::where("tipo", "EMPRESA");
        if ($empresa_id != 'todos') {
            $empresas->where("id", $empresa_id);
        }
        $empresas = $empresas->get();

        $asociacions = Empresa::where("tipo", "ASOCIACIÓN");
        if ($asociacion_id != 'todos') {
            $asociacions->where("id", $asociacion_id);
        }
        $asociacions = $asociacions->get();

        $html = "";

        $fecha_ini_t = date("d/m/Y", strtotime($fecha_ini));
        $fecha_fin_t = date("d/m/Y", strtotime($fecha_fin));

        foreach ($asociacions as $key => $asociacion) {

            $contratos = Contrato::where("empresa_id", $asociacion->id)->get();
            foreach ($contratos as $key_contrato => $contrato) {
                foreach ($empresas as $key_empresa => $empresa) {
                    if ($fecha_ini && $fecha_fin) {
                        $html .= '<p class="txt_info">Cisternas Descargadas de ' . $fecha_ini_t . ' al ' . $fecha_fin_t . '</p>';
                    } else {
                        $html .= '<p class="txt_info">Cisternas Descargadas</p>';
                    }
                    $html .= '<p class="txt_info">Contrato: ' . $contrato->codigo . '</p>';
                    $html .= '<p class="txt_info">Asociación: ' . $asociacion->razon_social . '</p>';
                    $html .= '<p class="txt_info">Empresa: ' . $empresa->razon_social . '</p>';

                    $viajes = Viaje::select("viajes.*")
                        ->join("programacions", "programacions.id", "=", "viajes.programacion_id")
                        ->where("programacions.empresa_id", $empresa->id)
                        ->where("programacions.contrato_id", $contrato->id)
                        ->where("programacions.asociacion_id", $asociacion->id);

                    if ($fecha_ini && $fecha_fin) {
                        $viajes->whereBetween("viajes.fecha_descarga", [$fecha_ini, $fecha_fin]);
                    }

                    $viajes =  $viajes->orderBy("viajes.fecha_carga", "asc")
                        ->get();

                    $html .= '<table border="1">';
                    $html .= '<thead>';
                    $html .= '<tr>';
                    $html .= '<th colspan="6">Información de Origen</th>';
                    $html .= '<th colspan="4">Información de Destino</th>';
                    $html .= '<th colspan="3">Merma</th>';
                    $html .= '<th colspan="2"></th>';
                    $html .= '</tr>';
                    $html .= '<tr>';
                    $html .= '<th width="3%">N°</th>';
                    $html .= '<th>Fecha carga</th>';
                    $html .= '<th>Placa</th>';
                    $html .= '<th>Empresa Transporte</th>';
                    $html .= '<th>Volumen despacho</th>';
                    $html .= '<th>Ruta(Origen- Carga - Destino)</th>';
                    $html .= '<th>Fecha de recepción</th>';
                    $html .= '<th>Volumen recepción</th>';
                    $html .= '<th>Planta Descarga</th>';
                    $html .= '<th>Cre Descarga</th>';
                    $html .= '<th>Diferencia Desp-Rec</th>';
                    $html .= '<th>Merma Permisible</th>';
                    $html .= '<th>Merma</th>';
                    $html .= '<th>Volumen a Liquidar</th>';
                    $html .= '<th>Producto</th>';
                    $html .= '</tr>';
                    $html .= '</thead>';
                    $html .= '<tbody>';
                    $cont = 1;

                    $total1 = 0;
                    $total2 = 0;
                    $total3 = 0;
                    $total4 = 0;
                    $total5 = 0;
                    $total6 = 0;


                    if (count($viajes) > 0) {
                        foreach ($viajes as $viaje) {
                            $html_vehiculo = $viaje->programacion->vehiculo->placa;
                            if ($viaje->programacion->reemplazo == 1) {
                                $html_vehiculo = '<span class="txt-rojo">' . $viaje->programacion->vehiculo->placa . '</span>';
                                $html_vehiculo .= '<span>' . $viaje->programacion->vehiculo_remplazo->placa . '</span>';
                            }

                            $html .= '<tr>
                                <td>' . $cont++ . '</td>
                                <td>' . $viaje->fecha_carga . '</td>
                                <td>' . $html_vehiculo . '</td>
                                <td>' . $empresa->razon_social . '</td>
                                <td>' . $viaje->volumen_programado . '</td>
                                <td>' . $viaje->programacion->origen_destino . '</td>
                                <td>' . $viaje->fecha_descarga_t . '</td>
                                <td>' . $viaje->volumen_recepcionado . '</td>
                                <td>YPFBL - LA PAZ</td>
                                <td>' . $viaje->segun_cre . '</td>
                                <td>' . $viaje->dif_litros . '</td>
                                <td>' . $viaje->merma_ypfb . '</td>
                                <td>' . $viaje->mermas . '</td>
                                <td>' . $viaje->volumen_facturar . '</td>
                                <td>' . $viaje->programacion->producto->nombre . '</td>
                            </tr>';
                            $total1 += (float)$viaje->volumen_programado;
                            $total2 += (float)$viaje->volumen_recepcionado;
                            $total3 += (float)$viaje->dif_litros;
                            $total4 += (float)$viaje->merma_ypfb;
                            $total5 += (float)$viaje->mermas;
                            $total6 += (float)$viaje->volumen_facturar;
                        }
                    } else {
                        $html .= '<tr><td colspan="15" class="centreado">Sin registros</td></tr>';
                    }

                    // totales
                    $html .= '<tr class="bg-gray bold">
                        <td colspan="4" class="bold">TOTALES</td>
                        <td>' . $total1 . '</td>
                        <td></td>
                        <td></td>
                        <td>' . $total2 . '</td>
                        <td colspan="2"></td>
                        <td>' . $total3 . '</td>
                        <td>' . $total4 . '</td>
                        <td>' . $total5 . '</td>
                        <td>' . $total6 . '</td>
                        <td></td>
                    </tr>';

                    $html .= '</tbody>';
                    $html .= '</table>';

                    $html .= '<table>';
                    $html .= '<tbody>';
                    $html .= '<tr>
                        <td>Total cisternas</td>
                        <td colspan="3">' . count($viajes) . '</td>
                    <tr>';
                    $html .= '<tr>
                        <td>TOTAL VOLUMEN CARGADO EN ORIGEN EN CISTERNAS</td>
                        <td>' . $total1 . '</td>
                        <td>TOTAL VOLUMEN RECIBIDO EN TANQUES</td>
                        <td>' . $total2 . '</td>
                    <tr>';
                    $html .= '</tbody>';
                    $html .= '</table>';
                    $html .= '<p class="txt_info">Nota: LOS VOLUMENES EN ORIGEN Y LOS RECEPCIONADOS FUERON VERIFICADOS DE ACUERDO A FORMULARIOS CRE</p>';

                    $html .= '<table border="1" class="table_info">';
                    $html .= '<thead>';
                    $html .= '<tr>
                        <th>DESCRIPCIÓN</th>
                        <th>VOLUMEN EN LITROS</th>
                        <th>VOLUMEN EN M3</th>
                    </tr>';
                    $html .= '<tbody>';
                    $html .= '<tr>
                        <td>VOLUMEN CARGA EN PLANTA</td>
                        <td>' . $total1 . '</td>
                        <td>' . floor($total1 * 100) / 100 . '</td>
                    </tr>';
                    $html .= '<tr>
                        <td>VOLUMEN RECEPCIONADO EN DESTINO</td>
                        <td>' . $total2 . '</td>
                        <td>' . floor($total2 * 100) / 100 . '</td>
                    </tr>';
                    $html .= '<tr>
                        <td>MERMA ESTABLECIDA</td>
                        <td>' . $total4 . '</td>
                        <td>' . floor(($total4 / 100) * 100) / 100 . '</td>
                    </tr>';
                    $html .= '<tr>
                        <td>MERMA EXCEDENTE</td>
                        <td>' . $total5 . '</td>
                        <td>' . floor(($total5 / 100) * 100) / 100 . '</td>
                    </tr>';
                    $html .= '</tbody>';
                    $html .= '</thead>';
                    $html .= '</table>';

                    $html .= '<table border="1" class="table_info" style="margin-top:20px;">';
                    $html .= '<thead>';
                    $html .= '<tr>
                                <th>DESCRIPCIÓN</th>
                                <th>% CUMPLIMIENTO A PROGRAMACIÓN</th>
                                <th>OBSERVACIONES</th>
                            </tr>';
                    $html .= '</thead>';
                    $html .= '<tbody>';
                    $html .= '<tr>
                                <td>CUMPLIMIENTO CLAUSULA N° 15<br/>CONTRATO ' . $contrato->codigo . '</td>
                                <td>82%</td>
                                <td></td>
                            </tr>';
                    $html .= '</tbody>';
                    $html .= '</table>';



                    if ($key_empresa < count($empresas) - 1) {
                        $html .= '<div class="page-break"></div>';
                    }
                }

                if ($key < count($asociacions) - 1) {
                    $html .= '<div class="page-break"></div>';
                }
            }
            if ($key_contrato < count($contratos) - 1) {
                $html .= '<div class="page-break"></div>';
            }
        }


        $pdf = PDF::loadView('reportes.consolidacion_viajes_empresas', compact('html'))->setPaper('letter', 'landscape');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('consolidacion_viajes_empresas.pdf');
    }

    public function consolidacion_viajes_facturacion()
    {
        return Inertia::render("Reportes/ConsolidacionViajesFacturacion");
    }

    public function r_consolidacion_viajes_facturacion(Request $request)
    {
        $empresa_id =  $request->empresa_id;
        $asociacion_id =  $request->asociacion_id;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;

        $asociacions = Empresa::where("tipo", "ASOCIACIÓN");
        if ($asociacion_id != 'todos') {
            $asociacions->where("id", $asociacion_id);
        }
        $asociacions = $asociacions->get();

        $pdf = PDF::loadView('reportes.consolidacion_viajes_facturacion', compact('asociacions', 'fecha_ini', 'fecha_fin', "empresa_id"))->setPaper('legal', 'landscape');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('consolidacion_viajes_facturacion.pdf');
    }

    public function pagos_empresas()
    {
        return Inertia::render("Reportes/PagosEmpresas");
    }

    public function r_pagos_empresas(Request $request)
    {
        $empresa_id =  $request->empresa_id;
        $asociacion_id =  $request->asociacion_id;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;

        $pdf = PDF::loadView('reportes.pagos_empresas', compact('fecha_ini', 'fecha_fin', "asociacion_id", "empresa_id"))->setPaper('legal', 'landscape');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('pagos_empresas.pdf');
    }

    public function predicciones()
    {
        return Inertia::render("Reportes/Predicciones");
    }

    public function r_predicciones(Request $request)
    {
        $empresa_id =  $request->empresa_id;
        // $asociacion_id =  $request->asociacion_id;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;

        $empresas = Empresa::select("empresas.*");
        $empresas->where("tipo", 'EMPRESA');
        if ($empresa_id != 'todos') {
            $empresas->where("id", $empresa_id);
        }
        $empresas = $empresas->get();

        $fecha1 = date("Y-m", strtotime($fecha_ini . ' -3 month'));
        $fecha2 = date("Y-m", strtotime($fecha_ini . ' -2 month'));
        $fecha3 = date("Y-m", strtotime($fecha_ini . ' -1 month'));

        // volumenes por viajes y facturacion
        $data1 = [];
        $data2 = [];
        foreach ($empresas as $empresa) {
            $sum1 = Viaje::join("programacions", "programacions.id", "=", "viajes.programacion_id")
                ->where("programacions.empresa_id", $empresa->id)
                ->where("fecha_descarga", "LIKE", "$fecha1%")
                ->sum("viajes.volumen_cargado");

            $sum2 = Viaje::join("programacions", "programacions.id", "=", "viajes.programacion_id")
                ->where("programacions.empresa_id", $empresa->id)
                ->where("fecha_descarga", "LIKE", "$fecha2%")
                ->sum("viajes.volumen_cargado");

            $sum3 = Viaje::join("programacions", "programacions.id", "=", "viajes.programacion_id")
                ->where("programacions.empresa_id", $empresa->id)
                ->where("fecha_descarga", "LIKE", "$fecha3%")
                ->sum("viajes.volumen_cargado");

            $prom = ($sum1 + $sum2 + $sum3) / 3;
            $prom = round($prom, 2);
            $data1[] = [$empresa->razon_social, (float)$prom];

            $sum1 = Viaje::join("programacions", "programacions.id", "=", "viajes.programacion_id")
                ->where("programacions.empresa_id", $empresa->id)
                ->where("fecha_descarga", "LIKE", "$fecha1%")
                ->sum("viajes.importe_bs");

            $sum2 = Viaje::join("programacions", "programacions.id", "=", "viajes.programacion_id")
                ->where("programacions.empresa_id", $empresa->id)
                ->where("fecha_descarga", "LIKE", "$fecha2%")
                ->sum("viajes.importe_bs");

            $sum3 = Viaje::join("programacions", "programacions.id", "=", "viajes.programacion_id")
                ->where("programacions.empresa_id", $empresa->id)
                ->where("fecha_descarga", "LIKE", "$fecha3%")
                ->sum("viajes.importe_bs");

            $prom = ($sum1 + $sum2 + $sum3) / 3;
            $prom = round($prom, 2);
            $data2[] = [$empresa->razon_social, (float)$prom];
        }


        return response()->JSON([
            "data1" => $data1,
            "data2" => $data2
        ]);
    }
}
