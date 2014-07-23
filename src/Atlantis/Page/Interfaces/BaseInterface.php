<?php namespace Atlantis\Page\Interfaces;


interface BaseInterface {

    public function all();

    public function create($create_array);

    public function find($id);

    public function first($field, $value);

    public function orderBy($field, $order);

    public function orderByAndPaginate($field, $order, $per_page);

    public function paginate($per_page);

    public function save();

    public function delete($id);

    public function instance();

}