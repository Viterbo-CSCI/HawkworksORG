<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'core/db.php'; // Your DB connection script

header('Content-Type: application/json'); // Specify the correct content type for JSON response

// Check if the user is logged in and has the permission to edit menus
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo json_encode(["status" => "error", "message" => "User not authenticated"]);
    exit;
}

// Ensure that $selectedMenuId is set correctly
// For example, it might be set when the user selects which menu to edit
// $selectedMenuId = $_SESSION['selectedMenuId']; // or another source

// Check if the menu data is received
$input = file_get_contents('php://input');
$data = json_decode($input, true); // Decode the JSON string from the raw input

if (!empty($data['menu'])) {
    $menuItems = $data['menu'];

    foreach ($menuItems as $item) {
        // Check if we need to update or insert a new item
        if ($item['id'] === "new") {
            // Insert new menu item
            $sql = "INSERT INTO menu_items (item_title, item_url, parent_id, `order`, menu_id) VALUES (?, ?, ?, ?, ?)";
            $parentId = $item['parent_id'] !== "null" ? $item['parent_id'] : null;
            $values = [$item['title'], $item['url'], $parentId, $item['order'], $selectedMenuId];
        } else {
            // Update existing menu item
            $sql = "UPDATE menu_items SET item_title = ?, item_url = ?, parent_id = ?, `order` = ? WHERE item_id = ?";
            $parentId = $item['parent_id'] !== "null" ? $item['parent_id'] : null;
            $values = [$item['title'], $item['url'], $parentId, $item['order'], $item['id']];
        }
        $stmt = $conn->prepare($sql);

        // Execute the query
        if (!$stmt->execute($values)) {
            // If there's an error, output it and stop the script
            echo json_encode(["status" => "error", "message" => "Database error: " . implode("; ", $stmt->errorInfo())]);
            exit;
        }
    }

    // If all updates/inserts were successful
    echo json_encode(["status" => "success", "message" => "Menu updated successfully"]);
} else {
    // If no data received
    echo json_encode(["status" => "error", "message" => "No menu data received"]);
}
?>
