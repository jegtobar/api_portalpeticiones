<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

class DashboardMuySatisfechos extends Controller
{

    //Funcion para mostrar las metas de vecinos satisfechos de las alcaldias auxiliares
    function getMetasAlcaldias(){
        $metas = DB::table('metas_muysatisfechos')
        ->select(DB::raw('SUM(metas_muysatisfechos.meta)AS meta, SUM(metas_muysatisfechos.actual)AS actual'), 'alcaldias.alcaldia')
        ->join('alcaldias','alcaldias.id','=','metas_muysatisfechos.alcaldia_id')
        ->groupBy('alcaldias.alcaldia')
        ->get();

        foreach ($metas as $meta ) {
            $chart = [
                    "chart" => [
                        "type" => "gauge",
                      ],
                      "title" => [
                        "text" => $meta->alcaldia
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
                            "to"=> $meta->meta * 0.66666,
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
                          "name" => "Muy Satisfechos",
                          "data" => [intval($meta->actual)],
                          "tooltip" => [
                            "valueSuffix" => "vecinos"
                          ]
                        ]
                      ],
                    "credits"=>[
                        [
                            "enabled"=>"false"
                            ]
                    ]
            ];
            $meta->chart=$chart;
        }
        


        return response()->json($metas);
        
    }
    //Funcion para mostrar las metas de vecinos satisfechos de todas las colonias según distrito (aplica para zona 1 y 21)
    function getMetasSatisfechosByDistrito(int $id, int $distrito){
        $metas = DB::table('metas_muysatisfechos')
        ->select('metas_muysatisfechos.meta', 'metas_muysatisfechos.actual', 'colonias.colonia')
        ->join('colonias','colonias.id','=','metas_muysatisfechos.colonia_id')
        ->where([
            ['metas_muysatisfechos.alcaldia_id','=',$id],
            ['colonias.distrito_id','=',$distrito]
            ])
        ->get();

        foreach ($metas as $meta ) {
            $chart = [
                    "chart" => [
                        "type" => "gauge",
                      ],
                      "title" => [
                        "text" => $meta->colonia
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
                            "to"=> $meta->meta * 0.66666,
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
                          "name" => "Muy Satisfechos",
                          "data" => [$meta->actual],
                          "tooltip" => [
                            "valueSuffix" => "vecinos"
                          ]
                        ]
                      ],
                    "credits"=>[
                        [
                            "enabled"=>"false"
                            ]
                    ]
            ];
            $meta->chart=$chart;
        }
        


        return response()->json($metas);
    }

    //Metas satisfechos por colonias según alcaldia 
    function getMetasSatisfechosByColonia(int $id){
        $metas = DB::table('metas_muysatisfechos')
        ->select('metas_muysatisfechos.meta', 'metas_muysatisfechos.actual', 'colonias.colonia')
        ->join('colonias','colonias.id','=','metas_muysatisfechos.colonia_id')
        ->where('metas_muysatisfechos.alcaldia_id','=',$id)
        ->get();

        foreach ($metas as $meta ) {
            $chart = [
                    "chart" => [
                        "type" => "gauge",
                      ],
                      "title" => [
                        "text" => $meta->colonia
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
                            "to"=> $meta->meta * 0.66666,
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
                          "name" => "Muy Satisfechos",
                          "data" => [$meta->actual],
                          "tooltip" => [
                            "valueSuffix" => "vecinos"
                          ]
                        ]
                      ],
                    "credits"=>[
                        [
                            "enabled"=>"false"
                            ]
                    ]
            ];
            $meta->chart=$chart;
        }
        


        return response()->json($metas);
    }

    //Metas satisfechos por distrito (aplica para zona 1 y 21)
    function getMetasSatisfechosDistritos(int $id){
        $metas = DB::table('metas_muysatisfechos')
        ->select(DB::raw('SUM(metas_muysatisfechos.meta)AS meta, SUM(metas_muysatisfechos.actual)AS actual'), 'colonias.distrito_id')
        ->join('alcaldias','alcaldias.id','=','metas_muysatisfechos.alcaldia_id')
        ->join('colonias','colonias.id','=','metas_muysatisfechos.colonia_id')
        ->where('metas_muysatisfechos.alcaldia_id','=',$id)
        ->groupBy('colonias.distrito_id')
        ->get();
        foreach ($metas as $meta ) {
            $chart = [
                    "chart" => [
                        "type" => "gauge",
                      ],
                      "title" => [
                        "text" => "Distrito ".$meta->distrito_id
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
                            "to"=> $meta->meta * 0.66666,
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
                          "name" => "Muy Satisfechos",
                          "data" => [intval($meta->actual)],
                          "tooltip" => [
                            "valueSuffix" => "vecinos"
                          ]
                        ]
                      ],
                    "credits"=>[
                        [
                            "enabled"=>"false"
                            ]
                    ]
            ];
            $meta->chart=$chart;
        }
        


        return response()->json($metas);
    }
        
    //Metas satisfechos por sector (aplica para zona 11)
    function getMetasSatisfechosBySector(int $id){
        $metas = DB::table('metas_muysatisfechos')
        ->select(DB::raw('SUM(metas_muysatisfechos.meta)AS meta, SUM(metas_muysatisfechos.actual)AS actual'), 'colonias.colonia')
        ->join('alcaldias','alcaldias.id','=','metas_muysatisfechos.alcaldia_id')
        ->join('colonias','colonias.id','=','metas_muysatisfechos.colonia_id')
        ->where('colonias.sector_id','=',$id)
        ->groupBy('colonias.colonia')
        ->get();
        foreach ($metas as $meta ) {
            $chart = [
                    "chart" => [
                        "type" => "gauge",
                      ],
                      "title" => [
                        "text" => $meta->colonia
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
                            "to"=> $meta->meta * 0.66666,
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
                          "name" => "Muy Satisfechos",
                          "data" => [intval($meta->actual)],
                          "tooltip" => [
                            "valueSuffix" => "vecinos"
                          ]
                        ]
                      ],
                    "credits"=>[
                        [
                            "enabled"=>"false"
                            ]
                    ]
            ];
            $meta->chart=$chart;
        }
        


        return response()->json($metas);
    }

    //Meta global por sector
    function getMetasBySector(int $id){
      $metas = DB::table('metas_muysatisfechos')
      ->select(DB::raw('SUM(metas_muysatisfechos.meta)AS meta, SUM(metas_muysatisfechos.actual)AS actual'), 'sectores.sector')
      ->join('alcaldias','alcaldias.id','=','metas_muysatisfechos.alcaldia_id')
      ->join('colonias','colonias.id','=','metas_muysatisfechos.colonia_id')
      ->join('sectores','sectores.id','=','colonias.sector_id')
      ->where('metas_muysatisfechos.alcaldia_id','=',$id)
      ->groupBy('sectores.sector')
      ->get();
      foreach ($metas as $meta ) {
          $chart = [
                  "chart" => [
                      "type" => "gauge",
                    ],
                    "title" => [
                      "text" => "Sector ".$meta->sector
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
                          "to"=> $meta->meta * 0.66666,
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
                        "name" => "Muy Satisfechos",
                        "data" => [intval($meta->actual)],
                        "tooltip" => [
                          "valueSuffix" => "vecinos"
                        ]
                      ]
                    ],
                  "credits"=>[
                      [
                          "enabled"=>"false"
                          ]
                  ]
          ];
          $meta->chart=$chart;
      }
      


      return response()->json($metas);
  }

}