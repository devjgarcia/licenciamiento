<?php

namespace App\Exports;

use App\Models\Licencias;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;


class LicenciasExport implements FromCollection, WithHeadings, WithProperties, WithColumnWidths, WithStyles
{
    protected $estado;

    public function __construct($estado)
    {
        $this->estado = $estado;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Licencias::where('prefijo', 'SM');

        if( $this->estado >= 1 ){
            $query->where('status', $this->estado);
        }

        $query->select([
            'codigo',
            'frkcliente',
            'frkproducto',
            'inicio',
            'vencimiento',
            //'status',
            
            DB::raw('CASE status WHEN 1 THEN "Activa"
                    WHEN 2 THEN "Demo"
                    WHEN 3 THEN "Vencida"
                    ELSE "--"
                    END 
                    AS status
            '),
            
            'nombre_pais',
            'codigo_pais',
            'persona',
            'correo',
            'telefono',]);

        $query->orderBy('id', 'asc');
        //$query->makeHidden(['class_estado', 'vencimiento_format', 'estado_licencia']);
        return $query->get()->makeHidden(['class_estado', 'vencimiento_format', 'estado_licencia']);
        
        //->makeHidden(['class_estado', 'vencimiento_format', 'estado_licencia']);
        //return $query;
    }

    public function headings(): array
    {
        return [
            'Código',
            'Empresa',
            'Tipo de Producto',
            'Fecha de Inicio',
            'Fecha de Vencimiento',
            'Estatus',
            'Pais',
            'Código de Pais',
            'Persona',
            'Correo',
            'Teléfono',
        ];
    }

    public function properties(): array
    {
        return [
            'codigo'             => 'Código',
            'frkcliente'         => 'Empresa',
            'frkproducto'        => 'Tipo de Producto',
            'inicio'             => 'Fecha de Inicio',
            'vencimiento_format' => 'Fecha de Vencimiento',
            'estado_licencia'    => 'Estatus',
            'nombre_pais'        => 'País',
            'codigo_pais'        => 'Código de País',
            'persona'            => 'Persona',
            'correo'             => 'Correo',
            'telefono'           => 'Teléfono',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 60,            
            'C' => 20,            
            'D' => 20,            
            'E' => 20,            
            'F' => 30,            
            'G' => 30,            
            'H' => 15,            
            'I' => 35,            
            'J' => 35,            
            'K' => 35,            
        ];
    }


    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => [
                'font' => ['bold' => true, 'size' => 14,],
                'fill' => [
                    'color' => array('rgb' => 'FF0000')
                ]
            ],

            // Styling a specific cell by coordinate.
            'A' => ['font' => ['bold' => true]],
            'F' => ['font' => ['bold' => true]],
        ];
    }
}
