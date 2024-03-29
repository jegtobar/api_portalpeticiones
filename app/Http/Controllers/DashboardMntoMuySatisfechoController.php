<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardMntoMuySatisfechoController extends Controller
{
  //Funcion para mostrar las metas de vecinos satisfechos de las alcaldias auxiliares.
  function getMetasRegionMntoMuySatisfecho()
  {
    $query = "SELECT COUNT(a.id)AS actual, (SELECT SUM(b.meta)FROM metas_mnto_muysatisfechos b)AS meta
    FROM personas a
    INNER JOIN metas_mnto_muysatisfechos d ON d.colonia_id = a.colonia_id
    WHERE a.seguimiento = 4 AND a.deleted_at IS NULL";
    $metaRegion = DB::select($query);

    $query = "SELECT COUNT(a.id)AS actual, (SELECT SUM(b.meta)FROM metas_mnto_muysatisfechos b WHERE b.alcaldia_id = a.zona_id)AS meta, c.alcaldia, a.zona_id
    FROM personas a
    INNER JOIN alcaldias c ON c.id = a.zona_id
    INNER JOIN metas_mnto_muysatisfechos d ON d.colonia_id = a.colonia_id
    WHERE a.seguimiento = 4 AND a.deleted_at IS NULL
    GROUP BY a.zona_id, c.alcaldia";
    $metasPorAlcaldias = DB::select($query);
    $actual = 0; 
    $metaParcial = 0;
    $totalAlcaldias = 0; 
    foreach ($metasPorAlcaldias as $meta) {
      if ($meta->meta < $meta->actual) {
        $m = $meta->meta;
        $metaParcial = $m + $metaParcial;
      }else{
        $a = $meta->actual;
        $actual = $a + $actual;
      }
    }
    $totalAlcaldias = $metaParcial + $actual;
    foreach ($metaRegion as $meta) {
      if ($meta->meta < $totalAlcaldias) {
        $actual =  $meta->actual-$meta->meta;
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
              "data" => [intval($totalAlcaldias)],
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
  function getMetasAlcaldias()
  {
    $query = "SELECT COUNT(a.id)AS actual, (SELECT SUM(b.meta)FROM metas_mnto_muysatisfechos b WHERE b.alcaldia_id = a.zona_id)AS meta, c.alcaldia, a.zona_id
      FROM personas a
      INNER JOIN alcaldias c ON c.id = a.zona_id
      INNER JOIN metas_mnto_muysatisfechos d ON d.colonia_id = a.colonia_id
      WHERE a.seguimiento = 4 AND a.deleted_at IS NULL
      GROUP BY a.zona_id, c.alcaldia";
    $metas = DB::select($query);

    foreach ($metas as $meta) {
      if (intval($meta->meta) < intval($meta->actual)) {

        $adicional = intval($meta->actual) - intval($meta->meta);

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
  function getMetasAlcaldiasById(int $id)
  {
    $query = "SELECT COUNT(a.id)AS actual, (SELECT SUM(b.meta)FROM metas_mnto_muysatisfechos b WHERE b.alcaldia_id = a.zona_id)AS meta, c.alcaldia, a.zona_id
    FROM personas a
    INNER JOIN alcaldias c ON c.id = a.zona_id
    INNER JOIN metas_mnto_muysatisfechos d ON d.colonia_id = a.colonia_id
    WHERE a.seguimiento = 4 AND a.deleted_at IS NULL and a.zona_id = $id
    GROUP BY a.zona_id, c.alcaldia";
    $metas = DB::select($query);

    foreach ($metas as $meta) {
      if (intval($meta->meta) < intval($meta->actual)) {

        $adicional = intval($meta->actual) - intval($meta->meta);

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
    $query = "SELECT COUNT(a.id)AS actual, (SELECT SUM(b.meta)FROM metas_mnto_muysatisfechos b WHERE b.colonia_id = a.colonia_id)AS meta, c.colonia
    FROM personas a
    INNER JOIN colonias c ON c.id = a.colonia_id
    WHERE a.seguimiento = 4 AND a.deleted_at IS NULL AND a.zona_id = $id AND c.distrito_id = $distrito
    GROUP BY a.colonia_id, c.colonia";
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
    $query = "SELECT COUNT(a.id)AS actual, (SELECT SUM(b.meta)FROM metas_mnto_muysatisfechos b WHERE b.colonia_id = a.colonia_id)AS meta, c.colonia
    FROM personas a
    INNER JOIN colonias c ON c.id = a.colonia_id
    INNER JOIN metas_mnto_muysatisfechos d ON d.colonia_id = a.colonia_id
    WHERE a.seguimiento = 4 AND a.deleted_at IS NULL AND a.zona_id = $id 
    GROUP BY a.colonia_id, c.colonia";
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
  function getMetasSatisfechosDistritos(int $id)
  {
    $query = "SELECT SUM(a.meta)AS meta, b.distrito_id
    FROM metas_mnto_muysatisfechos a
    INNER JOIN colonias b ON b.id = a.colonia_id
    WHERE a.alcaldia_id = $id 
    GROUP BY b.distrito_id";
    $metas = DB::select($query);

    $query = "SELECT COUNT(a.id)AS actual, b.distrito_id
    FROM personas a
    INNER JOIN colonias b ON b.id=a.colonia_id
    WHERE a.zona_id = $id  AND seguimiento = 4 AND a.deleted_at IS NULL
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
      if ($meta->meta < $meta->actual) {
        $actual = $meta->actual - $meta->meta;
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



    return response()->json($metasNuevas);
  }

  //Metas satisfechos por distrito (aplica para zona 1 y 21)
  function getMetasSatisfechosByDistritosAlcaldia(int $id, int $distrito)
  {
    $query = "SELECT SUM(a.meta)AS meta, b.distrito_id
    FROM metas_mnto_muysatisfechos a
    INNER JOIN colonias b ON b.id = a.colonia_id
    WHERE b.zona_id = $id and b.distrito_id = $distrito
    GROUP BY b.distrito_id";
    $metas = DB::select($query);

    $query = "SELECT COUNT(a.id)AS actual, b.distrito_id
    FROM personas a
    INNER JOIN colonias b ON b.id=a.colonia_id
    WHERE a.zona_id = $id  AND seguimiento = 4 AND a.deleted_at IS NULL and b.distrito_id = $distrito
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
      if ($meta->meta < $meta->actual) {
        $actual = $meta->actual - $meta->meta;
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

    return response()->json($metasNuevas);
  }
  //Metas satisfechos por sector (aplica para zona 11)
  function getMetasSatisfechosBySector(int $id)
  {
    $query = "SELECT COUNT(a.id)AS actual, (SELECT SUM(b.meta)FROM metas_mnto_muysatisfechos b WHERE b.colonia_id = a.colonia_id)AS meta, c.colonia
    FROM personas a
    INNER JOIN colonias c ON c.id = a.colonia_id
    WHERE a.seguimiento = 4 AND a.deleted_at IS NULL AND a.zona_id = 3 AND c.sector_id = $id
    GROUP BY a.colonia_id, c.colonia";
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
    $query = "SELECT SUM(a.meta)AS meta, c.sector, c.id
    FROM metas_mnto_muysatisfechos a
    INNER JOIN colonias b ON b.id = a.colonia_id
    INNER JOIN sectores c ON c.id = b.sector_id
    WHERE a.alcaldia_id = $id 
    GROUP BY c.sector, c.id";
    $metas = DB::select($query);

    $query = "SELECT COUNT(a.id)AS actual, c.sector, c.id
    FROM personas a
    INNER JOIN colonias b ON b.id=a.colonia_id
    INNER JOIN sectores c ON c.id = b.sector_id
    WHERE a.zona_id = $id AND seguimiento = 4 AND a.deleted_at IS NULL
    GROUP BY c.sector, c.id
    ";
    $actual = DB::select($query);
    $sector1 = 0;
    $sector2 = 0;
    $sector3 = 0;
    $sector4 = 0;
    $sector5 = 0;

    foreach ($actual as $act) {

      switch ($act->id) {
        case '1':
          $sector1 = $act->actual;
          break;
        case '2':
          $sector2 = $act->actual;
          break;
        case '3':
          $sector3 = $act->actual;
          break;
        case '4':
          $sector4 = $act->actual;
          break;
        case '5':
          $sector5 = $act->actual;
          break;
      }
    }
    $array = [];
    foreach ($metas as $meta) {
      switch ($meta->id) {
        case '1':
          $arreglo = [
            "meta" => $meta->meta,
            "actual" => $sector1,
            "sector" => $meta->sector
          ];
          break;
        case '2':
          $arreglo = [
            "meta" => $meta->meta,
            "actual" => $sector2,
            "sector" => $meta->sector
          ];
          break;
        case '3':
          $arreglo = [
            "meta" => $meta->meta,
            "actual" => $sector3,
            "sector" => $meta->sector
          ];
          break;
        case '4':
          $arreglo = [
            "meta" => $meta->meta,
            "actual" => $sector4,
            "sector" => $meta->sector
          ];
          break;
        case '5':
          $arreglo = [
            "meta" => $meta->meta,
            "actual" => $sector5,
            "sector" => $meta->sector
          ];
          break;
      }
      $array[] = $arreglo;
    }
    $nuevoArreglo = json_encode($array);
    $metasNuevas = json_decode($nuevoArreglo);

    foreach ($metasNuevas as $meta) {
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
    return response()->json($metasNuevas);
  }
}
