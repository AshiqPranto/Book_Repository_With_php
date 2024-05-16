<?php
$booksArray = json_decode(file_get_contents('books_repo.json'), true);

if (isset($_GET['index']) && isset($booksArray['books'][$_GET['index']])) {
    $index = $_GET['index'];
    $book = $booksArray['books'][$index];
} else {
    // Handle invalid index or book not found error
    echo "Invalid book index or book not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Book</title>
</head>
<body>
    <h2>Update Book</h2>
    <form action="" method="post">
        <input type="hidden" name="index" value="<?php echo $index; ?>">
        <label>Title:</label>
        <br>
        <input type="text" name="title" value="<?php echo $book['title']; ?>"><br>
        <label>ISBN:</label> <br>
        <input type="text" name="isbn" value="<?php echo $book['isbn']; ?>"><br>
        <label>Author:</label>
        <br>
        <input type="text" name="author" value="<?php echo $book['author']; ?>"><br>
        <label>Pages:</label>
        <br>
        <input type="text" name="pages" value="<?php echo $book['pages']; ?>"><br>
        <input type="submit" value="Update">
    </form>
</body>
</html>


<?php
// Load books data from JSON file
$jsonFile = 'books_repo.json';
$booksArray = json_decode(file_get_contents($jsonFile), true);

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $index = $_POST['index'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $pages = $_POST['pages'];

    // Update book in the $booksArray
    if (isset($booksArray['books'][$index])) {
        $booksArray['books'][$index]['title'] = $title;
        $booksArray['books'][$index]['author'] = $author;
        $booksArray['books'][$index]['pages'] = $pages;

        // Write updated array back to JSON file
        file_put_contents($jsonFile, json_encode($booksArray, JSON_PRETTY_PRINT));

        // Redirect to index.php after updating
        header('Location: index.php');
        // exit; // Ensure script stops after redirect
    } else {
        // Handle invalid book index error
        echo "Invalid book index.";
    }
}
?>