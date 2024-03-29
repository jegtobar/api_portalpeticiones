<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardMntoSatisfechoController extends Controller
{

  //Función para mostrar la meta global de la región mantenimiento vecinos satisfechos
  function getMetaRegionMntoSatisfechos() //Modificado 15/07/2022
  {
    $query = "SELECT COUNT(a.id)AS actual, (SELECT SUM(b.meta)FROM metas_mnto_satisfechos b)AS meta
    FROM personas a
    WHERE a.seguimiento = 2 AND a.deleted_at IS NULL";
    $metaRegion = DB::select($query);

    $query = "SELECT COUNT(a.id)AS actual, (SELECT SUM(b.meta)FROM metas_mnto_satisfechos b WHERE b.alcaldia_id = a.zona_id)AS meta, c.alcaldia
    FROM personas a
    INNER JOIN alcaldias c ON c.id = a.zona_id
    WHERE a.seguimiento = 2 AND a.deleted_at IS NULL
    GROUP BY a.zona_id";

    $metasPorAlcaldias = DB::select($query);
    $acumulados = 0;
    foreach ($metasPorAlcaldias as $meta) {
      if ($meta->meta < $meta->actual) {
        $actual = $meta->actual - $meta->meta;
        $acumulados = $acumulados + $actual;
      }
    }
    foreach ($metaRegion as $meta) {
      if ($meta->meta <  $acumulados) {
        $actual =  $acumulados - $meta->meta;
        $meta->actual = $meta->meta;
        $chart = [
          "chart" => [
            "type" => "gauge",
          ],
          "title" => [
            "text" => 'Región 1' . '<br/> Estado: ' . intval($meta->meta) . '<br/><p style="color:#10069f"> Actual: +' . $actual . '</p>'
          ],
          "pane" => [
            "startAngle" => -150,
            "endAngle" => 150,
          ],
          "yAxis" => [
            "min" => 0,
            "max" => intval($meta->meta),
            "minorTickInterval" => "auto",
            "minorTickWidth" => 1,
            "minorTickLength" => 10,
            "minorTickPosition" => "inside",
            "minorTickColor" => "#666",
            "tickPixelInterval" => 30,
            "tickWidth" => 2,
            "tickPosition" => "inside",
            "tickLength" => 10,
            "tickColor" => "#666",
            "labels" => [
              "step" => 2,
              "rotation" => "auto"
            ],
            "title" => [
              "text" => "Vecinos"
            ],
            "plotBands" => [
              [
                "from" => 0,
                "to" => $meta->meta * 0.3333,
                "color" => "#DF5353" // red
              ],
              [
                "from" => $meta->meta * 0.3333,
                "to" => $meta->meta * 0.66666,
                "color" => "#DDDF0D" // yellow
              ],
              [
                "from" => $meta->meta * 0.66666,
                "to" => $meta->meta,
                "color" => "#55BF3B" // green
              ]
            ]
          ],
          "series" => [
            [
              "name" => "Satisfechos",
              "data" => [intval($meta->meta)],
              "tooltip" => [
                "valueSuffix" => "vecinos"
              ]
            ]
          ],
          "credits" => [
            [
              "enabled" => "false"
            ]
          ]
        ];
      } else {
        $metaActual = $meta->actual - $acumulados;
        $chart = [
          "chart" => [
            "type" => "gauge",
          ],
          "title" => [
            "text" => 'Región 1' . '<br/> Estado: ' . intval($meta->meta)
          ],
          "pane" => [
            "startAngle" => -150,
            "endAngle" => 150,
          ],
          "yAxis" => [
            "min" => 0,
            "max" => intval($meta->meta),
            "minorTickInterval" => "auto",
            "minorTickWidth" => 1,
            "minorTickLength" => 10,
            "minorTickPosition" => "inside",
            "minorTickColor" => "#666",
            "tickPixelInterval" => 30,
            "tickWidth" => 2,
            "tickPosition" => "inside",
            "tickLength" => 10,
            "tickColor" => "#666",
            "labels" => [
              "step" => 2,
              "rotation" => "auto"
            ],
            "title" => [
              "text" => "Vecinos"
            ],
            "plotBands" => [
              [
                "from" => 0,
                "to" => $meta->meta * 0.3333,
                "color" => "#DF5353" // red
              ],
              [
                "from" => $meta->meta * 0.3333,
                "to" => $meta->meta * 0.66666,
                "color" => "#DDDF0D" // yellow
              ],
              [
                "from" => $meta->meta * 0.66666,
                "to" => $meta->meta,
                "color" => "#55BF3B" // green
              ]
            ]
          ],
          "series" => [
            [
              "name" => "Satisfechos",
              "data" => [intval($metaActual)],
              "tooltip" => [
                "valueSuffix" => "vecinos"
              ]
            ]
          ],
          "credits" => [
            [
              "enabled" => "false"
            ]
          ]
        ];
      }
      $meta->chart = $chart;
    }



    return response()->json($metaRegion);
  }


  //Funcion para mostrar las metas de vecinos satisfechos de las alcaldias auxiliares
  function getMetasAlcaldias() //Modificado 15/07/2022
  {
    $query = "SELECT COUNT(a.id)AS actual, (SELECT SUM(b.meta)FROM metas_mnto_satisfechos b WHERE b.alcaldia_id = a.zona_id)AS meta, c.alcaldia, a.zona_id
    FROM personas a
    INNER JOIN alcaldias c ON c.id = a.zona_id
    WHERE a.seguimiento = 2 AND a.deleted_at IS NULL
    GROUP BY a.zona_id";
    $metas = DB::select($query);
    
    foreach ($metas as $meta) {
      $acumulado = 0;
      $query = "SELECT COUNT(a.id)AS actual, (SELECT SUM(b.meta)FROM metas_mnto_satisfechos b WHERE b.colonia_id = a.colonia_id)AS meta, c.colonia
      FROM personas a
      INNER JOIN colonias c ON c.id = a.colonia_id
      WHERE a.seguimiento = 2 AND a.deleted_at IS NULL AND a.zona_id = $meta->zona_id
      GROUP BY a.colonia_id";
      $res = DB::Select($query);
      foreach ($res as $resultado) {
        if($resultado->meta < $resultado->actual){
          $a = $resultado->actual - $resultado->meta;
          $acumulado = $acumulado + $a;
        }
      }
      if (intval($meta->meta) < $acumulado) {

        $adicional = $acumulado - intval($meta->meta);

        $meta->actual = $meta->meta;

        $chart = [
          "chart" => [
            "type" => "gauge",
          ],
          "title" => [
            "text" => $meta->alcaldia . '<br/> Estado: ' . intval($meta->meta) . '<br/><p style="color:#10069f"> Actual: +' . $adicional . '</p>'
          ],
          "pane" => [
            "startAngle" => -150,
            "endAngle" => 150,
          ],
          "yAxis" => [
            "min" => 0,
            "max" => intval($meta->meta),
            "minorTickInterval" => "auto",
            "minorTickWidth" => 1,
            "minorTickLength" => 10,
            "minorTickPosition" => "inside",
            "minorTickColor" => "#666",
            "tickPixelInterval" => 30,
            "tickWidth" => 2,
            "tickPosition" => "inside",
            "tickLength" => 10,
            "tickColor" => "#666",
            "labels" => [
              "step" => 2,
              "rotation" => "auto"
            ],
            "title" => [
              "text" => "Vecinos"
            ],
            "plotBands" => [
              [
                "from" => 0,
                "to" => $meta->meta * 0.3333,
                "color" => "#DF5353" // red
              ],
              [
                "from" => $meta->meta * 0.3333,
                "to" => $meta->meta * 0.66666,
                "color" => "#DDDF0D" // yellow
              ],
              [
                "from" => $meta->meta * 0.66666,
                "to" => $meta->meta,
                "color" => "#55BF3B" // green
              ]
            ]
          ],
          "series" => [
            [
              "name" => "Satisfechos",
              "data" => [intval($meta->meta)],
              "tooltip" => [
                "valueSuffix" => "vecinos"
              ]
            ]
          ],
          "credits" => [
            [
              "enabled" => "false"
            ]
          ]
        ];
      } else {
        $actual = intval($meta->actual - $acumulado);
        $chart = [
          "chart" => [
            "type" => "gauge",
          ],
          "title" => [
            "text" => $meta->alcaldia . '<br/> Estado: ' . intval($meta->meta)
          ],
          "pane" => [
            "startAngle" => -150,
            "endAngle" => 150,
          ],
          "yAxis" => [
            "min" => 0,
            "max" => intval($meta->meta),
            "minorTickInterval" => "auto",
            "minorTickWidth" => 1,
            "minorTickLength" => 10,
            "minorTickPosition" => "inside",
            "minorTickColor" => "#666",
            "tickPixelInterval" => 30,
            "tickWidth" => 2,
            "tickPosition" => "inside",
            "tickLength" => 10,
            "tickColor" => "#666",
            "labels" => [
              "step" => 2,
              "rotation" => "auto"
            ],
            "title" => [
              "text" => "Vecinos"
            ],
            "plotBands" => [
              [
                "from" => 0,
                "to" => $meta->meta * 0.3333,
                "color" => "#DF5353" // red
              ],
              [
                "from" => $meta->meta * 0.3333,
                "to" => $meta->meta * 0.66666,
                "color" => "#DDDF0D" // yellow
              ],
              [
                "from" => $meta->meta * 0.66666,
                "to" => $meta->meta,
                "color" => "#55BF3B" // green
              ]
            ]
          ],
          "series" => [
            [
              "name" => "Satisfechos",
              "data" => [intval($actual)],
              "tooltip" => [
                "valueSuffix" => "vecinos"
              ]
            ]
          ],
          "credits" => [
            [
              "enabled" => "false"
            ]
          ]
        ];
      }
      $meta->chart = $chart;
    }

    return response()->json($metas);
  }


  function getMetasAlcaldiasById(int $id)
  {
    $metas = DB::table('metas_mnto_satisfechos')
      ->select(DB::raw('SUM(metas_mnto_satisfechos.meta)AS meta, SUM(metas_mnto_satisfechos.actual)AS actual'), 'alcaldias.alcaldia')
      ->join('alcaldias', 'alcaldias.id', '=', 'metas_mnto_satisfechos.alcaldia_id')
      ->where('metas_mnto_satisfechos.alcaldia_id', '=', $id)
      ->groupBy('alcaldias.alcaldia')
      ->get();

    foreach ($metas as $meta) {
      if ($meta->meta < $meta->actual) {
        $actual = $meta->actual - $meta->meta;
        $meta->actual = $meta->meta;
        $chart = [
          "chart" => [
            "type" => "gauge",
          ],
          "title" => [
            "text" => $meta->alcaldia . '<br/> Estado: ' . intval($meta->meta) . '<br/><p style="color:#10069f"> Actual: +' . $actual . '</p>'
          ],
          "pane" => [
            "startAngle" => -150,
            "endAngle" => 150,
          ],
          "yAxis" => [
            "min" => 0,
            "max" => intval($meta->meta),
            "minorTickInterval" => "auto",
            "minorTickWidth" => 1,
            "minorTickLength" => 10,
            "minorTickPosition" => "inside",
            "minorTickColor" => "#666",
            "tickPixelInterval" => 30,
            "tickWidth" => 2,
            "tickPosition" => "inside",
            "tickLength" => 10,
            "tickColor" => "#666",
            "labels" => [
              "step" => 2,
              "rotation" => "auto"
            ],
            "title" => [
              "text" => "Vecinos"
            ],
            "plotBands" => [
              [
                "from" => 0,
                "to" => $meta->meta * 0.3333,
                "color" => "#DF5353" // red
              ],
              [
                "from" => $meta->meta * 0.3333,
                "to" => $meta->meta * 0.66666,
                "color" => "#DDDF0D" // yellow
              ],
              [
                "from" => $meta->meta * 0.66666,
                "to" => $meta->meta,
                "color" => "#55BF3B" // green
              ]
            ]
          ],
          "series" => [
            [
              "name" => "Satisfechos",
              "data" => [intval($meta->meta)],
              "tooltip" => [
                "valueSuffix" => "vecinos"
              ]
            ]
          ],
          "credits" => [
            [
              "enabled" => "false"
            ]
          ]
        ];
      } else {
        $chart = [
          "chart" => [
            "type" => "gauge",
          ],
          "title" => [
            "text" => $meta->alcaldia . '<br/> Estado: ' . intval($meta->meta)
          ],
          "pane" => [
            "startAngle" => -150,
            "endAngle" => 150,
          ],
          "yAxis" => [
            "min" => 0,
            "max" => intval($meta->meta),
            "minorTickInterval" => "auto",
            "minorTickWidth" => 1,
            "minorTickLength" => 10,
            "minorTickPosition" => "inside",
            "minorTickColor" => "#666",
            "tickPixelInterval" => 30,
            "tickWidth" => 2,
            "tickPosition" => "inside",
            "tickLength" => 10,
            "tickColor" => "#666",
            "labels" => [
              "step" => 2,
              "rotation" => "auto"
            ],
            "title" => [
              "text" => "Vecinos"
            ],
            "plotBands" => [
              [
                "from" => 0,
                "to" => $meta->meta * 0.3333,
                "color" => "#DF5353" // red
              ],
              [
                "from" => $meta->meta * 0.3333,
                "to" => $meta->meta * 0.66666,
                "color" => "#DDDF0D" // yellow
              ],
              [
                "from" => $meta->meta * 0.66666,
                "to" => $meta->meta,
                "color" => "#55BF3B" // green
              ]
            ]
          ],
          "series" => [
            [
              "name" => "Satisfechos",
              "data" => [intval($meta->actual)],
              "tooltip" => [
                "valueSuffix" => "vecinos"
              ]
            ]
          ],
          "credits" => [
            [
              "enabled" => "false"
            ]
          ]
        ];
      }
      $meta->chart = $chart;
    }



    return response()->json($metas);
  }
  //Funcion para mostrar las metas de vecinos satisfechos de todas las colonias según distrito (aplica para zona 1 y 21)
  function getMetasSatisfechosByDistrito(int $id, int $distrito) 
  {
    $query = "SELECT COUNT(a.id)AS actual, (SELECT SUM(b.meta)FROM metas_mnto_satisfechos b WHERE b.colonia_id = a.colonia_id)AS meta, c.colonia
    FROM personas a
    INNER JOIN colonias c ON c.id = a.colonia_id
    WHERE a.seguimiento = 2 AND a.deleted_at IS NULL AND a.zona_id = $id AND c.distrito_id = $distrito
    GROUP BY a.colonia_id";
    $metas = DB::select($query);

    foreach ($metas as $meta) {
      if ($meta->meta < $meta->actual) {
        $actual = $meta->actual - $meta->meta;

        $meta->actual = $meta->meta;

        $chart = [
          "chart" => [
            "type" => "gauge",
          ],
          "title" => [
            "text" => $meta->colonia . '<br/> Estado: ' . intval($meta->meta) . '<br/> <p style="color:#10069f"> Actual: +' . $actual . '</p>'
          ],
          "pane" => [
            "startAngle" => -150,
            "endAngle" => 150,
          ],
          "yAxis" => [
            "min" => 0,
            "max" => intval($meta->meta),
            "minorTickInterval" => "auto",
            "minorTickWidth" => 1,
            "minorTickLength" => 10,
            "minorTickPosition" => "inside",
            "minorTickColor" => "#666",
            "tickPixelInterval" => 30,
            "tickWidth" => 2,
            "tickPosition" => "inside",
            "tickLength" => 10,
            "tickColor" => "#666",
            "labels" => [
              "step" => 2,
              "rotation" => "auto"
            ],
            "title" => [
              "text" => "Vecinos"
            ],
            "plotBands" => [
              [
                "from" => 0,
                "to" => $meta->meta * 0.3333,
                "color" => "#DF5353" // red
              ],
              [
                "from" => $meta->meta * 0.3333,
                "to" => $meta->meta * 0.66666,
                "color" => "#DDDF0D" // yellow
              ],
              [
                "from" => $meta->meta * 0.66666,
                "to" => $meta->meta,
                "color" => "#55BF3B" // green
              ]
            ]
          ],
          "series" => [
            [
              "name" => "Satisfechos",
              "data" => [intval($meta->meta)],
              "tooltip" => [
                "valueSuffix" => "vecinos"
              ]
            ]
          ],
          "credits" => [
            [
              "enabled" => "false"
            ]
          ]
        ];
      } else {
        $chart = [
          "chart" => [
            "type" => "gauge",
          ],
          "title" => [
            "text" => $meta->colonia . '<br/> Estado: ' . intval($meta->meta)
          ],
          "pane" => [
            "startAngle" => -150,
            "endAngle" => 150,
          ],
          "yAxis" => [
            "min" => 0,
            "max" => $meta->meta,
            "minorTickInterval" => "auto",
            "minorTickWidth" => 1,
            "minorTickLength" => 10,
            "minorTickPosition" => "inside",
            "minorTickColor" => "#666",
            "tickPixelInterval" => 30,
            "tickWidth" => 2,
            "tickPosition" => "inside",
            "tickLength" => 10,
            "tickColor" => "#666",
            "labels" => [
              "step" => 2,
              "rotation" => "auto"
            ],
            "title" => [
              "text" => "Vecinos"
            ],
            "plotBands" => [
              [
                "from" => 0,
                "to" => $meta->meta * 0.3333,
                "color" => "#DF5353" // red
              ],
              [
                "from" => $meta->meta * 0.3333,
                "to" => $meta->meta * 0.66666,
                "color" => "#DDDF0D" // yellow
              ],
              [
                "from" => $meta->meta * 0.66666,
                "to" => $meta->meta,
                "color" => "#55BF3B" // green
              ]
            ]
          ],
          "series" => [
            [
              "name" => "Satisfechos",
              "data" => [$meta->actual],
              "tooltip" => [
                "valueSuffix" => "vecinos"
              ]
            ]
          ],
          "credits" => [
            [
              "enabled" => "false"
            ]
          ]
        ];
      }
      $meta->chart = $chart;
    }



    return response()->json($metas);
  }

  //Metas satisfechos por colonias según alcaldia
  function getMetasSatisfechosByColonia(int $id)
  {
    $query = "SELECT COUNT(a.id)AS actual, (SELECT SUM(b.meta)FROM metas_mnto_satisfechos b WHERE b.colonia_id = a.colonia_id)AS meta, c.colonia
    FROM personas a
    INNER JOIN colonias c ON c.id = a.colonia_id
    WHERE a.seguimiento = 2 AND a.deleted_at IS NULL AND a.zona_id = $id 
    GROUP BY a.colonia_id";
    $metas = DB::select($query);
    foreach ($metas as $meta) {
      if ($meta->meta < $meta->actual) {
        $actual = $meta->actual - $meta->meta;
        $meta->actual = $meta->meta;
        $chart = [
          "chart" => [
            "type" => "gauge",
          ],
          "title" => [
            "text" => $meta->colonia . '<br/> Estado: ' . intval($meta->meta) . '<br/><p style="color:#10069f"> Actual: +' . $actual . '</p>'
          ],
          "pane" => [
            "startAngle" => -150,
            "endAngle" => 150,
          ],
          "yAxis" => [
            "min" => 0,
            "max" => $meta->meta,
            "minorTickInterval" => "auto",
            "minorTickWidth" => 1,
            "minorTickLength" => 10,
            "minorTickPosition" => "inside",
            "minorTickColor" => "#666",
            "tickPixelInterval" => 30,
            "tickWidth" => 2,
            "tickPosition" => "inside",
            "tickLength" => 10,
            "tickColor" => "#666",
            "labels" => [
              "step" => 2,
              "rotation" => "auto"
            ],
            "title" => [
              "text" => "Vecinos"
            ],
            "plotBands" => [
              [
                "from" => 0,
                "to" => $meta->meta * 0.3333,
                "color" => "#DF5353" // red
              ],
              [
                "from" => $meta->meta * 0.3333,
                "to" => $meta->meta * 0.66666,
                "color" => "#DDDF0D" // yellow
              ],
              [
                "from" => $meta->meta * 0.66666,
                "to" => $meta->meta,
                "color" => "#55BF3B" // green
              ]
            ]
          ],
          "series" => [
            [
              "name" => "Satisfechos",
              "data" => [intval($meta->meta)],
              "tooltip" => [
                "valueSuffix" => "vecinos"
              ]
            ]
          ],
          "credits" => [
            [
              "enabled" => "false"
            ]
          ]
        ];
      } else {
        $chart = [
          "chart" => [
            "type" => "gauge",
          ],
          "title" => [
            "text" => $meta->colonia . '<br/> Estado: ' . intval($meta->meta)
          ],
          "pane" => [
            "startAngle" => -150,
            "endAngle" => 150,
          ],
          "yAxis" => [
            "min" => 0,
            "max" => $meta->meta,
            "minorTickInterval" => "auto",
            "minorTickWidth" => 1,
            "minorTickLength" => 10,
            "minorTickPosition" => "inside",
            "minorTickColor" => "#666",
            "tickPixelInterval" => 30,
            "tickWidth" => 2,
            "tickPosition" => "inside",
            "tickLength" => 10,
            "tickColor" => "#666",
            "labels" => [
              "step" => 2,
              "rotation" => "auto"
            ],
            "title" => [
              "text" => "Vecinos"
            ],
            "plotBands" => [
              [
                "from" => 0,
                "to" => $meta->meta * 0.3333,
                "color" => "#DF5353" // red
              ],
              [
                "from" => $meta->meta * 0.3333,
                "to" => $meta->meta * 0.66666,
                "color" => "#DDDF0D" // yellow
              ],
              [
                "from" => $meta->meta * 0.66666,
                "to" => $meta->meta,
                "color" => "#55BF3B" // green
              ]
            ]
          ],
          "series" => [
            [
              "name" => "Satisfechos",
              "data" => [$meta->actual],
              "tooltip" => [
                "valueSuffix" => "vecinos"
              ]
            ]
          ],
          "credits" => [
            [
              "enabled" => "false"
            ]
          ]
        ];
      }
      $meta->chart = $chart;
    }



    return response()->json($metas);
  }

  //Metas satisfechos por distrito (aplica para zona 1 y 21)
  function getMetasSatisfechosDistritos(int $id) //Modificado 15/07/2022
  {

    $query = "SELECT SUM(a.meta)AS meta, b.distrito_id
    FROM metas_mnto_satisfechos a
    INNER JOIN colonias b ON b.id = a.colonia_id
    WHERE a.alcaldia_id = $id 
    GROUP BY b.distrito_id";
    $metas = DB::select($query);

    $query = "SELECT COUNT(a.id)AS actual, b.distrito_id
    FROM personas a
    INNER JOIN colonias b ON b.id=a.colonia_id
    WHERE a.zona_id = $id  AND seguimiento = 2 AND a.deleted_at IS NULL
    GROUP BY b.distrito_id";
    $actual = DB::select($query);
    $actualDistrito1 = 0;
    $actualDistrito2 = 0;
    $actualDistrito3 = 0;

    foreach ($actual as $act) {
      if ($act->distrito_id == 1) {
        $actualDistrito1 = $act->actual;
      } else if ($act->distrito_id == 2) {
        $actualDistrito2 = $act->actual;
      } else {
        $actualDistrito3 = $act->actual;
      }
    }
    $array = [];
    foreach ($metas as $meta) {
      switch ($meta->distrito_id) {
        case '1':
          $arreglo = [
            "meta" => $meta->meta,
            "actual" => $actualDistrito1,
            "distrito" => $meta->distrito_id
          ];
          break;
        case '2':
          $arreglo = [
            "meta" => $meta->meta,
            "actual" => $actualDistrito2,
            "distrito" => $meta->distrito_id
          ];
          break;
        case '3':
          $arreglo = [
            "meta" => $meta->meta,
            "actual" => $actualDistrito3,
            "distrito" => $meta->distrito_id
          ];
          break;
      }
      $array[] = $arreglo;
    }
    $nuevoArreglo = json_encode($array);
    $metasNuevas = json_decode($nuevoArreglo);


    foreach ($metasNuevas as $meta) {
      $acumulado = 0;
      $query = "SELECT COUNT(a.id)AS actual, (SELECT SUM(b.meta)FROM metas_mnto_satisfechos b WHERE b.colonia_id = a.colonia_id)AS meta, c.colonia
      FROM personas a
      INNER JOIN colonias c ON c.id = a.colonia_id
      WHERE a.seguimiento = 2 AND a.deleted_at IS NULL AND a.zona_id = $id AND c.distrito_id = $meta->distrito
      GROUP BY a.colonia_id";
      $res = DB::Select($query);
      foreach ($res as $resultado) {
        if($resultado->meta < $resultado->actual){
          $a = $resultado->actual - $resultado->meta;
          $acumulado = $acumulado + $a;
        }
      }
      if ($meta->meta < $acumulado) {
        $actual = $acumulado - $meta->meta;
        $meta->actual = $meta->meta;
        $chart = [
          "chart" => [
            "type" => "gauge",
          ],
          "title" => [
            "text" => "Distrito " . $meta->distrito . '<br/> Estado: ' . intval($meta->meta) . '<br/> <p style="color:#10069f"> Actual: +' . $actual . '</p>'
          ],
          "pane" => [
            "startAngle" => -150,
            "endAngle" => 150,
          ],
          "yAxis" => [
            "min" => 0,
            "max" => intval($meta->meta),
            "minorTickInterval" => "auto",
            "minorTickWidth" => 1,
            "minorTickLength" => 10,
            "minorTickPosition" => "inside",
            "minorTickColor" => "#666",
            "tickPixelInterval" => 30,
            "tickWidth" => 2,
            "tickPosition" => "inside",
            "tickLength" => 10,
            "tickColor" => "#666",
            "labels" => [
              "step" => 2,
              "rotation" => "auto"
            ],
            "title" => [
              "text" => "Vecinos"
            ],
            "plotBands" => [
              [
                "from" => 0,
                "to" => $meta->meta * 0.3333,
                "color" => "#DF5353" // red
              ],
              [
                "from" => $meta->meta * 0.3333,
                "to" => $meta->meta * 0.66666,
                "color" => "#DDDF0D" // yellow
              ],
              [
                "from" => $meta->meta * 0.66666,
                "to" => $meta->meta,
                "color" => "#55BF3B" // green
              ]
            ]
          ],
          "series" => [
            [
              "name" => "Satisfechos",
              "data" => [intval($meta->meta)],
              "tooltip" => [
                "valueSuffix" => "vecinos"
              ]
            ]
          ],
          "credits" => [
            [
              "enabled" => "false"
            ]
          ]
        ];
      } else {
        $metaActual = $meta->actual - $acumulado;
        $chart = [
          "chart" => [
            "type" => "gauge",
          ],
          "title" => [
            "text" => "Distrito " . $meta->distrito . '<br/> Estado: ' . intval($meta->meta)
          ],
          "pane" => [
            "startAngle" => -150,
            "endAngle" => 150,
          ],
          "yAxis" => [
            "min" => 0,
            "max" => intval($meta->meta),
            "minorTickInterval" => "auto",
            "minorTickWidth" => 1,
            "minorTickLength" => 10,
            "minorTickPosition" => "inside",
            "minorTickColor" => "#666",
            "tickPixelInterval" => 30,
            "tickWidth" => 2,
            "tickPosition" => "inside",
            "tickLength" => 10,
            "tickColor" => "#666",
            "labels" => [
              "step" => 2,
              "rotation" => "auto"
            ],
            "title" => [
              "text" => "Vecinos"
            ],
            "plotBands" => [
              [
                "from" => 0,
                "to" => $meta->meta * 0.3333,
                "color" => "#DF5353" // red
              ],
              [
                "from" => $meta->meta * 0.3333,
                "to" => $meta->meta * 0.66666,
                "color" => "#DDDF0D" // yellow
              ],
              [
                "from" => $meta->meta * 0.66666,
                "to" => $meta->meta,
                "color" => "#55BF3B" // green
              ]
            ]
          ],
          "series" => [
            [
              "name" => "Satisfechos",
              "data" => [intval($metaActual)],
              "tooltip" => [
                "valueSuffix" => "vecinos"
              ]
            ]
          ],
          "credits" => [
            [
              "enabled" => "false"
            ]
          ]
        ];
      }
      $meta->chart = $chart;
    }



    return response()->json($metasNuevas);
  }

  //Metas satisfechos por distrito (aplica para zona 1 y 21)
  function getMetasSatisfechosByDistritoAlcaldia(int $id, int $distrito)
  {
    $metas = DB::table('metas_mnto_satisfechos')
      ->select(DB::raw('SUM(metas_mnto_satisfechos.meta)AS meta, SUM(metas_mnto_satisfechos.actual)AS actual'), 'colonias.distrito_id')
      ->join('alcaldias', 'alcaldias.id', '=', 'metas_mnto_satisfechos.alcaldia_id')
      ->join('colonias', 'colonias.id', '=', 'metas_mnto_satisfechos.colonia_id')
      ->where([
        ['metas_mnto_satisfechos.alcaldia_id', '=', $id],
        ['colonias.distrito_id', '=', $distrito],
      ])
      ->groupBy('colonias.distrito_id')
      ->get();
    foreach ($metas as $meta) {
      if ($meta->meta < $meta->actual) {
        $actual = $meta->actual - $meta->meta;
        $meta->actual = $meta->meta;
        $chart = [
          "chart" => [
            "type" => "gauge",
          ],
          "title" => [
            "text" => "Distrito " . $meta->distrito_id . '<br/> Estado: ' . intval($meta->meta) . '<br/> <p style="color:#10069f"> Actual: +' . $actual . '</p>'
          ],
          "pane" => [
            "startAngle" => -150,
            "endAngle" => 150,
          ],
          "yAxis" => [
            "min" => 0,
            "max" => intval($meta->meta),
            "minorTickInterval" => "auto",
            "minorTickWidth" => 1,
            "minorTickLength" => 10,
            "minorTickPosition" => "inside",
            "minorTickColor" => "#666",
            "tickPixelInterval" => 30,
            "tickWidth" => 2,
            "tickPosition" => "inside",
            "tickLength" => 10,
            "tickColor" => "#666",
            "labels" => [
              "step" => 2,
              "rotation" => "auto"
            ],
            "title" => [
              "text" => "Vecinos"
            ],
            "plotBands" => [
              [
                "from" => 0,
                "to" => $meta->meta * 0.3333,
                "color" => "#DF5353" // red
              ],
              [
                "from" => $meta->meta * 0.3333,
                "to" => $meta->meta * 0.66666,
                "color" => "#DDDF0D" // yellow
              ],
              [
                "from" => $meta->meta * 0.66666,
                "to" => $meta->meta,
                "color" => "#55BF3B" // green
              ]
            ]
          ],
          "series" => [
            [
              "name" => "Satisfechos",
              "data" => [intval($meta->meta)],
              "tooltip" => [
                "valueSuffix" => "vecinos"
              ]
            ]
          ],
          "credits" => [
            [
              "enabled" => "false"
            ]
          ]
        ];
      } else {
        $chart = [
          "chart" => [
            "type" => "gauge",
          ],
          "title" => [
            "text" => "Distrito " . $meta->distrito_id . '<br/> Estado: ' . intval($meta->meta)
          ],
          "pane" => [
            "startAngle" => -150,
            "endAngle" => 150,
          ],
          "yAxis" => [
            "min" => 0,
            "max" => intval($meta->meta),
            "minorTickInterval" => "auto",
            "minorTickWidth" => 1,
            "minorTickLength" => 10,
            "minorTickPosition" => "inside",
            "minorTickColor" => "#666",
            "tickPixelInterval" => 30,
            "tickWidth" => 2,
            "tickPosition" => "inside",
            "tickLength" => 10,
            "tickColor" => "#666",
            "labels" => [
              "step" => 2,
              "rotation" => "auto"
            ],
            "title" => [
              "text" => "Vecinos"
            ],
            "plotBands" => [
              [
                "from" => 0,
                "to" => $meta->meta * 0.3333,
                "color" => "#DF5353" // red
              ],
              [
                "from" => $meta->meta * 0.3333,
                "to" => $meta->meta * 0.66666,
                "color" => "#DDDF0D" // yellow
              ],
              [
                "from" => $meta->meta * 0.66666,
                "to" => $meta->meta,
                "color" => "#55BF3B" // green
              ]
            ]
          ],
          "series" => [
            [
              "name" => "Satisfechos",
              "data" => [intval($meta->actual)],
              "tooltip" => [
                "valueSuffix" => "vecinos"
              ]
            ]
          ],
          "credits" => [
            [
              "enabled" => "false"
            ]
          ]
        ];
      }
      $meta->chart = $chart;
    }



    return response()->json($metas);
  }


  //Metas satisfechos por sector (aplica para zona 11)
  function getMetasSatisfechosBySector(int $id)
  {
    $metas = DB::table('metas_mnto_satisfechos')
      ->select(DB::raw('SUM(metas_mnto_satisfechos.meta)AS meta, SUM(metas_mnto_satisfechos.actual)AS actual'), 'colonias.colonia')
      ->join('alcaldias', 'alcaldias.id', '=', 'metas_mnto_satisfechos.alcaldia_id')
      ->join('colonias', 'colonias.id', '=', 'metas_mnto_satisfechos.colonia_id')
      ->where('colonias.sector_id', '=', $id)
      ->groupBy('colonias.colonia')
      ->get();
    foreach ($metas as $meta) {
      if ($meta->meta < $meta->actual) {
        $actual = $meta->actual - $meta->meta;
        $meta->actual = $meta->meta;
        $chart = [
          "chart" => [
            "type" => "gauge",
          ],
          "title" => [
            "text" => $meta->colonia . '<br/> Estado: ' . intval($meta->meta) . '<br/> <p style="color:#10069f">Actual: +' . $actual . '</p>'
          ],
          "pane" => [
            "startAngle" => -150,
            "endAngle" => 150,
          ],
          "yAxis" => [
            "min" => 0,
            "max" => intval($meta->meta),
            "minorTickInterval" => "auto",
            "minorTickWidth" => 1,
            "minorTickLength" => 10,
            "minorTickPosition" => "inside",
            "minorTickColor" => "#666",
            "tickPixelInterval" => 30,
            "tickWidth" => 2,
            "tickPosition" => "inside",
            "tickLength" => 10,
            "tickColor" => "#666",
            "labels" => [
              "step" => 2,
              "rotation" => "auto"
            ],
            "title" => [
              "text" => "Vecinos"
            ],
            "plotBands" => [
              [
                "from" => 0,
                "to" => $meta->meta * 0.3333,
                "color" => "#DF5353" // red
              ],
              [
                "from" => $meta->meta * 0.3333,
                "to" => $meta->meta * 0.66666,
                "color" => "#DDDF0D" // yellow
              ],
              [
                "from" => $meta->meta * 0.66666,
                "to" => $meta->meta,
                "color" => "#55BF3B" // green
              ]
            ]
          ],
          "series" => [
            [
              "name" => "Satisfechos",
              "data" => [intval($meta->meta)],
              "tooltip" => [
                "valueSuffix" => "vecinos"
              ]
            ]
          ],
          "credits" => [
            [
              "enabled" => "false"
            ]
          ]
        ];
      } else {
        $chart = [
          "chart" => [
            "type" => "gauge",
          ],
          "title" => [
            "text" => $meta->colonia . '<br/> Estado: ' . intval($meta->meta)
          ],
          "pane" => [
            "startAngle" => -150,
            "endAngle" => 150,
          ],
          "yAxis" => [
            "min" => 0,
            "max" => intval($meta->meta),
            "minorTickInterval" => "auto",
            "minorTickWidth" => 1,
            "minorTickLength" => 10,
            "minorTickPosition" => "inside",
            "minorTickColor" => "#666",
            "tickPixelInterval" => 30,
            "tickWidth" => 2,
            "tickPosition" => "inside",
            "tickLength" => 10,
            "tickColor" => "#666",
            "labels" => [
              "step" => 2,
              "rotation" => "auto"
            ],
            "title" => [
              "text" => "Vecinos"
            ],
            "plotBands" => [
              [
                "from" => 0,
                "to" => $meta->meta * 0.3333,
                "color" => "#DF5353" // red
              ],
              [
                "from" => $meta->meta * 0.3333,
                "to" => $meta->meta * 0.66666,
                "color" => "#DDDF0D" // yellow
              ],
              [
                "from" => $meta->meta * 0.66666,
                "to" => $meta->meta,
                "color" => "#55BF3B" // green
              ]
            ]
          ],
          "series" => [
            [
              "name" => "Satisfechos",
              "data" => [intval($meta->actual)],
              "tooltip" => [
                "valueSuffix" => "vecinos"
              ]
            ]
          ],
          "credits" => [
            [
              "enabled" => "false"
            ]
          ]
        ];
      }
      $meta->chart = $chart;
    }



    return response()->json($metas);
  }

  //Meta global por sector
  function getMetasBySector(int $id)
  {
    $metas = DB::table('metas_mnto_satisfechos')
      ->select(DB::raw('SUM(metas_mnto_satisfechos.meta)AS meta, SUM(metas_mnto_satisfechos.actual)AS actual'), 'sectores.sector')
      ->join('alcaldias', 'alcaldias.id', '=', 'metas_mnto_satisfechos.alcaldia_id')
      ->join('colonias', 'colonias.id', '=', 'metas_mnto_satisfechos.colonia_id')
      ->join('sectores', 'sectores.id', '=', 'colonias.sector_id')
      ->where('metas_mnto_satisfechos.alcaldia_id', '=', $id)
      ->groupBy('sectores.sector')
      ->get();
    foreach ($metas as $meta) {
      if ($meta->meta < $meta->actual) {
        $actual = $meta->actual - $meta->meta;
        $meta->actual = $meta->meta;
        $chart = [
          "chart" => [
            "type" => "gauge",
          ],
          "title" => [
            "text" => "Sector " . $meta->sector . '<br/> Estado: ' . intval($meta->meta) . '<br/> <p style="color:#10069f"> Actual: +' . $actual . '</p>'
          ],
          "pane" => [
            "startAngle" => -150,
            "endAngle" => 150,
          ],
          "yAxis" => [
            "min" => 0,
            "max" => intval($meta->meta),
            "minorTickInterval" => "auto",
            "minorTickWidth" => 1,
            "minorTickLength" => 10,
            "minorTickPosition" => "inside",
            "minorTickColor" => "#666",
            "tickPixelInterval" => 30,
            "tickWidth" => 2,
            "tickPosition" => "inside",
            "tickLength" => 10,
            "tickColor" => "#666",
            "labels" => [
              "step" => 2,
              "rotation" => "auto"
            ],
            "title" => [
              "text" => "Vecinos"
            ],
            "plotBands" => [
              [
                "from" => 0,
                "to" => $meta->meta * 0.3333,
                "color" => "#DF5353" // red
              ],
              [
                "from" => $meta->meta * 0.3333,
                "to" => $meta->meta * 0.66666,
                "color" => "#DDDF0D" // yellow
              ],
              [
                "from" => $meta->meta * 0.66666,
                "to" => $meta->meta,
                "color" => "#55BF3B" // green
              ]
            ]
          ],
          "series" => [
            [
              "name" => "Satisfechos",
              "data" => [intval($meta->meta)],
              "tooltip" => [
                "valueSuffix" => "vecinos"
              ]
            ]
          ],
          "credits" => [
            [
              "enabled" => "false"
            ]
          ]
        ];
      } else {

        $chart = [
          "chart" => [
            "type" => "gauge",
          ],
          "title" => [
            "text" => "Sector " . $meta->sector . '<br/> Estado: ' . intval($meta->meta)
          ],
          "pane" => [
            "startAngle" => -150,
            "endAngle" => 150,
          ],
          "yAxis" => [
            "min" => 0,
            "max" => intval($meta->meta),
            "minorTickInterval" => "auto",
            "minorTickWidth" => 1,
            "minorTickLength" => 10,
            "minorTickPosition" => "inside",
            "minorTickColor" => "#666",
            "tickPixelInterval" => 30,
            "tickWidth" => 2,
            "tickPosition" => "inside",
            "tickLength" => 10,
            "tickColor" => "#666",
            "labels" => [
              "step" => 2,
              "rotation" => "auto"
            ],
            "title" => [
              "text" => "Vecinos"
            ],
            "plotBands" => [
              [
                "from" => 0,
                "to" => $meta->meta * 0.3333,
                "color" => "#DF5353" // red
              ],
              [
                "from" => $meta->meta * 0.3333,
                "to" => $meta->meta * 0.66666,
                "color" => "#DDDF0D" // yellow
              ],
              [
                "from" => $meta->meta * 0.66666,
                "to" => $meta->meta,
                "color" => "#55BF3B" // green
              ]
            ]
          ],
          "series" => [
            [
              "name" => "Satisfechos",
              "data" => [intval($meta->actual)],
              "tooltip" => [
                "valueSuffix" => "vecinos"
              ]
            ]
          ],
          "credits" => [
            [
              "enabled" => "false"
            ]
          ]
        ];
      }
      $meta->chart = $chart;
    }



    return response()->json($metas);
  }
}
