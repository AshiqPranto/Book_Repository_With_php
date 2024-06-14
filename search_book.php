<?php
// Autoloader
function autoloader($classname) {
    $lastSlash = strpos($classname, '\\') + 1;
    $classname = substr($classname, $lastSlash);
    $directory = $classname;
    // $directory = str_replace('\\', '/', $classname);
    $filename = __DIR__ . '\src'. '\\' . $directory . '.php';
    // echo "\nFile name = ", $filename, "\n";
    require_once($filename);
}
// echo __DIR__;
spl_autoload_register('autoloader');


use src\Utils\Config;
// require 'Books_Home_page.html';

$dbConfig = Config::getInstance()->get('db');
// echo $dbConfig['password']
// var_dump($dbConfig);
$db = new PDO(
    'mysql:host=127.0.0.1;dbname=bookstore',
    $dbConfig['user'],
    $dbConfig['password']
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$booksArray = $db->query('SELECT * FROM books'); // return a pdostatement
$booksArray = $booksArray->fetchAll(); // convert pdostatement to associative array
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Books</title>
</head>
<style>
    table, th, td {
        border:1px solid black;
    }
    th, td {
	    padding: 1rem;
    }
</style>
<body>
    <h2>Search Books</h2>

    <form method="post">
        <label for="searchField">Select Search Field:</label>
        <select id="searchField" name="searchField">
            <option value="title" <?php if(isset($_POST['searchField']) && $_POST['searchField'] === 'title') echo 'selected'; ?>>Title</option>
            <option value="isbn" <?php if(isset($_POST['searchField']) && $_POST['searchField'] === 'isbn') echo 'selected'; ?>>ISBN</option>
            <option value="author" <?php if(isset($_POST['searchField']) && $_POST['searchField'] === 'author') echo 'selected'; ?>>Author</option>
            <option value="price" <?php if(isset($_POST['searchField']) && $_POST['searchField'] === 'price') echo 'selected'; ?>>Price</option>
        </select>

        <label for="searchValue">Search Value:</label>
        <input type="text" id="searchValue" name="searchValue" value="<?php echo isset($_POST['searchValue']) ? htmlspecialchars($_POST['searchValue']) : ''; ?>">

        <button type="submit" name="searchBtn">Search</button>
    </form>

    <hr>

    <?php
    if (isset($_POST['searchBtn'])) {
        // Get search field and value from form submission
        $searchField = $_POST['searchField'];
        $searchValue = $_POST['searchValue'];

        // Load book data from JSON file
        // $jsonString = file_get_contents('books_repo.json');
        // $booksArray = json_decode($jsonString, true);

        // Filter books based on search criteria
        $filteredBooks = array_filter($booksArray, function($book) use ($searchField, $searchValue) {
            // Convert search value to lowercase for case-insensitive comparison
            $searchValue = strtolower($searchValue);

            // Convert book field value to lowercase for case-insensitive comparison
            $bookFieldValue = strtolower($book[$searchField]);

            // Check if the book field value contains the search value
            return strpos($bookFieldValue, $searchValue) !== false;
        });

        // Display search results in a table
        if (!empty($filteredBooks)) {
            echo '<h3>Search Results:</h3>';
            echo '<table border="1">';
            echo '<tr><th>Title</th><th>ISBN</th><th>Author</th><th>Price</th></tr>';
            foreach ($filteredBooks as $book) {
                echo '<tr>';
                echo '<td>' . $book['title'] . '</td>';
                echo '<td>' . $book['isbn'] . '</td>';
                echo '<td>' . $book['author'] . '</td>';
                echo '<td>' . $book['price'] . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<p>No books found matching the search criteria.</p>';
        }
    }
    ?>
</body>
</html>
