<?php 

require_once 'Book.php';
require_once 'Library.php';

function displayMenu() {
    echo "Library Management System\n";
    echo "1. Add Book\n";
    echo "2. Update Book\n";
    echo "3. Delete Book\n";
    echo "4. List Books\n";
    echo "5. View Book\n";
    echo "6. Sort Books\n";
    echo "7. Search Book\n";
    echo "8. View Logs\n";
    echo "9. Exit\n";
}

function main() {
    $library = new Library();
    do {
        displayMenu();
        $choice = intval(trim(fgets(STDIN)));
        switch ($choice) {
            case 1:
                echo "Enter book name: ";
                $name = trim(fgets(STDIN));
                echo "Enter book description: ";
                $description = trim(fgets(STDIN));
                echo "Is the book in stock (1 for Yes, 0 for No): ";
                $inStock = intval(trim(fgets(STDIN)));
                $library->addBook($name, $description, $inStock);
                break;
            case 2:
                echo "Enter book ID to update: ";
                $id = trim(fgets(STDIN));
                echo "Enter new book name: ";
                $name = trim(fgets(STDIN));
                echo "Enter new book description: ";
                $description = trim(fgets(STDIN));
                echo "Is the book in stock (1 for Yes, 0 for No): ";
                $inStock = intval(trim(fgets(STDIN)));
                $library->updateBook($id, $name, $description, $inStock);
                break;
            case 3:
                echo "Enter book ID to delete: ";
                $id = trim(fgets(STDIN));
                $library->deleteBook($id);
                break;
            case 4:
                $library->listBooks();
                break;
            case 5:
                echo "Enter book ID to view: ";
                $id = trim(fgets(STDIN));
                $library->viewBook($id);
                break;
            case 6:
                echo "Enter column to sort by (name, description, inStock): ";
                $column = trim(fgets(STDIN));
                $library->sortBooks($column);
                break;
            case 7:
                echo "Enter column to search by (name, description, inStock): ";
                $column = trim(fgets(STDIN));
                echo "Enter value to search for: ";
                $value = trim(fgets(STDIN));
                $library->searchBook($column, $value);
                break;
            case 8:
                $library->listLogs();
                break;
            case 9:
                exit;
            default:
                echo "Invalid choice. Please try again.\n";
        }
    } while (true);
}

main();
