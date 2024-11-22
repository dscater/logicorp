<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Consolidacion de viajes para facturacion</title>
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

    @php
        $contador = 0;
    @endphp

    @foreach ($asociacions as $key => $asociacion)
        @php
            $contratos = App\Models\Contrato::where('empresa_id', $asociacion->id)->get();
        @endphp
        @foreach ($contratos as $key_contrato => $contrato)
            <table>
                <tbody>
                    <tr>
                        <td class="bold centreado">EMPRESA/SOCIEDAD</td>
                        <td class="bold centreado">CONTRATO</td>
                        <td class="bold centreado">MES</td>
                        <td class="bold centreado">FECHA</td>
                    </tr>
                    <tr>
                        <td class="centreado">{{ $contrato->empresa->razon_social }}</td>
                        <td class="centreado">{{ $contrato->codigo }}</td>
                        <td class="centreado">{{ $contrato->mes_anio }}</td>
                        <td class="centreado">{{ $contrato->fecha_registro_t }}</td>
                    </tr>
                </tbody>
            </table>
            <table border="1">
                <thead>
                    <tr>
                        <th width="3%">N°</th>
                        <th>ID</th>
                        <th>FECHA CARGA</th>
                        <th>CONDUCTOR</th>
                        <th>PLACA</th>
                        <th>PRODUCTO</th>
                        <th>TRAMO</th>
                        <th>EMPRESA</th>
                        <th>PROVEEDOR</th>
                        <th>VOLUMEN CARGADO M3</th>
                        <th>VOL. RECEP. INDIVIDUAL</th>
                        <th>DIF. EN LITROS</th>
                        <th>MERMA PERMISIBLE Y.P.F.B.</th>
                        <th>MERMAS POR COBRAR</th>
                        <th>VOL. A FACTURAR</th>
                        <th>FECHA DE DESCARGA</th>
                        <th>CRE DE DESCARGA N°</th>
                        <th>ASOCIACIÓN</th>
                        <th>PRODUCTO</th>
                        <th>IMPORTE BS.</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $viajes = App\Models\Viaje::select('viajes.*')
                            ->join('programacions', 'programacions.id', '=', 'viajes.programacion_id')
                            ->where('programacions.contrato_id', $contrato->id)
                            ->where('programacions.asociacion_id', $asociacion->id);

                        if ($empresa_id != 'todos') {
                            $viajes->where('programacions.empresa_id', $empresa_id);
                        }

                        if ($fecha_ini && $fecha_fin) {
                            $viajes->whereBetween('viajes.fecha_descarga', [$fecha_ini, $fecha_fin]);
                        }
                        $viajes = $viajes->get();
                        $cont = 1;
                        $total = 0;
                    @endphp
                    @foreach ($viajes as $key_viaje => $viaje)
                        <tr>
                            <td>{{ $cont++ }}</td>
                            <td>{{ $viaje->id }}</td>
                            <td>{{ $viaje->fecha_carga_t }}</td>
                            <td>{{ $viaje->programacion->conductor->full_name }}</td>
                            <td>{{ $viaje->programacion->vehiculo->placa }}</td>
                            <td>{{ $viaje->programacion->producto->nombre }}</td>
                            <td>{{ $viaje->tramo }}</td>
                            <td>{{ $viaje->programacion->empresa->razon_social }}</td>
                            <td>{{ $viaje->programacion->proveedor->razon_social }}</td>
                            <td>{{ $viaje->volumen_cargado }}</td>
                            <td>{{ $viaje->volumen_recepcionado }}</td>
                            <td>{{ $viaje->dif_litros }}</td>
                            <td>{{ $viaje->mermas }}</td>
                            <td>{{ $viaje->merma_cobrar }}</td>
                            <td>{{ $viaje->volumen_facturar }}</td>
                            <td>{{ $viaje->fecha_descarga_t }}</td>
                            <td>{{ $viaje->cre_carga }}</td>
                            <td>{{ $asociacion->razon_social }}</td>
                            <td>{{ $viaje->programacion->producto->nombre }}</td>
                            <td>{{ $viaje->importe_bs }}</td>
                            @php
                                $total += (float) $viaje->importe_bs;
                            @endphp
                        </tr>
                    @endforeach
                    <tr>
                        <td class="bold text-right" colspan="19">TOTAL</td>
                        <td class="bold">{{ number_format($total, 2, '.', '') }}</td>
                    </tr>
                </tbody>
            </table>

            @if ($key_contrato < count($contratos) - 1)
                <div class="page-break"></div>
            @endif
        @endforeach

        @php
            $contador++;
        @endphp
        @if ($contador < count($asociacions))
            <div class="page-break"></div>
        @endif
    @endforeach
</body>

</html>
