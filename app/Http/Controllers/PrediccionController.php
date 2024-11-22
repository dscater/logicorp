<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Viaje;
use Illuminate\Http\Request;

class PrediccionController extends Controller
{
    // Función para entrenar el modelo de series temporales
    public function trainModel($modelo)
    {
        $viajes = Viaje::orderBy('fecha', 'asc')->get(); // Ordenar por fecha
        $samples = [];
        $targets = [];
        $lag = 3; // Número de valores anteriores (lags) a considerar

        // Generar samples y targets para series temporales
        foreach ($viajes as $index => $viaje) {
            if ($index >= $lag) {
                $lagValues = [];
                for ($i = 1; $i <= $lag; $i++) {
                    switch ($modelo) {
                        case 'volumen':
                            $lagValues[] = $viajes[$index - $i]->volumen_cargado;
                            break;
                        case 'facturacion':
                            $lagValues[] = $viajes[$index - $i]->importe_total;
                            break;
                    }
                }
                $samples[] = $lagValues;
                $targets[] = ($modelo === 'volumen') ? $viaje->volumen_cargado : $viaje->importe_total;
            }
        }

        // Entrenar el modelo
        $regression = new LeastSquares();
        $regression->train($samples, $targets);

        // Guardar el modelo como JSON
        $modelData = [
            'coefficients' => $regression->getCoefficients(),
            'intercept' => $regression->getIntercept(),
            'lag' => $lag
        ];

        file_put_contents(storage_path("app/{$modelo}_model.json"), json_encode($modelData));

        return true;
    }

    // Función para predecir usando un modelo de series temporales
    public function predictt($modelo, $input)
    {
        $modelFile = storage_path("app/{$modelo}_model.json");
        if (!file_exists($modelFile)) {
            throw new \Exception('Modelo no entrenado');
        }

        $modelData = json_decode(file_get_contents($modelFile), true);

        $coefficients = $modelData['coefficients'];
        $intercept = $modelData['intercept'];
        $lag = $modelData['lag'];

        // Validar que el número de valores de entrada coincida con el lag
        if (count($input) !== $lag) {
            throw new \Exception("Se requieren exactamente {$lag} valores de entrada.");
        }

        // Calcular la predicción usando el modelo
        $prediction = $intercept;
        foreach ($input as $key => $value) {
            $prediction += $coefficients[$key] * $value;
        }

        return $prediction;
    }

    public function generar_predicciones($params)
    {
        $empresa_id =  $params->empresa_id;
        $empresas = Empresa::select("empresas.*");
        $empresas->where("tipo", 'EMPRESA');
        if ($empresa_id != 'todos') {
            $empresas->where("id", $empresa_id);
        }
        $empresas = $empresas->get();
        // volumenes por viajes y facturacion
        $data1 = [];
        $data2 = [];
        foreach ($empresas as $empresa) {
            // VOLUMEN
            // entrenar el modelo
            $this->trainModel("volumen");
            // cargar los datos de prediccion
            $empresas = Empresa::select("empresas.*");
            if ($empresa_id != 'todos') {
                $empresas->where("id", $empresa_id);
            }
            $empresas = $empresas->get();
            $input1 = [];
            $input2 = [];
            foreach ($empresas as $empresa) {
                $sum = Viaje::join("programacions", "programacions.id", "=", "viajes.programacion_id")
                    ->where("programacions.empresa_id", $empresa->id)
                    ->sum("viajes.volumen_cargado");

                $input1[] = [$sum, count($empresas)];

                $sum = Viaje::join("programacions", "programacions.id", "=", "viajes.programacion_id")
                    ->where("programacions.empresa_id", $empresa->id)
                    ->sum("viajes.importe_bs");
                $input2[] = [$sum, count($empresas)];
            }
            // obtener el resultado del modelo
            $total_res = $this->predict("volumen", $input1);
            $data1[] = [$empresa->razon_social, $total_res];
            // FACTURACION
            // entrenar el modelo
            $this->trainModel("facturacion");
            // obtener el resultado del modelo
            $total_res = $this->predict("volumen", $input2);
            $data2[] = [$empresa->razon_social, $total_res];
        }
        return [$data1, $data2];
    }
}
