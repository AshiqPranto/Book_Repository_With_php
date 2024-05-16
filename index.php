<?php
$looking = isset($_GET['title']) || isset($_GET['author']);
// require 'Books_Home_page.html';

$jsonString = file_get_contents('books_repo.json');
$booksArray = json_decode($jsonString, true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Books Form</title>
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
    <h2>Books Form</h2>
    <button type="button" onclick="addBook()">Add Book</button>
    <button type="button" onclick="searchBook()">Search Book</button>
    <table >
        <tr>
            <th>Title</th>
            <th>ISBN</th>
            <th>Author</th>
            <th>Pages</th>
        </tr>
        <?php foreach ($booksArray['books'] as $index => $book): ?>
                <tr>
                    <td><?php echo $booksArray['books'][$index]['title']?></td>
                    <td><?php echo $booksArray['books'][$index]['isbn']?></td>
                    <td><?php echo $booksArray['books'][$index]['author']?></td>
                    <td><?php echo $booksArray['books'][$index]['pages']?></td>
                    <td>
                        <button type="button" onclick="redirectToUpdatePage(<?php echo $index;?>)">Update</button>
                    </td>
                    <td><button type="button" onclick="deleteBook(<?php echo $index;?>)">Delete</button></td>
                </tr>
        <?php endforeach; ?>

        <!-- <input type="submit" value="Submit"> -->
    </table>
</body>
<script>
    function searchBook(){
        window.location.href = "search_book.php";
    }
    function addBook(){
        window.location.href = "add_book.php";
    }
    function redirectToUpdatePage(index) {
        // print(index)
        console.log(index);
        
        window.location.href = "update_book.php?index=" + index;

    }

    function deleteBook(index) {
        console.log(index);
        if (confirm('Are you sure you want to delete this book?')) {

            // Send AJAX request to delete_book.php
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_book.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Reload the page after successful deletion
                        location.reload();
                    } else {
                        console.error('Failed to delete book');
                    }
                }
            };
            xhr.send("index=" + index);
        }
    //     if (confirm('Are you sure you want to delete this book?')) {
    //         print(index)
    //         // Send AJAX request to delete book from server-side array
    //         // Alternatively, update $booksArray in PHP and refresh the page
    //         <?php
    //         echo "index: " . $index;
    //         // Assuming $booksArray is a PHP variable that holds the book data
    //         // Remove book from the array using array_splice
    //         print_r($booksArray['books']);
    //         array_splice($booksArray['books'], $index, 1);
    //         print_r($booksArray['books']);
            
    //         // file_put_contents("books_repo.json", json_encode($booksArray, JSON_PRETTY_PRINT));
    //         // header('Location: ');

    //         // Update HTML table by removing the corresponding row
    //         // For example:
    //         ?>
    //         // document.querySelector('table tr:nth-child(' + (index + 1) + ')').remove();

    //     }
    }
</script>
</html>


