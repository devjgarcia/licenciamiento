@extends('layouts.app')

@section('title', 'Bienvenido, '.Auth::user()->name)

@section('content')
<div class="container-fluid">
    <div class="row justify-start mt-4 py-3">
        <div class="@if( Auth::user()->isSistemas() || Auth::user()->isAdmin() ) col-md-6 @else col-md-12 @endif">
            <div class="col-md-12 text-center py-4">
                <h5>Licencias</h5>
            </div>
            <div class="row py-4 mt-4">
                <div class="@if( Auth::user()->isSistemas() || Auth::user()->isAdmin() ) col-md-4 @else col-md-4 @endif">
                    <div class="card">
                        <div class="card-header bg-success text-white text-bold">
                            <i class="fa fa-check"></i>
                            Activas ({{ $activas }})
                        </div>
                        <div class="card-body text-center">
                            <a href="/descarga-licencias/1" title="Descargar Listado" class="btn btn-success btn-sm">
                                <i class="fa fa-file-download"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="@if( Auth::user()->isSistemas() || Auth::user()->isAdmin() ) col-md-4 @else col-md-4 @endif">
                    <div class="card">
                        <div class="card-header bg-primary text-white text-bold">
                            <i class="fa fa-shopping-cart"></i>
                            Demo ({{ $demo }})
                        </div>
                        <div class="card-body text-center">
                            <a href="/descarga-licencias/2" title="Descargar Listado" class="btn btn-primary btn-sm">
                                <i class="fa fa-file-excel"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="@if( Auth::user()->isSistemas() || Auth::user()->isAdmin() ) col-md-4 @else col-md-4 @endif">
                    <div class="card">
                        <div class="card-header bg-danger text-white text-bold">
                            <i class="fa fa-calendar-minus"></i>
                            Vencidas ({{ $vencidas }})
                        </div>
                        <div class="card-body text-center">
                            <a href="/descarga-licencias/3" title="Descargar Listado" class="btn btn-danger btn-sm">
                                <i class="fa fa-file-excel"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 text-center mt-2 py-3">
                <a href="/descarga-licencias/0" class="btn btn-secondary btn-lg">
                    <i class="fa fa-file-excel"></i> Descargar Listado Completo
                </a>
            </div>
        </div>
        @if( Auth::user()->isSistemas() || Auth::user()->isAdmin() )
            <div class="col-md-6">
                <div class="row py-3 mt-4">
                    <div class="col-md-12">
                        <canvas id="lice-disponibles" style="width: 100%; height: 40vh;"></canvas>
                    </div>
                    <div class="col-12 text-right">
                        <button type="button" title="Actualizar gráfico" class="btn btn-success btn-sm" onclick="updateValores()">
                            <i class="fa fa-chart-bar"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('custom_script')
    <script src="{{ asset('js/chart.js') }}"></script>

    <script>
        let datos = {asignadas: 0, disponibles: 0};

        // Datos de ejemplo para el gráfico
        let datasets = {
            labels: ["Licencias"],
            datasets: [
                {
                    label: 'Disponibles',
                    data: [datos.disponibles],
                    backgroundColor: '#38c172',
                    borderColor: '#38c172',
                    borderWidth: 1
                },
                {
                    label: 'Asignadas',
                    data: [datos.asignadas],
                    backgroundColor: 'rgba(0, 60, 192, 1)',
                    borderColor: 'rgba(0, 60, 192, 1)',
                    borderWidth: 1
                },
            ]
        };

        // Configuración del gráfico
        const opciones = {
            scales: {
                y: {
                    beginAtZero: true,
                },
            }
        };

        // Obtén el contexto del lienzo
        let ctx = document.getElementById('lice-disponibles').getContext('2d');

        // Crea el gráfico
        let miGrafico = new Chart(ctx, {
            type: 'bar',
            data: datasets,
            options: opciones
        });

        const updateValores = async () => {
            await axios.get(`/lice_dispo_estadisticas`)
                .then(response => {

                    let data = response.data[0];
    	            
                    miGrafico.data.datasets[0].data = [data.LicDisponibles];
                    miGrafico.data.datasets[1].data = [data.LicTotales];
                    
                    miGrafico.options.scales.y.max = parseInt(data.LicTotales) + 10;
                    miGrafico.options.scales.y.stepSize = 5;
                    miGrafico.update();
                });
        }

        updateValores();

        
    </script>
@endsection