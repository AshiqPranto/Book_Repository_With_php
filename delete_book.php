<?php
$jsonString = file_get_contents('books_repo.json');
$booksArray = json_decode($jsonString, true);

if (isset($_POST['index'])) {
    $index = $_POST['index'];

    // Remove the book from the array
    if (isset($booksArray['books'][$index])) {
        unset($booksArray['books'][$index]);

        // Reassign keys to maintain continuity if necessary
        $booksArray['books'] = array_values($booksArray['books']);

        // Encode array back to JSON
        $newJsonString = json_encode($booksArray, JSON_PRETTY_PRINT);

        // Write JSON back to the file
        file_put_contents('books_repo.json', $newJsonString);

        // Return success response
        http_response_code(200);
        exit;
    }
}

// If index is not provided or book is not found
http_response_code(400);
exit;
?>
