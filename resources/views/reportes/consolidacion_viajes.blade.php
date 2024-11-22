<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Consolidacion de viajes</title>
    <style type="text/css">
        * {
            font-family: sans-serif;
        }

        @page {
            margin-top: 1cm;
            margin-bottom: 1cm;
            margin-left: 2cm;
            margin-right: 1cm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 20px;
            page-break-before: avoid;
        }

        table thead tr th,
        tbody tr td {
            padding: 3px;
            word-wrap: break-word;
        }

        table thead tr th {
            font-size: 8.5pt;
        }

        table tbody tr td {
            font-size: 7.5pt;
        }


        .encabezado {
            width: 100%;
        }

        .logo img {
            position: absolute;
            height: 100px;
            top: -20px;
            left: 0px;
        }

        h2.titulo {
            width: 450px;
            margin: auto;
            margin-top: 0PX;
            margin-bottom: 15px;
            text-align: center;
            font-size: 14pt;
        }

        .texto {
            width: 250px;
            text-align: center;
            margin: auto;
            margin-top: 15px;
            font-weight: bold;
            font-size: 1.1em;
        }

        .fecha {
            width: 250px;
            text-align: center;
            margin: auto;
            margin-top: 15px;
            font-weight: normal;
            font-size: 0.85em;
        }

        .total {
            text-align: right;
            padding-right: 15px;
            font-weight: bold;
        }

        table {
            width: 100%;
        }

        table thead {
            background: rgb(236, 236, 236)
        }

        tr {
            page-break-inside: avoid !important;
        }

        .centreado {
            padding-left: 0px;
            text-align: center;
        }

        .datos {
            margin-left: 15px;
            border-top: solid 1px;
            border-collapse: collapse;
            width: 250px;
        }

        .txt {
            font-weight: bold;
            text-align: right;
            padding-right: 5px;
        }

        .txt_center {
            font-weight: bold;
            text-align: center;
        }

        .cumplimiento {
            position: absolute;
            width: 150px;
            right: 0px;
            top: 86px;
        }

        .b_top {
            border-top: solid 1px black;
        }

        .gray {
            background: rgb(202, 202, 202);
        }

        .bg-principal {
            background: #669eb9;
            color: white;
        }

        .bg-gray {
            background: rgb(236, 236, 236)
        }

        .bold {
            font-weight: bold;
        }

        .img_celda img {
            width: 45px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    @inject('configuracion', 'App\Models\Configuracion')
    <div class="encabezado">
        <div class="logo">
            <img src="{{ $configuracion->first()->logo_b64 }}">
        </div>
        <h2 class="titulo">
            {{ $configuracion->first()->razon_social }}
        </h2>
        <h4 class="texto">CONSOLIDACIÓN DE VIAJES</h4>
        <h4 class="fecha">Expedido: {{ date('d-m-Y') }}</h4>
    </div>

    @php
        $contador = 0;
    @endphp

    @foreach ($viajes as $key => $viaje)
        <table border="1">
            <tbody>
                <tr>
                    <td class="bg-gray bold" width="25%">N°</td>
                    <td>{{ $key + 1 }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">CONDUCTOR</td>
                    <td>{{ $viaje->programacion->conductor->full_name }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">LICENCIA</td>
                    <td>{{ $viaje->programacion->conductor->nro_licencia }}
                        CAT. {{ $viaje->programacion->conductor->categoria }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">PLACA</td>
                    <td>{{ $viaje->programacion->vehiculo->placa }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">MARCA</td>
                    <td>{{ $viaje->programacion->vehiculo->marca }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">COLOR</td>
                    <td>{{ $viaje->programacion->vehiculo->color }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">VOL. PROG.</td>
                    <td>{{ $viaje->volumen_programado }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">TRAMO</td>
                    <td>{{ $viaje->tramo }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">FRONTERA</td>
                    <td>{{ $viaje->programacion->frontera }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">EMPRESA</td>
                    <td>{{ $viaje->programacion->empresa->razon_social }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">FECHA DE PROGRAMACIÓN</td>
                    <td>{{ $viaje->programacion->fecha_programacion_t }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">SOCIEDAD</td>
                    <td>{{ $viaje->programacion->asociacion->razon_social }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">NOMINA</td>
                    <td>{{ $viaje->nomina }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">RESOLUCIÓN</td>
                    <td>{{ $viaje->resolucion }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">PRODUCTO</td>
                    <td>{{ $viaje->programacion->producto->nombre }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">DIM</td>
                    <td>{{ $viaje->dim }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">ESTADO</td>
                    <td>{{ $viaje->estado }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">OBSERVACIONES</td>
                    <td>{{ $viaje->observaciones }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">FECHA CARGA</td>
                    <td>{{ $viaje->fecha_carga }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">VOL. CARGADO</td>
                    <td>{{ $viaje->volumen_cargado }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">TOTAL</td>
                    <td>{{ $viaje->total }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">CRE DE CARGA N°</td>
                    <td>{{ $viaje->cre_carga }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">VOL. RECEP.</td>
                    <td>{{ $viaje->volumen_recepcionado }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">TOTAL 3</td>
                    <td>{{ $viaje->total2 }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">MERMAS</td>
                    <td>{{ $viaje->mermas }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">DIF. EN LITROS</td>
                    <td>{{ $viaje->dif_litros }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">MERMA 0.15% Y.P.F.B.</td>
                    <td>{{ $viaje->merma_ypfb }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">MERMAS POR COBRAR</td>
                    <td>{{ $viaje->merma_cobrar }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">VOL. A FACT.</td>
                    <td>{{ $viaje->volumen_facturar }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">FECHA DE DESCARGA</td>
                    <td>{{ $viaje->fecha_descarga_t }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">SEGUN CRE N°</td>
                    <td>{{ $viaje->segun_cre }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">FACT./LOTE</td>
                    <td>{{ $viaje->factura_lote }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">ARICA - TRAMO QUEMADO - LA PAZ</td>
                    <td>{{ $viaje->atq_lapaz }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">MES SERVICIO</td>
                    <td>{{ $viaje->mes_servicio }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">DIM</td>
                    <td>{{ $viaje->dim2 }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">CRT</td>
                    <td>{{ $viaje->crt }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">VOL. CRT M3</td>
                    <td>{{ $viaje->vol_crtm3 }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">PESO CRT</td>
                    <td>{{ $viaje->peso_crt }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">PLANTA DE CARGA SEGÚN CRT (CAMPO 7)</td>
                    <td>{{ $viaje->planta_carga_crt }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">FECHA CRUCE FRONTERA</td>
                    <td>{{ $viaje->fecha_cruce_frontera_t }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">MIC/DTA</td>
                    <td>{{ $viaje->mic_dta }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">VOL. SEGÚN MIC</td>
                    <td>{{ $viaje->vol_mic }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">PESO SEGÚN MIC</td>
                    <td>{{ $viaje->peso_mic }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">PARTE DE RECEPCIÓN</td>
                    <td>{{ $viaje->parte_recepcion }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">VOL. SEGÚN PARTE M3</td>
                    <td>{{ $viaje->vol_parte_mic }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">VOL. SEGÚN PARTE LTS</td>
                    <td>{{ $viaje->vol_parte_lts }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">PESO PARTE</td>
                    <td>{{ $viaje->peso_parte }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">OBSERVACIONES</td>
                    <td>{{ $viaje->observaciones2 }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">NUM. DE SOLICITUD HR</td>
                    <td>{{ $viaje->nro_solicitud_hr }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">NUM. DE RUTA</td>
                    <td>{{ $viaje->nro_ruta }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">FECHA DE HR</td>
                    <td>{{ $viaje->fecha_hr_t }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">OBSERVACIONES</td>
                    <td>{{ $viaje->observaciones3 }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">NUM. FACT. ALBO/DAB</td>
                    <td>{{ $viaje->nro_fac_albodab }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">FECHA DE FACT.</td>
                    <td>{{ $viaje->fecha_hr_t }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">IMPORTE EN BS.</td>
                    <td>{{ $viaje->importe_bs }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">OBSERVACIONES</td>
                    <td>{{ $viaje->observaciones4 }}</td>
                </tr>
                <tr>
                    <td class="bg-gray bold">OBS. GENERAL</td>
                    <td>{{ $viaje->observaciones_general }}</td>
                </tr>
            </tbody>
        </table>

        @php
            $contador++;
        @endphp
        @if ($contador < count($viajes))
            <div class="page-break"></div>
        @endif
    @endforeach
</body>

</html>
