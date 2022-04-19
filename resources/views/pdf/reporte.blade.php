<html>
    <head>
        <style>
            /** Define the margins of your page **/
            @page {
                margin: 100px 25px;
            }

            header {
                position: fixed;
                top: -60px;
                left: 0px;
                right: 0px;
                height: 100px;

                /** Extra personal styles **/

                text-align: center;
                line-height: 20px;
            }

            footer {
                position: fixed; 
                bottom: -60px; 
                left: 0px; 
                right: 0px;
                height: 50px; 

                /** Extra personal styles **/
                background-color: #03a9f4;
                color: white;
                text-align: center;
                line-height: 35px;
            }

        </style>
    </head>
    <body>
        <!-- Define header and footer blocks before your content -->
        <header>
          <blockquote class="blockquote text-center">
            <h3>Reporte de seguimientos de actividades no realizadas <br>
              @foreach($titulo as $item)
              {{$item}}
              @endforeach
              @foreach($alcaldia as $name)
            {{$name->alcaldia}}
            @endforeach
            <br>
            {{ date('d/m/Y') }}
            </h3>  
        </blockquote>
        </header>

        {{--  <footer>
            Copyright &copy; <?php echo date("Y");?> 
        </footer>  --}}

        <!-- Wrap the content of your PDF inside a main tag -->
        <main>
          <br>
          <br>
          <br>
            <p style="page-break-after: never;">
              <div style="text-align:center;">
                <table CELLSPACING="20">
                  <thead>
                    <tr>
                      <th scope="col">Fecha</th>
                      <th scope="col">Vecino</th>
                      <th scope="col">Actividad</th>
                      <th scope="col">Descripción</th>
                      <th scope="col">Responsable</th>
                      <th scope="col">Auditado por</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($reportes as $item)
                    <tr>
                      <td>{{ $item->fecha }}</td>
                      <td>{{ $item->pNombre }} {{ $item->pApellido }}</td>
                      <td>{{ $item->actividad}}</td>
                      <td>{{ $item->descripcion}}</td>
                      <td>{{ $item->responsable}}</td>
                      <td>{{ $item->auditor}}</td>
                    </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>
            </p>
            
        </main>
    </body>
</html>