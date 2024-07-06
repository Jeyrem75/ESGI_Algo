<?php

require_once 'Book.php';

class Library {
    private $books;
    private $logFile = 'logs.json';
    private $dataFile = 'books.json';

    public function __construct() {
        $this->books = $this->loadBooks();
    }

    public function addBook($name, $description, $inStock) {
        $book = new Book($name, $description, $inStock);
        $this->books[] = $book;
        $this->saveBooks();
        $this->logAction("Created book with ID: {$book->id}");
    }

    public function updateBook($id, $name, $description, $inStock) {
        foreach ($this->books as $book) {
            if ($book->id === $id) {
                $book->name = $name;
                $book->description = $description;
                $book->inStock = $inStock;
                $this->saveBooks();
                $this->logAction("Updated book with ID: $id");
                return;
            }
        }
        echo "Book not found.\n";
    }

    public function deleteBook($id) {
        foreach ($this->books as $key => $book) {
            if ($book->id === $id) {
                unset($this->books[$key]);
                $this->saveBooks();
                $this->logAction("Deleted book with ID: $id");
                return;
            }
        }
        echo "Book not found.\n";
    }

    public function listBooks() {
        foreach ($this->books as $book) {
            echo "ID: {$book->id}, Name: {$book->name}, Description: {$book->description}, In Stock: " . ($book->inStock ? 'Yes' : 'No') . "\n";
        }
        $this->logAction("Listed all books");
    }

    public function viewBook($id) {
        foreach ($this->books as $book) {
            if ($book->id === $id) {
                echo "ID: {$book->id}, Name: {$book->name}, Description: {$book->description}, In Stock: " . ($book->inStock ? 'Yes' : 'No') . "\n";
                $this->logAction("Viewed book with ID: $id");
                return;
            }
        }
        echo "Book not found.\n";
    }

    public function sortBooks($column) {
        usort($this->books, function($a, $b) use ($column) {
            return strcmp($a->$column, $b->$column);
        });
        $this->logAction("Sorted books by $column");
    }

    public function searchBook($column, $value) {
        $this->quickSort($this->books, 0, count($this->books) - 1, $column);
        $result = $this->binarySearch($this->books, $column, $value);
        if ($result) {
            echo "ID: {$result->id}, Name: {$result->name}, Description: {$result->description}, In Stock: " . ($result->inStock ? 'Yes' : 'No') . "\n";
        } else {
            echo "Book not found.\n";
        }
        $this->logAction("Searched book by $column with value $value");
    }

    private function loadBooks() {
        if (!file_exists($this->dataFile)) {
            return [];
        }
        $data = json_decode(file_get_contents($this->dataFile), true);
        return array_map(function($book) {
            $b = new Book($book['name'], $book['description'], $book['inStock']);
            $b->id = $book['id'];
            return $b;
        }, $data);
    }

    private function saveBooks() {
        $data = array_map(function($book) {
            return $book->toArray();
        }, $this->books);
        file_put_contents($this->dataFile, json_encode($data));
    }

    private function logAction($action) {
        $log = [
            'action' => $action,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        $logs = $this->getLogs();
        $logs[] = $log;
        file_put_contents($this->logFile, json_encode($logs));
    }

    private function getLogs() {
        if (!file_exists($this->logFile)) {
            return [];
        }
        return json_decode(file_get_contents($this->logFile), true);
    }

    public function listLogs() {
        $logs = $this->getLogs();
        foreach ($logs as $log) {
            echo "Action: {$log['action']}, Timestamp: {$log['timestamp']}\n";
        }
    }

    private function quickSort(&$array, $low, $high, $column) {
        if ($low < $high) {
            $pi = $this->partition($array, $low, $high, $column);
            $this->quickSort($array, $low, $pi - 1, $column);
            $this->quickSort($array, $pi + 1, $high, $column);
        }
    }

    private function partition(&$array, $low, $high, $column) {
        $pivot = $array[$high]->$column;
        $i = ($low - 1);
        for ($j = $low; $j <= $high - 1; $j++) {
            if ($array[$j]->$column < $pivot) {
                $i++;
                $this->swap($array, $i, $j);
            }
        }
        $this->swap($array, $i + 1, $high);
        return ($i + 1);
    }

    private function swap(&$array, $i, $j) {
        $temp = $array[$i];
        $array[$i] = $array[$j];
        $array[$j] = $temp;
    }

    private function binarySearch($array, $column, $value) {
        $low = 0;
        $high = count($array) - 1;
        while ($low <= $high) {
            $mid = floor(($low + $high) / 2);
            if ($array[$mid]->$column == $value) {
                return $array[$mid];
            }
            if ($array[$mid]->$column < $value) {
                $low = $mid + 1;
            } else {
                $high = $mid - 1;
            }
        }
        return null;
    }
}
