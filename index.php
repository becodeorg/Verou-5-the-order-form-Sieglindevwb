<?php

declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

$_SESSION['input_data'] = $_POST;

// Use the current input data, if available, otherwise return empty string
$inputData = $_SESSION['input_data'] ?? [];

function dd (array $array){

    echo "<pre>";
    var_dump($array);
    echo "<pre>";
    die();
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$products = [
    ['name' => 'The One With All the Cheesecakes', 'price' => 10.7],
    ['name' => 'The One With the Chickenwings', 'price' => 11.6],
    ['name' => 'The One With Phoebe`s Cookies', 'price' => 5.3],
    ['name' => 'The One With The Fried stuff with cheese', 'price' => 7.4],
    ['name' => 'Joey doesn`t share food!', 'price' => 4.4],
    ['name' => 'The One With the Jam', 'price' => 1.4],
    ['name' => 'The One With the Dozen Lasagnas', 'price' => 12.6],
    ['name' => 'A show called “Mac and C.H.E.E.S.E."', 'price' => 10.6],
    ['name' => 'The One With Ross`s Sandwich.', 'price' => 7.2],
    ['name' => '“Custard? Good. Jam? Good. Meat? Gooooood.”', 'price' => 16.2],
    ['name' => 'The One With Unagi', 'price' => 14.3],
];

// Variable to track total order value
$totalValue = 0;

function validate($formData) {
    /* //Tutorial Basile
    $error = [];
    foreach ($_POST as $key => $value) {
        if ($value !== "") {
            if ($key === "email" || !filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) 
        } else {
        errors[$key] = "The field: $key is not empty"};
    }

    if (!isset($_POST["products"])) {
        $errors[] = "please select product";
    }
        */
    $errors = [];

 // Validation rules
    if (empty($formData['email']) || !filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }

    if (empty($formData['street'])) {
        $errors['street'] = 'Please enter your street.';
    }

    if (empty($formData['streetnumber']) || !is_numeric($formData['streetnumber'])) {
        $errors['streetnumber'] = 'Please enter a valid numeric value for street number';
    }

    if (empty($formData['city'])) {
        $errors['city'] = 'Please enter your city';
    }

    if (empty($formData['zipcode']) || !is_numeric($formData['zipcode'])) {
        $errors['zipcode'] = 'Please enter a valid numeric value for zipcode';
    }

    return $errors;
}

// Handle form submission
function handleForm() {

    global $products;

    $formData = $_POST ?? [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Extract and sanitize form data
        $email = isset($formData['email']) ? test_input($formData['email']) : '';
        $street = isset($formData['street']) ? test_input($formData['street']) : '';
        $streetnumber = isset($formData['streetnumber']) ? test_input($formData['streetnumber']) : '';
        $city = isset($formData['city']) ? test_input($formData['city']) : '';
        $zipcode = isset($formData['zipcode']) ? test_input($formData['zipcode']) : '';

        // Selected products array
        $selectedProducts = [];

        // Process selected products
        if (isset($formData['products']) && is_array($formData['products'])) {
            foreach ($formData['products'] as $i => $value) {
                if ($value == 1) {
                    $selectedProducts[] = $products[$i]['name'];
                }
            }
        }

        // Validation
        $invalidFields = validate($formData);

        // Handle validation errors or successful submission
        if (!empty($invalidFields)) {
            echo '<div class="alert alert-danger" role="alert">';
            echo '<h4 class="alert-heading">Validation errors:</h4>';
            echo "<ul>";
            foreach ($invalidFields as $field => $error) {
                echo "<li>" . $error . "</li>";
            }
            echo "</ul>";
            echo "</div>";
        } else {
            echo '<div class="alert alert-success" role="alert">';
            echo "<h2 class='alert-heading'>Order Confirmation</h2>";
            echo "<p>Email: $email</p>";
            echo "<p>Delivery Address: $street $streetnumber, $city, $zipcode</p>";
            echo "<p>Selected Products:</p>";
            echo "<ul>";
            foreach ($selectedProducts as $selectedProduct) {
                echo "<li>" . $selectedProduct . "</li>";
            }
            echo "</ul>";
            echo '</div>';
        }
    }
}


// Replace this if by an actual check for the form to be submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handleForm();
}

require 'form-view.php';
