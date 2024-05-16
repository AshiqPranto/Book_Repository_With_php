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
            <option value="pages" <?php if(isset($_POST['searchField']) && $_POST['searchField'] === 'pages') echo 'selected'; ?>>Pages</option>
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
        $jsonString = file_get_contents('books_repo.json');
        $booksArray = json_decode($jsonString, true);

        // Filter books based on search criteria
        $filteredBooks = array_filter($booksArray['books'], function($book) use ($searchField, $searchValue) {
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
            echo '<tr><th>Title</th><th>ISBN</th><th>Author</th><th>Pages</th></tr>';
            foreach ($filteredBooks as $book) {
                echo '<tr>';
                echo '<td>' . $book['title'] . '</td>';
                echo '<td>' . $book['isbn'] . '</td>';
                echo '<td>' . $book['author'] . '</td>';
                echo '<td>' . $book['pages'] . '</td>';
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
