<form action="" method="post">
        <input type="hidden" name="index" value="">
        <label>Title:</label>
        <br>
        <input type="text" name="title" value=""><br>
        <label>ISBN:</label> <br>
        <input type="text" name="isbn" value=""><br>
        <label>Author:</label>
        <br>
        <input type="text" name="author" value=""><br>
        <label>Pages:</label>
        <br>
        <input type="text" name="pages" value=""><br>
        <input type="submit" value="Submit">
</form>


<?php
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve form data
        $title = $_POST['title'];
        $isbn = $_POST['isbn'];
        $author = $_POST['author'];
        $pages = $_POST['pages'];

        // Validate form data (e.g., ensure required fields are not empty)

        // Load existing book data from JSON file
        $jsonString = file_get_contents('books_repo.json');
        $booksArray = json_decode($jsonString, true);

        // Create new book object
        $newBook = [
            'title' => $title,
            'isbn' => $isbn,
            'author' => $author,
            'pages' => $pages
        ];

        // Add the new book to the books array
        $booksArray['books'][] = $newBook;

        // Encode array back to JSON
        $newJsonString = json_encode($booksArray, JSON_PRETTY_PRINT);

        // Write JSON back to the file
        file_put_contents('books_repo.json', $newJsonString);

        // Redirect to books listing page or display success message
        header('Location: index.php'); // Redirect to a listing page
        exit;
    }

?>