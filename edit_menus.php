<?php
session_start();
require 'core/db.php'; // Your DB connection script


// Redirect if not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Fetch menus for selection
$menusQuery = "SELECT menu_id, menu_name FROM menus ORDER BY menu_name ASC";
$menus = $conn->query($menusQuery)->fetchAll();

// Load selected menu items if a menu is selected
$menuItems = [];
if (isset($_POST['load_menu'])) {
    $selectedMenuId = $_POST['menu_id'];
    // Assuming a recursive function fetchMenuItems() that builds a nested array of menu items
    $menuItems = fetchMenuItems($selectedMenuId, $conn);
}

// Function to recursively fetch menu items and their children (assuming a 'parent_id' column for hierarchy)
function fetchMenuItems($menuId, $pdo, $parentId = null) {
    $itemsQuery = "SELECT item_id, item_title, item_url, parent_id FROM menu_items WHERE menu_id = ? AND parent_id ".($parentId ? "= $parentId" : "IS NULL")." ORDER BY `order` ASC";
    $stmt = $pdo->prepare($itemsQuery);
    $stmt->execute([$menuId]);
    $items = [];
    while ($item = $stmt->fetch()) {
        $item['children'] = fetchMenuItems($menuId, $pdo, $item['item_id']);
        $items[] = $item;
    }
    return $items;
}

// Convert the nested menu items array to a nestable list's HTML
function buildNestableList($items) {
    $html = '<ol class="dd-list">';
    foreach ($items as $item) {
        $html .= '<li class="dd-item" data-id="' . $item['item_id'] . '">';
        $html .= '<div class="dd-handle">';
        $html .= '<input type="text" name="title[' . $item['item_id'] . ']" value="' . htmlspecialchars($item['item_title']) . '" placeholder="Title" />';
        $html .= '<input type="text" name="url[' . $item['item_id'] . ']" value="' . htmlspecialchars($item['item_url']) . '" placeholder="URL" />';
        $html .= '<button type="button" class="delete-item">Delete</button>';
        $html .= '</div>';
        if (!empty($item['children'])) {
            $html .= buildNestableList($item['children']);
        }
        $html .= '</li>';
    }
    $html .= '</ol>';
    $html .= '<button type="button" class="add-item">Add Item</button>';
    return $html;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Menu Editor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.js"></script>
    <style>
        .dd { max-width: 600px; }
        .dd-handle { display: block; height: 30px; line-height: 30px; }
    </style>
</head>
<body>

<form method="post" action="">
    <label for="menu-select">Choose a menu to edit:</label>
    <select name="menu_id" id="menu-select">
        <?php foreach ($menus as $menu): ?>
            <option value="<?= $menu['menu_id']; ?>"><?= htmlspecialchars($menu['menu_name']); ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit" name="load_menu">Load Menu</button>
</form>


<?php if (!empty($menuItems)): ?>
    <div class="dd" id="nestable">
        <?= buildNestableList($menuItems); ?>
    </div>
    <button type="button" class="save-btn">Save Menu</button> <!-- Save button added -->
<?php endif; ?>

<script>
$(document).ready(function() {
    $('#nestable').nestable();

    // Delete item event
    $(document).on('click', '.delete-item', function(e) {
        e.preventDefault();
        var item_id = $(this).closest('.dd-item').data('id');
        // Optionally, send an AJAX request to delete the item from the database
        $(this).closest('.dd-item').remove();
    });
     // Add new item event
     $(document).on('click', '.add-item', function(e) {
        e.preventDefault();
        // You'll need to define how new items should be structured
        // Here's an example adding a new item at the end of the list
        var newItemHtml = '<li class="dd-item" data-id="new">' +
            '<div class="dd-handle">' +
            '<input type="text" name="title[new]" placeholder="Title" />' +
            '<input type="text" name="url[new]" placeholder="URL" />' +
            '<button type="button" class="delete-item">Delete</button>' +
            '</div>' +
            '</li>';
        $('#nestable > .dd-list').append(newItemHtml);
    });

    // Event handler for the save button
    $('.save-btn').on('click', function(e) {
        e.preventDefault();

        // Initialize an empty array to store the menu items and their details
        var items = [];
        
        // Iterate over each menu item and collect its details
        $('#nestable .dd-item').each(function() {
            var id = $(this).data('id');
            var title = $(this).find('input[name^="title"]').val();
            var url = $(this).find('input[name^="url"]').val();
            var parentId = $(this).parent().closest('.dd-item').data('id') || null;
            var order = $(this).index() + 1; // Add 1 to start the index from 1 instead of 0

            // Push the item details into the items array
            items.push({ id: id, title: title, url: url, parent_id: parentId, order: order });
        });

        // Prepare the data object to be sent to the server
        var postData = {
            menu: items
        };

        // AJAX call to the server to save the menu structure
        $.ajax({
            url: 'core/save_menu_structure.php', // Update to the correct server-side script URL
            type: 'POST',
            data: JSON.stringify(postData), // Send the postData object as a JSON string
            contentType: 'application/json', // Set the content type of the request
            success: function(response) {
                // Here you can parse the JSON response from the server if needed
                alert("Menu saved successfully!");
            },
            error: function(xhr, status, error) {
                alert("An error occurred while saving the menu: " + error);
            }
        });
    });

});
</script>


</body>
</html>
