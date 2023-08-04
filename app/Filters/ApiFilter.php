<?php

namespace App\Filters;

use Illuminate\Http\Request;

class ApiFilter
{
    protected $safeParms = [];

    protected $columnMap = [];

    protected $operatorMap = [];

    public function transform(Request $request)
    {
        // Query yang akan digunakan untuk filter

        $eloQuery = [];

        // Melakukan perulangan terhadap safe parms

        foreach ($this->safeParms as $parm => $operators) {
            // Mendapatkan query yang diinputkan user
            // Input user: http://127.0.0.1:8000/api/v1/customers?state[eq]=Kentucky
            // Hasil: [eq => Kentucky]

            $query = $request->query($parm);

            // Jika query tersebut tidak ada isinya
            // Maka continue

            if (!isset($query)) {
                continue;
            }

            // Mendapatkan nama kolom yang sebenarnya
            // Karena sebelumnya diubah menjadi camelCase
            // postalCode menjadi postal_code

            $column = $this->columnMap[$parm] ?? $parm;

            // Melakukan perulangan terhadap operators
            // yang diperbolehkan
            // Contoh: postalCode mendukung eq, gt, lt
            // Sementara
            // state hanya mendukung eq

            foreach ($operators as $operator) {
                // Jika query yang diinputkan user sesuai 
                // dengan query yang diperbolehkan,
                // maka buat query

                // Contoh: state hanya memperbolehkan eq
                // Jadi, jika user menginputkan
                // ...?state[gt]=2000
                // Maka tidak perlu melakukan apapun
                // Selain itu, buat query
                // Anggap ... adalah URL yang digunakan

                if (isset($query[$operator])) {
                    // Menambahkan query
                    // Contoh: [state, =, Kentucky]

                    $eloQuery[] = [$column, $this->operatorMap[$operator], $query[$operator]];
                }
            }
        }

        return $eloQuery;
    }
}
