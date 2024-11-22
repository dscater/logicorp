<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Consolidacion de pagos para facturacion</title>
    <style type="text/css">
        * {
            font-family: sans-serif;
        }

        @page {
            margin-top: 1.5cm;
            margin-bottom: 1cm;
            margin-left: 1cm;
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
            font-size: 7.5pt;
        }

        table tbody tr td {
            font-size: 6.5pt;
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

        .text-right {
            text-align: right;
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
        <h4 class="texto">CONSOLIDACIÓN DE VIAJES PARA FACTURACIÓN</h4>
        <h4 class="fecha">Expedido: {{ date('d-m-Y') }}</h4>
    </div>

    <table border="1">
        <thead>
            <tr>
                <th width="3%">N°</th>
                <th>MES</th>
                <th>CTO</th>
                <th>SOCIEDAD</th>
                <th>VOL. CARG.</th>
                <th>FECHA</th>
                <th>EMPRESA</th>
                <th>PRODUCTO</th>
                <th>TRAMO</th>
                <th>RETENCIÓN 7%</th>
                <th>DESC. MERMA</th>
                <th>TOTAL PAGO</th>
            </tr>
        </thead>
        <tbody>
            @php
                $pagos = App\Models\Pago::select('pagos.*')->join(
                    'programacions',
                    'programacions.id',
                    '=',
                    'pagos.programacion_id',
                );

                if ($empresa_id != 'todos') {
                    $pagos->where('programacions.empresa_id', $empresa_id);
                }

                if ($asociacion_id != 'todos') {
                    $pagos->where('programacions.asociacion_id', $asociacion_id);
                }

                if ($fecha_ini && $fecha_fin) {
                    $pagos->whereBetween('pagos.fecha_registro', [$fecha_ini, $fecha_fin]);
                }
                $pagos = $pagos->get();
                $cont = 1;
                $total = 0;
            @endphp
            @foreach ($pagos as $key_pago => $pago)
                <tr>
                    <td>{{ $cont++ }}</td>
                    <td>{{ $pago->mes_anio }}</td>
                    <td>{{ $pago->cto }}</td>
                    <td>{{ $pago->programacion->asociacion->razon_social }}</td>
                    <td>{{ $pago->viaje->volumen_cargado }}</td>
                    <td>{{ $pago->fecha_registro_t }}</td>
                    <td>{{ $pago->programacion->empresa->razon_social }}</td>
                    <td>{{ $pago->programacion->producto->nombre }}</td>
                    <td>{{ $pago->viaje->tramo }}</td>
                    <td>{{ $pago->retencion }}</td>
                    <td>{{ $pago->desc_merma }}</td>
                    <td>{{ $pago->total_pagado }}</td>
                    @php
                        $total += (float) $pago->total_pagado;
                    @endphp
                </tr>
            @endforeach
            <tr>
                <td class="bold text-right" colspan="11">TOTAL</td>
                <td class="bold">{{ number_format($total, 2, '.', '') }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
