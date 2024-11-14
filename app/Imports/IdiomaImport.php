<?php

namespace App\Imports;

use App\Models\Idioma;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class IdiomaImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            Idioma::create([
                'codsis'         => date('y').rand(1,99).date('s').rand(10, 999),
                'codidio'        => 'LABEL_'.rand(10, 999),
                'itemidioorigen' => $row['spanish'],
                'idio_eng'       => $row['english'],
                'idio_fra'       => $row['french'],
                'idio_ara'       => $row['arab'],
                'idio_por'       => $row['portuguese'],
                'ideo_03'        => $row['ubicacion'],
                'idio_padre'     => $row['seccion'],
                'observ_idio'    => substr($row['comentario'], 0, 149),
            ]);
        }
    }

    public function headingRow(): int
    {
        return 1; //this should be the row number of heading row
    }
}
