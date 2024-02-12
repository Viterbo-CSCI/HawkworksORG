<?php
// Include your database connection file
require_once 'core/db.php';

// Initialize an array to hold the main menu and its items
$mainMenu = [];

// Fetch all menu items for the main menu
$itemsQuery = "SELECT * FROM menu_items WHERE menu_id = 1 ORDER BY `order`";
$itemsResult = $conn->query($itemsQuery);

// Build a nested array of menu items
$menuItems = [];
while ($item = $itemsResult->fetch(PDO::FETCH_ASSOC)) {
    $menuItems[$item['item_id']] = $item;
    $menuItems[$item['item_id']]['children'] = [];
}

// Assign children to their parents
foreach ($menuItems as $item) {
    if ($item['parent_id'] !== null && isset($menuItems[$item['parent_id']])) {
        $menuItems[$item['parent_id']]['children'][] = &$menuItems[$item['item_id']];
    }
}

// Remove children from the top level
foreach ($menuItems as $key => $item) {
    if ($item['parent_id'] !== null) {
        unset($menuItems[$key]);
    }
}

/// Function to build the HTML for the menu
function buildMenuHtml($items) {
    $html = '';
    foreach ($items as $item) {
        $hasChildren = !empty($item['children']);
        $html .= '<li class="nav-item' . ($hasChildren ? ' dropdown' : '') . '">';
        if ($hasChildren) {
            $html .= '<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink' . $item['item_id'] . '" role="button" data-bs-toggle="dropdown" aria-expanded="false">';
        } else {
            $html .= '<a class="nav-link" href="' . htmlspecialchars($item['item_url']) . '">';
        }
        $html .= htmlspecialchars($item['item_title']) . '</a>';
        if ($hasChildren) {
            $html .= '<ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink' . $item['item_id'] . '">';
            $html .= buildMenuHtml($item['children']);
            $html .= '</ul>';
        }
        $html .= '</li>';
    }
    return $html;
}

// Start building the menu HTML
$menuHtml = buildMenuHtml($menuItems);
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container navbar-container">
        <a class="navbar-brand" href="#">
            <img src="/imgs/logo2.png" alt="Logo" style="height: 40px;"> <!-- Replace with your actual logo path -->
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto">
                <?php echo $menuHtml; ?>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
