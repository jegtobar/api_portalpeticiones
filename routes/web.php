<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

//, "middleware"=>"auth"

//Ruta Login
$router->post('/login', 'UserController@login');



//, "middleware"=>"auth"

$router->group(["prefix"=>"/v1"], function()use($router){

        //Menu del usuario
        $router->get('/menu/{id}', 'MenuController@getMenu');
        //Rutas segun el rol del usuario
        $router->post('/rutas', 'RutasController@getRutas');

        //Ruta cerrar sesión
        $router->post('/logout', 'UserController@logout');

        $router->group(["prefix"=>"/user"], function()use($router){
        //Registro de usuarios
        $router->post('/register', 'UserController@createUser');
        //Lista de usuarios activos
        $router->get('/usuarios','UserController@getListUser');
         //Lista de usuarios (activos-inactivos)
         $router->get('/listausuarios','UserController@getListAllUser');
         //Usuario por id
         $router->get('/usuario/{id}','UserController@getUserById');
         //Usuario promotor segun alcaldía
         $router->get('/promotor/{id}','UserController@getUserPromotor');

        //Usuario promotor y coordinador segun alcaldía
        $router->get('/promotorcoordinador/{id}','UserController@getUserPromotorCoordinador');
        //Actualizar usuario
        $router->put("/{id}", "UserController@putUser");

        //Ruta para mostrar información del usuario que puede editar en su perfil
        $router->get('/editUser/{id}','UserController@getUserEditById');
        //Ruta para editar datos del usuario y avatar
        $router->post('/editar', "UserController@postUserUpdate");
        //Eliminar usuario
        $router->delete("/{id}", "UserController@deleteUser");
        //Restaurar usuario
        $router->get("/{id}/restore", "UserController@restoreUser");
    });



        $router->group(["prefix"=>"/persona"], function()use($router){
        //Registro de vecinos
        $router->post('/register', 'PersonaController@createPersona');

         /*Rutas Vecinos Satisfechos  y Mantenimiento Satisfechos

        Al hablar de mantenimiento nos referimos a la constancia del vecino en calidad de satisfecho.*/

        //Lista de vecinos satisfechas (Válido para administradores y auditores)
        $router->get('/lista', 'PersonaController@getPersona');

        //Lista de todas las personas
        $router->get('/completo', 'PersonaController@getTodasLasPersonas');

        //Lista de vecinos generals (promotores que pertenecen a un distrito )
        $router->get('/promotores/{id}/{distrito}/{vecino}', 'PersonaController@getPersonasByZonaDistritoPromotores');

        //Lista de vecinos general (promotores)
        $router->get('/vecinos/zona/{id}/{vecino}', 'PersonaController@getPersonasByZonaPromotores');

        //Lista de vecinos insatisfechos (Válido para alcaldes auxiliares y coordinadores)
        $router->get('/insatisfechosporzona/{id}','PersonaController@getPersonasInsatisfechosByZona');

        //Lista de vecinos insatisfechos (Válido para administradores y auditores)
        $router->get('/insatisfechos','PersonaController@getPersonaInsatisfechos');

        //Lista de vecinos satisfechos (Válido para alcaldes auxiliares y coordinadores)
        $router->get('/listaporzona/{id}','PersonaController@getPersonasByZona');

        //Lista de vecinos por zona y distrito (Válido para alcaldes auxiliares y coordinadores)
        $router->get('/listaporzonadistrito/{id}/{distrito}','PersonaController@getPersonasByZonaDistritoSatisfecho');

         //Lista de personas mantenimiento satisfechos (Válido para administradores y auditores)
        $router->get('/listaMantenimientoSatisfechos', 'PersonaController@getPersonaMantenimientoSatisfechos');

        //Lista de personas mantenimiento satisfechos (Válido para alcaldes auxiliares y coordinadores)
        $router->get('/listaMantenimientoSatisfechos/{id}', 'PersonaController@getPersonaMantenimientoSatisfechosByZona');

        //Lista de personas mantenimiento satisfechos para auditoría
        $router->get('/listaMntoSatisfechosAuditoria/{id}', 'PersonaController@getPersonaMntoSatisfechosAuditoría');

        $router->get('/mntozonadistritosatisfechos/{id}/{distrito}','PersonaController@getPersonasByZonaDistritoMntoSatisfecho');

        //Ruta para actualizar el estatus del vecino de seguimiento a mantenimiento
        $router->put("/mantenimiento/{id}", "PersonaController@putMantenimientoSatisfecho");

        /*
        FIN DE SECCIÓN VECINOS SATISFECHOS - MANTENIMIENTO VECINOS SATISFECHOS xD

        */

         /*
        Rutas Vecinos Muy Satisfechos - Mantenimiento Vecinos Muy Satisfechos
         Al hablar de mantenimiento nos referimos a la constancia del vecino en calidad de muy satisfecho
         */

        //Lista de personas muy satisfechas válido para administradores y auditores
        $router->get('/listaMuySatisfechos','PersonaController@getPersonaMuySatisfechos');

        //Lista de personas muy satisfechas válido para alcalde auxiliar y coordinadores
        $router->get('/listaMuySatisfechos/{id}','PersonaController@getPersonaMuySatisfechosByZona');

        //Lista de personas mantenimiento muy satisfechos válido para administradaores y auditores
        $router->get('/listaMantenimientoMuySatisfechos','PersonaController@getPersonaMantenimientoMuySatisfechos');
        
         //Lista de vecinos por zona y distrito (Válido para alcaldes auxiliares y coordinadores)
         $router->get('/listamuysatisfechoszonadistrito/{id}/{distrito}','PersonaController@getPersonasByZonaDistritoMuySatisfecho');

        //Lista de vecinos por zona y distrito (Válido para alcaldes auxiliares y coordinadores)
        $router->get('/listamntomuysatisfechoszonadistrito/{id}/{distrito}','PersonaController@getPersonasByZonaDistritoMntoMuySatisfecho');


         //Lista de personas mantenimiento muy satisfechos válido para alcalde auxiliar y coordinadores
         $router->get('/listaMantenimientoMuySatisfechos/{id}','PersonaController@getPersonaMantenimientoMuySatisfechosByZona');
        
         //Lista de personas mantenimiento muy satisfechos válido para auditoress
         $router->get('/listaMntoMuySatisfechosAuditoria/{id}','PersonaController@getPersonaMntoMuySatisfechosAuditoria');
        
        
         /*
        /*
        FIN DE SECCIÓN VECINOS MUY SATISFECHOS - MANTENIMIENTO VECINOS MUY SATISFECHOS xD

        */

         //Lista de personas para llenar datatable
        $router->get('/lista/table', 'PersonaController@getPersonaDataTable');
        //Mostrar persona por id
        $router->get('/{id}', 'PersonaController@getPersonaById');
        //Mostrar persona por dpi
        $router->get('/dpi/{id}', 'PersonaController@getPersonaByDpi');
        //Actualizar persona
        $router->put("/{id}", "PersonaController@putPersona");
        //Eliminar (deshabilitar) persona
        $router->delete("/{id}", "PersonaController@deletePersona");
         //Restaurar vecino deshabilitado
        $router->get("/{id}/restore", "PersonaController@restorePersona");
    });

    //Rutas Gestiones
    $router->group(["prefix"=>"/gestion"], function()use($router){
        //Registro de gestion
        $router->post('/register', 'GestionController@createGestion');
        //Lista de gestiones
        $router->get('/gestiones', 'GestionController@getGestiones');
        //Lista de gestión por id
        $router->get('/gestiones/{id}', 'GestionController@getGestionById');
        //Lista de gestión por dpi del vecino
        $router->get('/vecino/{id}/{alcaldia}', 'GestionController@getGestionByDpi');
        //Actualizacion de gestión
        $router->put("/gestiones/{id}", "GestionController@putGestion");
        //Eliminar(deshabilitar) gestion
        $router->get("/{id}/restore","GestionController@deleteGestion");
        //Seguimiento de gestión según número de gestión
        $router->get("/seguimiento/{id}","GestionController@getGestionSeguimientobyId");
        //Creacion de seguimiento de gestión
        $router->post('/seguimiento/create', 'GestionController@createSeguimientoGestion');

        //Lista de seguimiento vecino satisfecho por id persona
        $router->get("/seguimiento/vecinosatisfecho/{id}","GestionController@getSeguimientoVecinosSatisfechosById");
        //Creacion de seguimiento vecino satisfecho
        $router->post('/seguimientosatisfecho/create', 'GestionController@createSeguimientoVecinosSatisfechos');
        //Creacion de seguimientos (modulo de promotores)
        $router->post('/seguimientos/create', 'GestionController@createSeguimientos');
        //Actualizar seguimiento de vecino satisfecho
        $router->put('/seguimientosatisfecho/actualizar/{id}', 'GestionController@putSeguimientoSatisfecho');
        //Eliminar seguimiento vecino satisfecho
        $router->delete('/seguimientosatisfecho/eliminar/{id}', 'GestionController@deleteSeguimientoSatisfecho');


        //Lista de seguimiento vecino muy satisfecho por id persona
        $router->get("/seguimiento/vecinomuysatisfecho/{id}","GestionController@getSeguimientoVecinosMuySatisfechosById");
        //Creacion de seguimiento vecino muy satisfecho
        $router->post('/seguimientomuysatisfecho/create', 'GestionController@createSeguimientoVecinosMuySatisfechos');
        //Actualizar seguimiento de vecino muy satisfecho
        $router->put('/seguimientomuysatisfecho/actualizar/{id}', 'GestionController@putSeguimientoMuySatisfecho');
        //Eliminar seguimiento vecino muy satisfecho
        $router->delete('/seguimientomuysatisfecho/eliminar/{id}', 'GestionController@deleteSeguimientoMuySatisfecho');

        //Lista de seguimiento mantenimiento vecino satisfecho por id persona
        $router->get("/seguimiento/mntovecinosatisfecho/{id}","GestionController@getSeguimientoMantenimientoVecinosSatisfechosById");
        //Creacion de seguimiento mantenimiento vecino  satisfecho
        $router->post('/mntoseguimientosatisfecho/create', 'GestionController@createSeguimientoVecinosMntoSatisfechos');
        //Actualizar seguimiento de mantenimiento vecino  satisfecho
        $router->put('/mntoseguimientosatisfecho/actualizar/{id}', 'GestionController@putSeguimientoMantenimientoSatisfecho');
        //Eliminar seguimiento mantenimiento vecino  satisfecho
        $router->delete('/mntoseguimientosatisfecho/eliminar/{id}', 'GestionController@delSeguimientoMantenimientoSatisfecho');


        //Lista de seguimiento mantenimiento vecino muy satisfecho por id persona
        $router->get("/seguimiento/mntovecinomuysatisfecho/{id}","GestionController@getSeguimientoVecinosMntoMuySatisfechosById");
        //Creacion de seguimiento mantenimiento vecino  muy satisfecho
        $router->post('/mntoseguimientomuysatisfecho/create', 'GestionController@createSeguimientoVecinosMntoMuySatisfechos');
        //Actualizar seguimiento de mantenimiento vecino muy  satisfecho
        $router->put('/mntoseguimientomuysatisfecho/actualizar/{id}', 'GestionController@putSeguimientoMantenimientoMuySatisfecho');
        //Eliminar seguimiento mantenimiento vecino muy  satisfecho
        $router->delete('/mntoseguimientomuysatisfecho/eliminar/{id}', 'GestionController@delSeguimientoMantenimientoMuySatisfecho');

    });

    //Rutas Catalogos
    $router->group(["prefix"=>"/catalogo"], function()use($router){

        //Lista de alcaldias
        $router->get('/alcaldias', 'CatalogosController@getAlcaldias');
        //Lista de roles
        $router->get('/roles', 'CatalogosController@getRoles');
        //Lista de zonas
        $router->get('/zonas', 'CatalogosController@getZonas');
        //Lista de liderazgo
        $router->get('/liderazgo', 'CatalogosController@getLiderazgo');
        //Lista de dependencias
        $router->get('/dependencia', 'CatalogosController@getDependencia');
        //Lista de tipo de peticiones
        $router->get('/peticiones', 'CatalogosController@getPeticion');
        //Lista de catalogo canales (vías de comunicación entre vecino y alcaldia)
        $router->get('/canales', 'CatalogosController@getCanales');
        //Lista de catalogo distritos
        $router->get('/distritos/{id}', 'CatalogosController@getDistritos');
        //Lista de catalogo sectores
        $router->get('/sectores/{id}', 'CatalogosController@getSectores');
        //Lista de catalogo colonias
        $router->get('/colonias', 'CatalogosController@getColonia');
        //Lista de catalogo colonias por zona
        $router->get('/coloniasporzona/{id}', 'CatalogosController@getColoniaByZona');
        //Lista de catalogo colonias por distrito
        $router->get('/coloniaspordistrito/{distrito}/{zona}', 'CatalogosController@getColoniaByDistritos');
        //Lista de catalogo colonias por sectores
        $router->get('/coloniasporsector/{sector}/{zona}', 'CatalogosController@getColoniaBySectores');
        //Zona según alcaldía auxiliar
        $router->get('/zonaalcaldia/{id}', 'CatalogosController@getZonaByAlcaldia');
        //Lista de actividades
        $router->get('/actividades', 'CatalogosController@getCatActividades');
        //Zona según alcaldía auxiliar del usuario logeado
        $router->get('/z_alcaldia/{id}', 'CatalogosController@getZonaAlcaldia');
        //Rol del usuario logeado
        $router->get('/rol/{id}', 'CatalogosController@getRol');
        //Todas las zonas alcaldias para creación de usuarios
        $router->get('/zonasAlcaldias', 'CatalogosController@getZonasAlcaldias');


    });

    //Rutas Metas
    $router->group(["prefix"=>"/metas"], function()use($router){
        //METAS VECINOS SATISFECHOS
        //Ruta para listar metas vecinos satisfechos
        $router->get('/satisfechos/{id}','MetasSatisfechosController@getMetasSatisfechos');
        //Ruta para crear una meta de vecinos satisfechos
        $router->post('/satisfechos/crear','MetasSatisfechosController@postMetaSatisfecho');
        //Ruta para actualizar una meta de vecinos satisfechos
        $router->put('/satisfechos/actualizar/{id}','MetasSatisfechosController@putMetaSatisfecho');
        //Ruta para eliminar una meta
        $router->delete('/satisfechos/eliminar/{id}','MetasSatisfechosController@deleteMetaSatisfecho');

        //METAS VECINOS MUY SATISFECHOS
        //Ruta para listar metas vecinos muy satisfechos
        $router->get('/muysatisfechos/{id}','MetasMuySatisfechosController@getMetasMuySatisfechos');
        //Ruta para crear una meta de vecinos muy satisfechos
        $router->post('/muysatisfechos/crear','MetasMuySatisfechosController@postMetaMuySatisfecho');
        //Ruta para actualizar una meta de vecinos muy satisfechos
        $router->put('/muysatisfechos/actualizar/{id}','MetasMuySatisfechosController@putMetaMuySatisfecho');
        //Ruta para eliminar una meta
        $router->delete('/muysatisfechos/eliminar/{id}','MetasMuySatisfechosController@deleteMetaMuySatisfecho');

        //METAS MANTENIMIENTO VECINOS SATISFECHOS
        //Ruta para listar metas mantenimiento vecinos satisfechos
        $router->get('/mntosatisfechos/{id}','MetasMntoSatisfechosController@getMetasMntoSatisfechos');
        //Ruta para crear una meta de mantenimiento vecinos satisfechos
        $router->post('/mntosatisfechos/crear','MetasMntoSatisfechosController@postMetaMntoSatisfecho');
        //Ruta para actualizar una meta de mantenimiento vecinos satisfechos
        $router->put('/mntosatisfechos/actualizar/{id}','MetasMntoSatisfechosController@putMetaMntoSatisfecho');
        //Ruta para eliminar una meta
        $router->delete('/mntosatisfechos/eliminar/{id}','MetasMntoSatisfechosController@deleteMetaMntoSatisfecho');


        //METAS MANTENIMIENTO VECINOS MUY SATISFECHOS
        //Ruta para listar metas mantenimiento vecinos muy satisfechos
        $router->get('/mntomuysatisfechos/{id}','MetasMntoMuySatisfechosController@getMetasMntoMuySatisfechos');
        //Ruta para crear una meta de mantenimiento vecinos muy satisfechos
        $router->post('/mntomuysatisfechos/crear','MetasMntoMuySatisfechosController@postMetaMntoMuySatisfecho');
        //Ruta para actualizar una meta de mantenimiento vecinos muy satisfechos
        $router->put('/mntomuysatisfechos/actualizar/{id}','MetasMntoMuySatisfechosController@putMetaMntoMuySatisfecho');
        //Ruta para eliminar una meta
        $router->delete('/mntomuysatisfechos/eliminar/{id}','MetasMntoMuySatisfechosController@deleteMetaMntoMuySatisfecho');



    });

    //RUTAS DE REPORTES DE SEGUIMIENTO DE ACTIVIDADES NO REALIZADAS VECINOS SATISFECHOS, MUY SATISFECHOS Y SUS RESPECTIVOS MANTENIMIENTOS
    $router->group(["prefix"=>"/reporte"], function()use($router){
        //Reporte seguimiento vecinos satisfechos
        $router->get('/satisfechos/{id}', 'ReportesController@reporteSatisfechos');
        //Reporte seguimiento vecinos muy satisfechos
        $router->get('/muysatisfechos/{id}', 'ReportesController@reporteMuySatisfechos');
        //Reporte seguimiento mantenimiento vecinos satisfechos
        $router->get('/mntosatisfechos/{id}', 'ReportesController@reporteMntoSatisfechos');
        //Reporte seguimiento mantenimiento vecinos satisfechos
        $router->get('/mntomuysatisfechos/{id}', 'ReportesController@reporteMntoMuySatisfechos');

        //Reporte seguimiento vecinos satisfechos (excel)
        $router->get('/seguimientosatisfechos', 'SeguimientosExportController@reporteSatisfechos');
        //Reporte seguimiento vecinos mantenimiento satisfechos (excel)
        $router->get('/seguimientomntosatisfechos', 'SeguimientosExportController@reporteMntoSatisfechos');
        //Reporte seguimiento vecinos muy satisfechos (excel)
        $router->get('/seguimientomuysatisfechos', 'SeguimientosExportController@reporteMuySatisfechos');
        //Reporte seguimiento vecinos mantenimiento muy satisfechos (excel)
        $router->get('/seguimientomntomuysatisfechos', 'SeguimientosExportController@reporteMntoMuySatisfechos');
    });

    //RUTAS UTILIZADAS PARA DASHBOARD INDICADORES DE METAS VECINOS SATISFECHOS Y MUY SATISFECHOS - Y SUS MANTENIMIENTOS
    $router->group(["prefix"=>"/dashboard"], function()use($router){

    //DASHBOARD VECINOS SATISFECHOS:
    //Dashboard metas vecinos satisfechos de todas las alcaldias
        $router->get('/satisfechos/{id}', 'DashboardController@getMetasAlcaldias');
        //Dashboard metas vecinos satisfechos de todas las colonias segun distrito
        $router->get('/satisfechos/{id}/{distrito}', 'DashboardController@getMetasSatisfechosByDistrito');
        //Dashboard metas vecinos satisfechos colonias por alcaldia
        $router->get('/alcaldia/{id}', 'DashboardController@getMetasSatisfechosByColonia');
        //Dashboard metas vecinos satisfechos por distrito
        $router->get('/distrito/{id}', 'DashboardController@getMetasSatisfechosDistritos');
        //Dashboard metas vecinos satisfechos por sector (aplica para zona 11)
        $router->get('/porsector/{id}', 'DashboardController@getMetasSatisfechosBySector');
        //Dashboard meta global vecinos satisfechos por sector (aplica para zona 11)
        $router->get('/metaporsector/{id}', 'DashboardController@getMetasBySector');

        //DASHBOARD MANTENIMIENTO VECINOS SATISFECHOS
        //Dashboard metas vecinos satisfechos de todas las alcaldias
        $router->get('/mntosatisfechos', 'DashboardMntoSatisfechoController@getMetasAlcaldias');
        //Dashboard metas vecinos satisfechos de todas las alcaldias por id
        $router->get('/mntosatisfechos/{id}', 'DashboardMntoSatisfechoController@getMetasAlcaldiasById');
        //Dashboard metas vecinos satisfechos de todas las colonias segun distrito
        $router->get('/mntosatisfechos/{id}/{distrito}', 'DashboardMntoSatisfechoController@getMetasSatisfechosByDistrito');
        //Dashboard metas vecinos satisfechos colonias por alcaldia
        $router->get('/mntoalcaldia/{id}', 'DashboardMntoSatisfechoController@getMetasSatisfechosByColonia');
        //Dashboard metas vecinos satisfechos por distrito
        $router->get('/mntodistrito/{id}', 'DashboardMntoSatisfechoController@getMetasSatisfechosDistritos');

        $router->get('/mntodistritoAlcaldia/{id}/{distrito}', 'DashboardMntoSatisfechoController@getMetasSatisfechosByDistritoAlcaldia');
        //Dashboard metas vecinos satisfechos por sector (aplica para zona 11)
        $router->get('/mntoporsector/{id}', 'DashboardMntoSatisfechoController@getMetasSatisfechosBySector');
        //Dashboard meta global vecinos satisfechos por sector (aplica para zona 11)
        $router->get('/mntometaporsector/{id}', 'DashboardMntoSatisfechoController@getMetasBySector');

        //Dashboard global región 1 mantenimiento vecinos satisfechos
        $router->get('/mntosatisfechosregion', 'DashboardMntoSatisfechoController@getMetaRegionMntoSatisfechos');


        //DASHBOARD VECINOS MUY SATISFECHOS
        //Dashboard metas vecinos satisfechos de todas las alcaldias
        $router->get('/muysatisfechos', 'DashboardMuySatisfechos@getMetasAlcaldias');
        //Dashboard metas vecinos satisfechos de todas las colonias segun distrito
        $router->get('/muysatisfechos/{id}/{distrito}', 'DashboardMuySatisfechos@getMetasSatisfechosByDistrito');
        //Dashboard metas vecinos satisfechos colonias por alcaldia
        $router->get('/muyalcaldia/{id}', 'DashboardMuySatisfechos@getMetasSatisfechosByColonia');
        //Dashboard metas vecinos satisfechos por distrito
        $router->get('/muydistrito/{id}', 'DashboardMuySatisfechos@getMetasSatisfechosDistritos');
        //Dashboard metas vecinos satisfechos por sector (aplica para zona 11)
        $router->get('/muyporsector/{id}', 'DashboardMuySatisfechos@getMetasSatisfechosBySector');
        //Dashboard meta global vecinos satisfechos por sector (aplica para zona 11)
        $router->get('/muymetaporsector/{id}', 'DashboardMuySatisfechos@getMetasBySector');

        //DASHBOARD MANTENIMIENTO VECINOS MUY SATISFECHOS
        //Dashboard metas vecinos satisfechos de todas las alcaldias
        $router->get('/mntomuysatisfechos', 'DashboardMntoMuySatisfechoController@getMetasAlcaldias');
        //Dashboard metas vecinos satisfechos de todas las alcaldias por id
        $router->get('/mntomuysatisfechos/{id}', 'DashboardMntoMuySatisfechoController@getMetasAlcaldiasById');
        //Dashboard metas vecinos satisfechos de todas las colonias segun distrito
        $router->get('/mntomuysatisfechos/{id}/{distrito}', 'DashboardMntoMuySatisfechoController@getMetasSatisfechosByDistrito');
        //Dashboard metas vecinos satisfechos colonias por alcaldia
        $router->get('/mntomuyalcaldia/{id}', 'DashboardMntoMuySatisfechoController@getMetasSatisfechosByColonia');
        //Dashboard metas vecinos satisfechos por distrito
        $router->get('/mntomuydistrito/{id}', 'DashboardMntoMuySatisfechoController@getMetasSatisfechosDistritos');

         //Dashboard metas vecinos satisfechos por distrito para alcaldes
         $router->get('/mntomuydistritoAlcaldia/{id}/{distrito}', 'DashboardMntoMuySatisfechoController@getMetasSatisfechosDistritosAlcaldia');
        //Dashboard metas vecinos satisfechos por sector (aplica para zona 11)
        $router->get('/mntomuyporsector/{id}', 'DashboardMntoMuySatisfechoController@getMetasSatisfechosBySector');
        //Dashboard meta global vecinos satisfechos por sector (aplica para zona 11)
        $router->get('/mntomuymetaporsector/{id}', 'DashboardMntoMuySatisfechoController@getMetasBySector');

        //Dashboard meta global Region 1
        $router->get('/mntomuymetaregion', 'DashboardMntoMuySatisfechoController@getMetasRegionMntoMuySatisfecho');


    });
    //Rutas tableros de metas por promotor responsable
    $router->group(["prefix"=>"/tablero"], function()use($router){
    //Tablero Vecinos Satisfechos
        $router->get('/satisfechos', 'InicioController@getSatisfechos');
        //Tablero Mantenimiento Vecinos Satisfechos
        $router->get('/mntosatisfechos', 'InicioController@getMantenimientoSatisfechos');
        //Tablero Vecinos Muy Satisfechos
        $router->get('/muysatisfechos', 'InicioController@getMuySatisfechos');
        //Tablero Mantenimiento Vecinos Muy Satisfechos
        $router->get('/mntomuysatisfechos', 'InicioController@getMntoMuySatisfechos');

    });

});
