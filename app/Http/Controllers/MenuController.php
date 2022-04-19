<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller {

    function getMenu(int $id){
        $menus = DB::table('users')
        ->select('users.rol_id')
        ->where('users.id', $id)
        ->get();

        foreach ($menus as $menu) {
            switch ($menu->rol_id) {
              //Alcalde auxiliar
                case 1:
                    $link = [

                        [
                            "to"   => "/inicio",
                            "icon" => "mdi-home",
                            "text" => "Inicio",
                        ],
                        [
                            "icon"     => "mdi-view-dashboard",
                            "text"     => "Dashboard",
                            "subLinks" =>[
                              [
                                "text" => "Satisfechos",
                                "to"   => "/indicadoresSatisfechos",
                                "icon" => "mdi-gauge",
                              ],
                              [
                                "text" => "Muy Satisfechos",
                                "to"   => "/indicadoresMuySatisfechos",
                                "icon" => "mdi-gauge",
                              ],
                            ],
                        ],
                        [
                          "icon"     => "mdi-account-multiple",
                          "text"     => "Vecinos",
                          "subLinks" =>[
                            [
                              "text" => "Satisfechos",
                              "to"   => "/satisfechos",
                              "icon" => "mdi-check",
                            ],
                            [
                              "text" => "Muy Satisfechos",
                              "to"   => "/muysatisfechos",
                              "icon" => "mdi-check-all",
                            ],
                            [
                                "text" => "Insatisfechos",
                                "to"   => "/insatisfecho",
                                "icon" => "mdi-thumb-down-outline",
                            ],
                          ],
                        ],
                        // [
                        //   "icon"     => "mdi-check-outline",
                        //   "text"     => "Peticiones",
                        //   "subLinks" =>[
                        //     [
                        //       "text" => "Ingresar",
                        //       "to"   => "/peticion",
                        //       "icon" => "mdi-plus",
                        //     ],
                        //     [
                        //       "text" => "Seguimiento",
                        //       "to"   => "/seguimiento",
                        //       "icon" => "mdi-plus",
                        //     ],
                        //     [
                        //       "text" => "Por Vecino",
                        //       "to"   => "/peticion-vecino",
                        //       "icon" => "mdi-view-list",
                        //     ],
                        //   ],
                        // ],
                        [
                            "icon"     => "mdi-clipboard-edit-outline",
                            "text"     => "Auditoría",
                            "subLinks" =>[
                              [
                                "text" => "Satisfechos",
                                "to"   => "/audiSatisfechos",
                                "icon" => "mdi-check",
                              ],
                              [
                                "text" => "Muy Satisfechos",
                                "to"   => "/audiMuySatisfechos",
                                "icon" => "mdi-check-all",
                              ],
                            ],
                        ],
                        [
                          "to"   => "/perfil",
                          "icon" => "mdi-account-cog",
                          "text" => "Editar usuario",
                        ],

                ];
                    break;
                    //Promotor
                case 2:
                  $link = [
                    [
                      "to"   => "/inicio",
                      "icon" => "mdi-home",
                      "text" => "Inicio",
                    ],
                    [
                      "icon"     => "mdi-account-multiple",
                      "text"     => "Vecinos",
                      "to"       => "/nuevoVecino",
                    ],
                  // [
                  //   "icon"     => "mdi-check-outline",
                  //   "text"     => "Peticiones",
                  //   "subLinks" =>[
                  //     [
                  //       "text" => "Ingresar",
                  //       "to"   => "/peticion",
                  //       "icon" => "mdi-plus",
                  //     ],
                  //     [
                  //       "text" => "Seguimiento",
                  //       "to"   => "/seguimiento",
                  //       "icon" => "mdi-plus",
                  //     ],
                  //     [
                  //       "text" => "Por Vecino",
                  //       "to"   => "/peticion-vecino",
                  //       "icon" => "mdi-view-list",
                  //     ],
                  //   ],
                  // ],
                  [
                    "to"   => "/perfil",
                    "icon" => "mdi-account-cog",
                    "text" => "Editar usuario",
                  ],

                  ];

                    break;
                    //Coordinador
                case 3:
                  $link = [

                    [
                        "to"   => "/inicio",
                        "icon" => "mdi-home",
                        "text" => "Inicio",
                    ],
                    [
                      "icon"     => "mdi-view-dashboard",
                      "text"     => "Dashboard",
                      "subLinks" =>[
                        [
                          "text" => "Satisfechos",
                          "to"   => "/indicadoresSatisfechos",
                          "icon" => "mdi-gauge",
                        ],
                        [
                          "text" => "Muy Satisfechos",
                          "to"   => "/indicadoresMuySatisfechos",
                          "icon" => "mdi-gauge",
                        ],
                      ],
                  ],
                  [
                    "icon"     => "mdi-account-multiple",
                    "text"     => "Vecinos",
                    "subLinks" =>[
                      [
                        "text" => "Satisfechos",
                        "to"   => "/satisfechos",
                        "icon" => "mdi-check",
                      ],
                      [
                        "text" => "Muy Satisfechos",
                        "to"   => "/muysatisfechos",
                        "icon" => "mdi-check-all",
                      ],
                      [
                        "text" => "Insatisfechos",
                        "to"   => "/insatisfecho",
                        "icon" => "mdi-thumb-down-outline",
                      ],
                    ],
                  ],

                    [
                      "to"   => "/perfil",
                      "icon" => "mdi-account-cog",
                      "text" => "Editar usuario",
                    ],

            ];
                    break;
                case 4:
                    $link = [

                            [
                                "to"   => "/inicio",
                                "icon" => "mdi-home",
                                "text" => "Inicio",
                            ],
                            [
                                "icon"     => "mdi-view-dashboard",
                                "text"     => "Dashboard",
                                "subLinks" =>[
                                  [
                                    "text" => "Satisfechos",
                                    "to"   => "/dashboard",
                                    "icon" => "mdi-gauge",
                                  ],
                                  [
                                    "text" => "Muy Satisfechos",
                                    "to"   => "/dashboardMuySatisfechos",
                                    "icon" => "mdi-gauge",
                                  ],
                                ],
                            ],
                                [
                                    "text" => "Usuarios",
                                    "to"   => "/usuarios",
                                    "icon" => "mdi-account",
                                ],
                            [
                              "icon"     => "mdi-account-multiple",
                              "text"     => "Vecinos",
                              "subLinks" =>[
                                [
                                  "text" => "Satisfechos",
                                  "to"   => "/satisfechos",
                                  "icon" => "mdi-check",
                                ],
                                [
                                  "text" => "Muy Satisfechos",
                                  "to"   => "/muysatisfechos",
                                  "icon" => "mdi-check-all",
                                ],
                                [
                                    "text" => "Insatisfechos",
                                    "to"   => "/insatisfecho",
                                    "icon" => "mdi-thumb-down-outline",
                                ],
                              ],
                          ],
                          [
                            "icon"     => "mdi-bullseye-arrow",
                            "text"     => "Estados",
                            "subLinks" =>[
                              [
                                "text" => "Satisfechos",
                                "to"   => "/metasSatisfechos",
                                "icon" => "mdi-check",
                              ],
                              [
                                "text" => "Muy Satisfechos",
                                "to"   => "/metasMuySatisfechos",
                                "icon" => "mdi-check-all",
                              ],
                            ],
                        ],
                      //   [
                      //     "icon"     => "mdi-check-outline",
                      //     "text"     => "Peticiones",
                      //     "subLinks" =>[
                      //       [
                      //         "text" => "Ingresar",
                      //         "to"   => "/peticion",
                      //         "icon" => "mdi-plus",
                      //       ],
                      //       [
                      //         "text" => "Seguimiento",
                      //         "to"   => "/seguimiento",
                      //         "icon" => "mdi-plus",
                      //       ],
                      //       [
                      //         "text" => "Por Vecino",
                      //         "to"   => "/peticion-vecino",
                      //         "icon" => "mdi-view-list",
                      //       ],
                      //     ],
                      // ],
                      [
                        "icon"     => "mdi-clipboard-edit-outline",
                        "text"     => "Auditoría",
                        "subLinks" =>[
                          [
                            "text" => "Satisfechos",
                            "to"   => "/auditoriaSatisfechos",
                            "icon" => "mdi-check",
                          ],
                          [
                            "text" => "Muy Satisfechos",
                            "to"   => "/auditoriaMuySatisfechos",
                            "icon" => "mdi-check-all",
                          ],
                        ],
                    ],
                    [
                      "to"   => "/perfil",
                      "icon" => "mdi-account-cog",
                      "text" => "Editar usuario",
                    ],

                    ];

                    break;
                    //Auditor
                  case 5:
                    $link = [
                      [
                        "to"   => "/inicio",
                        "icon" => "mdi-home",
                        "text" => "Inicio",
                      ],
                      [
                          "icon"     => "mdi-clipboard-edit-outline",
                          "text"     => "Auditoría",
                          "subLinks" =>[
                            [
                              "text" => "Satisfechos",
                              "to"   => "/auditoriaSatisfechos",
                              "icon" => "mdi-check",
                            ],
                            [
                              "text" => "Muy Satisfechos",
                              "to"   => "/auditoriaMuySatisfechos",
                              "icon" => "mdi-check-all",
                            ],
                          ],
                      ],
                      [
                        "to"   => "/perfil",
                        "icon" => "mdi-account-cog",
                        "text" => "Editar usuario",
                      ],

              ];
                    break;
                    //Administrador Vecinos
              case 6:
                $link = [

                    [
                        "to"   => "/inicio",
                        "icon" => "mdi-home",
                        "text" => "Inicio",
                    ],
                    [
                      "icon"     => "mdi-view-dashboard",
                      "text"     => "Dashboard",
                      "subLinks" =>[
                        [
                          "text" => "Satisfechos",
                          "to"   => "/dashboard",
                          "icon" => "mdi-gauge",
                        ],
                        [
                          "text" => "Muy Satisfechos",
                          "to"   => "/dashboardMuySatisfechos",
                          "icon" => "mdi-gauge",
                        ],
                      ],
                  ],

                    [
                      "icon"     => "mdi-account-multiple",
                      "text"     => "Vecinos",
                      "subLinks" =>[
                        [
                          "text" => "Satisfechos",
                          "to"   => "/satisfechos",
                          "icon" => "mdi-check",
                        ],
                        [
                          "text" => "Muy Satisfechos",
                          "to"   => "/muysatisfechos",
                          "icon" => "mdi-check-all",
                        ],
                        [
                            "text" => "Insatisfechos",
                            "to"   => "/insatisfecho",
                            "icon" => "mdi-check-all",
                        ],
                      ],
                    ],
                    // [
                    //   "icon"     => "mdi-check-outline",
                    //   "text"     => "Peticiones",
                    //   "subLinks" =>[
                    //     [
                    //       "text" => "Ingresar",
                    //       "to"   => "/peticion",
                    //       "icon" => "mdi-plus",
                    //     ],
                    //     [
                    //       "text" => "Seguimiento",
                    //       "to"   => "/seguimiento",
                    //       "icon" => "mdi-plus",
                    //     ],
                    //     [
                    //       "text" => "Por Vecino",
                    //       "to"   => "/peticion-vecino",
                    //       "icon" => "mdi-view-list",
                    //     ],
                    //   ],
                    // ],

                    [
                      "to"   => "/perfil",
                      "icon" => "mdi-account-cog",
                      "text" => "Editar usuario",
                    ],

            ];
                break;

                default:
                    # code...
                    break;
            }
            $menu->menu=$link;
            return response()->json($menu);

        }

    }
}
