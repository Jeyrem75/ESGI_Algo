<?php 

class Book {
    public $id;
    public $name;
    public $description;
    public $inStock;

    public function __construct($name, $description, $inStock) {
        $this->id = uniqid();
        $this->name = $name;
        $this->description = $description;
        $this->inStock = $inStock;
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'inStock' => $this->inStock
        ];
    }
}
