<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XML DOM Data Retrieval</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h1>XML DOM Data Retrieval</h1>

        <table id="basketTable">
            <tr>
                <th>Basket No</th>
                <th>Basket Owner</th>
                <th>Fruit 1</th>
                <th>Fruit 2</th>
                <th>Fruit 3</th>
                <th>Fruit 4</th>
                <th>Total Fruits</th>
            </tr>
        </table>

    </div>
   
    <div class="formContainer">

    <div class="sub-formContainer">
    <h2>Add Basket</h2>
    <form method="POST">
    <label for="number">Basket Number:</label>
    <input type="number" name="number" required><br>

    <label for="owner">Basket Owner:</label>
    <input type="text" name="owner" required><br>

    <label for="apple">Apple:</label>
    <input type="number" name="apple" value="0" min="0"><br>

    <label for="banana">Banana:</label>
    <input type="number" name="banana" value="0" min="0"><br>

    <label for="orange">Orange:</label>
    <input type="number" name="orange" value="0" min="0"><br>

    <label for="grapes">Grapes:</label>
    <input type="number" name="grapes" value="0" min="0"><br>

    <button type="submit" name="add">Add Basket</button>
    <?php if (isset($addMessage)) echo "<p>$addMessage</p>"; ?>
</form>
    </div>

<!-- Update Basket Form -->
<div class="sub-formContainer">
<h2>Update Basket</h2>
<form method="POST">
    <label for="number">Basket Number:</label>
    <input type="number" name="number" required><br>

    <label for="owner">Basket Owner:</label>
    <input type="text" name="owner" required><br>

    <label for="apple">Apple:</label>
    <input type="number" name="apple" value="0" min="0"><br>

    <label for="banana">Banana:</label>
    <input type="number" name="banana" value="0" min="0"><br>

    <label for="orange">Orange:</label>
    <input type="number" name="orange" value="0" min="0"><br>

    <label for="grapes">Grapes:</label>
    <input type="number" name="grapes" value="0" min="0"><br>

    <button type="submit" name="update">Update Basket</button>
    <?php if (isset($updateMessage)) echo "<p>$updateMessage</p>"; ?>
</form>
    </div>

<div class="sub-formContainermini">
<h2>Delete Basket</h2>
<form method="POST">
    <label for="number">Basket Number:</label>
    <input type="number" name="number" required><br>

    <button type="submit" name="delete">Delete Basket</button>
    <?php if (isset($deleteMessage)) echo "<p>$deleteMessage</p>"; ?>
</form>
</div>
    
</body>
<script src="script.js"></script>



<?php
$xmlFile = 'porazo_joyce.xml';

function readXmlData() {
    global $xmlFile;

    if (file_exists($xmlFile)) {
        $xml = simplexml_load_file($xmlFile);
        return $xml;
    } else {
        return false;
    }
}

function writeXmlData($xml) {
    global $xmlFile;

    if ($xml->asXML($xmlFile)) {
        return true;
    } else {
        return false;
    }
}

// Function to add a basket
function addBasket($number, $owner, $fruits) {
    $xml = readXmlData();

    if (!$xml) {
        $xml = new SimpleXMLElement('<baskets></baskets>');
    }

    // Check if basket number already exists
    foreach ($xml->children() as $basket) {
        if ((int)$basket->number == $number) {
            return "Error: Basket number already exists.";
        }
    }

    $basket = $xml->addChild('basket');
    $basket->addChild('number', $number);
    $basket->addChild('owner', $owner);

    $fruitsElement = $basket->addChild('fruits');
    foreach ($fruits as $fruitName => $fruitCount) {
        $fruitsElement->addChild($fruitName, $fruitCount);
    }

    if (writeXmlData($xml)) {
        return "Basket added successfully.";
    } else {
        return "Error: Unable to add basket.";
    }
}

function updateBasket($number, $owner, $fruits) {
    $xml = readXmlData();

    if (!$xml) {
        return "Error: Database not found.";
    }

    $basket = $xml->xpath("//basket[number='$number']");

    if ($basket) {
        $basket[0]->owner = $owner;

        unset($basket[0]->fruits);

        $fruitsElement = $basket[0]->addChild('fruits');
        foreach ($fruits as $fruitName => $fruitCount) {
            $fruitsElement->addChild($fruitName, $fruitCount);
        }

        if (writeXmlData($xml)) {
            return "Basket updated successfully.";
        } else {
            return "Error: Unable to update basket.";
        }
    } else {
        return "Error: Basket not found.";
    }
}

// Function to delete a basket
function deleteBasket($number) {
    $xml = readXmlData();

    if (!$xml) {
        return "Error: Database not found.";
    }

    $basket = $xml->xpath("//basket[number='$number']");

    if ($basket) {
        unset($basket[0][0]);

        if (writeXmlData($xml)) {
            return "Basket deleted successfully.";
        } else {
            return "Error: Unable to delete basket.";
        }
    } else {
        return "Error: Basket not found.";
    }
}

function calculateTotalFruits($xml) {
    $totals = [];

    foreach ($xml->basket as $basket) {
        foreach ($basket->fruits->children() as $fruit) {
            $fruitName = $fruit->getName();
            $count = (int)$fruit;

            if (!isset($totals[$fruitName])) {
                $totals[$fruitName] = 0;
            }
            $totals[$fruitName] += $count;
        }
    }

    return $totals;
}

// Function to handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        // Handle add basket form
        $number = $_POST['number'];
        $owner = $_POST['owner'];
        $fruits = [
            'Apple' => (int)$_POST['apple'],
            'Banana' => (int)$_POST['banana'],
            'Orange' => (int)$_POST['orange'],
            'Grapes' => (int)$_POST['grapes']
        ];

        $addMessage = addBasket($number, $owner, $fruits);
    } elseif (isset($_POST['update'])) {
        // Handle update basket form
        $number = $_POST['number'];
        $owner = $_POST['owner'];
        $fruits = [
            'Apple' => (int)$_POST['apple'],
            'Banana' => (int)$_POST['banana'],
            'Orange' => (int)$_POST['orange'],
            'Grapes' => (int)$_POST['grapes']
        ];

        $updateMessage = updateBasket($number, $owner, $fruits);
    } elseif (isset($_POST['delete'])) {
        // Handle delete basket form
        $number = $_POST['number'];
        $deleteMessage = deleteBasket($number);
    }
}

$xml = readXmlData();
$fruitTotals = [];
if ($xml) {
    $fruitTotals = calculateTotalFruits($xml);
}
?>

</html>
