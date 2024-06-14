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

$looking = isset($_GET['title']) || isset($_GET['author']);

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

$booksArray = $db->query('SELECT * FROM books');
// foreach($booksArray as $book){
//     print_r($book);
//     // echo "\n";
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
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
    <div class="flex justify-center">
        <button class="bg-green-700 hover:bg-green-900 text-white font-bold py-2 px-4 rounded-full mx-5 my-5" type="button" onclick="addBook()">Add Book</button>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full mx-5 my-5" type="button" onclick="searchBook()">Search Book</button>
    </div>
    <table class="table-auto mx-auto w-f">
       <thead>
           <tr>
               <th class="bg-teal-300 w-1/4">Title</th>
               <th class="bg-teal-300 w-1/4">ISBN</th>
               <th class="bg-teal-300 w-1/4">Author</th>
               <th class="bg-teal-300 w-1/7">Price</th>
           </tr>
       </thead>
        <?php foreach ($booksArray as $index => $book): ?>
            <tr>
                <td class="bg-slate-300"><?php echo $book['title']?></td>
                <td class="bg-slate-300"><?php echo $book['isbn']?></td>
                <td class="bg-slate-300"><?php echo $book['author']?></td>
                <td class="bg-slate-300"><?php echo $book['price']?></td>
                <td class="bg-slate-300">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full" type="button" onclick="redirectToUpdatePage(<?php echo $index;?>)">Update</button>
                </td>
                <td class="bg-slate-300"><button class="bg-red-500 hover:bg-red-900 text-white font-bold py-2 px-4 rounded-full" type="button" onclick="deleteBook(<?php echo $index;?>)">Delete</button></td>
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
    }
</script>
</html>


