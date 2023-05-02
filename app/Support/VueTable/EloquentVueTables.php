<?php

namespace App\Support\VueTable;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class EloquentVueTables implements VueTablesInterface
{
    public function get($data, Array $fields,  Array $searchable_fields, Array $relationsFiter = []) {
        extract(\Input::only('query', 'limit', 'page', 'orderBy', 'ascending', 'byColumn'));

        //        if (count($relations) > 0):
        //            $data->with($relations);
        //        endif;


        if (isset($query) && $query) {
            $data = $byColumn == 1 ? $this->filterByColumn($data, $query):
            $this->filter($data, $query, $searchable_fields, $relationsFiter);

        }

        $count = $data->count();

        $data->limit($limit)
            ->skip($limit * ($page-1));

        if (isset($orderBy) && $orderBy):
            $direction = $ascending==1?"ASC":"DESC";
            $data->orderBy($orderBy,$direction);
        endif;



        $results = $data->get();

        return [
            'data' => $results,
            'count' => $count
        ];
    }

    public function handleFilters($data, Array $fields,  Array $searchable_fields, Array $relationsFiter = []) {
        extract(\Input::only('query', 'limit', 'page', 'orderBy', 'ascending', 'byColumn'));

        if (isset($query) && $query) {
            $data = $byColumn == 1 ? $this->filterByColumn($data, $query):
            $this->filter($data, $query, $searchable_fields, $relationsFiter);

        }

        return $data;
    }

    public function handlePaginate($data, Array $fields,  Array $searchable_fields, Array $relationsFiter = []) {
        extract(\Input::only('query', 'limit', 'page', 'orderBy', 'ascending', 'byColumn'));

        $count = $data->count();

        $data->limit($limit)
            ->skip($limit * ($page-1));


        $results = $data->get();

        return [
            'data' => $results,
            'count' => $count
        ];
    }

    protected function filterByColumn($data, $queries)
    {
        return $data->where(function ($q) use ($queries) {
            foreach ($queries as $field => $query) {
                if (is_string($query)) {
                    $q->where($field, 'LIKE', "%{$query}%");
                } else {
                    $start = Carbon::createFromFormat('Y-m-d', $query['start'])->startOfDay();
                    $end = Carbon::createFromFormat('Y-m-d', $query['end'])->endOfDay();

                    $q->whereBetween($field, [$start, $end]);
                }
            }
        });
    }

    protected function filter($data, $query, $fields, $relationsFiter)
    {

        $data->where(function($subQuery) use ($relationsFiter, $query)
        {   
            $first_key = array_key_first($relationsFiter);
            foreach ($relationsFiter as $index => $field) {
                $relation_method = $first_key === $index ? 'whereHas' : 'orWhereHas';
                $subQuery->{$relation_method}($index, function ($q) use ($query,$field) {
                    foreach ($field as $index => $va){
                        $method = $index ? 'orWhere' : 'where';
                        $q->{$method}($va, 'like', "%{$query}%");
                    }
                });
            }
        });

        if(count($relationsFiter) > 0 ){
            $data->orWhere(function ($q) use ($query, $fields) {
                foreach ($fields as $index => $field) {
                    $method = $index ? 'orWhere' : 'where';
                    $q->{$method}($field, 'LIKE', "%{$query}%");
                }
            });
        }else{
            $data->where(function ($q) use ($query, $fields) {
                foreach ($fields as $index => $field) {
                    $method = $index ? 'orWhere' : 'where';
                    $q->{$method}($field, 'LIKE', "%{$query}%");
                }
            });
        }
        return $data;
    }
}
