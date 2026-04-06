<?php
$activePage = 'inventory';
require_once 'includes/cart.php';
require_once 'includes/products.php';
require_once 'includes/repository.php';
require_once 'includes/validate.php';

$dbReady = thriftDbConfigured();
$dbError = '';
$flash = $_SESSION['inventory_flash'] ?? null;
unset($_SESSION['inventory_flash']);

$baseCategories = ['clothing', 'accessories', 'shoes'];
$errors = [];
$editingId = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;
$editingProduct = null;

$formData = [
    'name' => '',
    'description' => '',
    'image' => '',
    'image_alt' => '',
    'buy_price' => '',
    'bid_price' => '',
    'category' => 'clothing',
    'is_active' => '1',
];

if ($dbReady && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inventory_action'])) {
    $action = $_POST['inventory_action'];

    if ($action === 'delete') {
        $id = (int)($_POST['product_id'] ?? 0);

        try {
            deleteProductRecord($id);
            $_SESSION['inventory_flash'] = ['type' => 'success', 'message' => 'Product deleted from the database.'];
        } catch (Throwable $e) {
            $_SESSION['inventory_flash'] = ['type' => 'error', 'message' => 'Could not delete that product.'];
        }

        header('Location: admin-products.php');
        exit;
    }

    $formData = [
        'name' => trim($_POST['name'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'image' => trim($_POST['image'] ?? ''),
        'image_alt' => trim($_POST['image_alt'] ?? ''),
        'buy_price' => trim($_POST['buy_price'] ?? ''),
        'bid_price' => trim($_POST['bid_price'] ?? ''),
        'category' => trim($_POST['category'] ?? ''),
        'is_active' => isset($_POST['is_active']) ? '1' : '0',
    ];

    if (!validateText($formData['name'], 3, 120)) {
        $errors['name'] = 'Name must be between 3 and 120 characters.';
    }
    if (!validateText($formData['description'], 10, 2000)) {
        $errors['description'] = 'Description must be between 10 and 2000 characters.';
    }
    if (!validateText($formData['image'], 5, 255)) {
        $errors['image'] = 'Image path is required.';
    }
    if (!validateText($formData['image_alt'], 5, 255)) {
        $errors['image_alt'] = 'Image alt text is required.';
    }
    if (!validateNumber($formData['buy_price'], 0.01, 99999)) {
        $errors['buy_price'] = 'Buy price must be a valid positive number.';
    }
    if (!validateNumber($formData['bid_price'], 0.01, 99999)) {
        $errors['bid_price'] = 'Bid price must be a valid positive number.';
    }
    if ((float)$formData['bid_price'] > (float)$formData['buy_price']) {
        $errors['bid_price'] = 'Bid price should not be higher than the buy price.';
    }
    if (!validateText($formData['category'], 3, 50)) {
        $errors['category'] = 'Category is required.';
    }

    if (empty($errors)) {
        $payload = [
            'name' => $formData['name'],
            'description' => $formData['description'],
            'image' => $formData['image'],
            'image_alt' => $formData['image_alt'],
            'buy_price' => (float)$formData['buy_price'],
            'bid_price' => (float)$formData['bid_price'],
            'category' => strtolower($formData['category']),
            'is_active' => (int)$formData['is_active'],
        ];

        try {
            if ($action === 'add') {
                createProduct($payload);
                $_SESSION['inventory_flash'] = ['type' => 'success', 'message' => 'Product added to the database.'];
                header('Location: admin-products.php');
                exit;
            }

            if ($action === 'update') {
                $id = (int)($_POST['product_id'] ?? 0);
                updateProductRecord($id, $payload);
                $_SESSION['inventory_flash'] = ['type' => 'success', 'message' => 'Product updated in the database.'];
                header('Location: admin-products.php?edit=' . $id);
                exit;
            }
        } catch (Throwable $e) {
            $flash = ['type' => 'error', 'message' => 'Database write failed. Check the credentials in includes/database-connection.php.'];
        }
    } else {
        $flash = ['type' => 'error', 'message' => 'Fix the validation errors and try again.'];
        $editingId = (int)($_POST['product_id'] ?? 0);
    }
}

if ($dbReady) {
    try {
        if ($editingId > 0) {
            $editingProduct = fetchProductById($editingId);

            if ($editingProduct && empty($errors) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
                $formData = [
                    'name' => $editingProduct['name'],
                    'description' => $editingProduct['description'],
                    'image' => $editingProduct['image'],
                    'image_alt' => $editingProduct['image_alt'],
                    'buy_price' => (string)$editingProduct['buy_price'],
                    'bid_price' => (string)$editingProduct['bid_price'],
                    'category' => $editingProduct['category'],
                    'is_active' => (string)$editingProduct['is_active'],
                ];
            }
        }

        $products = fetchAllProducts(true);
        $categories = array_values(array_unique(array_merge($baseCategories, fetchProductCategories(true))));
        sort($categories);
    } catch (Throwable $e) {
        $dbReady = false;
        $dbError = $e->getMessage();
        $products = [];
        $categories = $baseCategories;
    }
} else {
    $products = [];
    $categories = $baseCategories;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Manager — 401 Thrift</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="content">
        <h1>Inventory Manager</h1>
        <p>
            This page performs the required database modifications for your project.
            Add products with `INSERT`, edit them with `UPDATE`, and remove them with `DELETE`.
        </p>

        <?php if ($flash): ?>
            <p class="form-status <?= htmlspecialchars($flash['type']) ?>"><?= htmlspecialchars($flash['message']) ?></p>
        <?php endif; ?>

        <?php if (!$dbReady): ?>
            <div class="db-setup-card">
                <h2>Database Setup Required</h2>
                <p>Before this page can write to MySQL:</p>
                <p>1. Import `db/schema.sql` into your MySQL database.</p>
                <p>2. Update `includes/database-connection.php` with your database host, name, username, and password.</p>
                <?php if ($dbError !== ''): ?>
                    <p class="form-status error"><?= htmlspecialchars($dbError) ?></p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="inventory-layout">
                <section class="inventory-form-panel">
                    <h2><?= $editingProduct ? 'Edit Product' : 'Add Product' ?></h2>
                    <form method="POST" action="admin-products.php<?= $editingProduct ? '?edit=' . (int)$editingProduct['id'] : '' ?>">
                        <input type="hidden" name="inventory_action" value="<?= $editingProduct ? 'update' : 'add' ?>">
                        <?php if ($editingProduct): ?>
                            <input type="hidden" name="product_id" value="<?= (int)$editingProduct['id'] ?>">
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="name">Product Name *</label>
                            <input type="text" id="name" name="name" value="<?= htmlspecialchars($formData['name']) ?>" required>
                            <?php if (!empty($errors['name'])): ?><span class="field-error"><?= htmlspecialchars($errors['name']) ?></span><?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="description">Description *</label>
                            <textarea id="description" name="description" rows="5" required><?= htmlspecialchars($formData['description']) ?></textarea>
                            <?php if (!empty($errors['description'])): ?><span class="field-error"><?= htmlspecialchars($errors['description']) ?></span><?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="image">Image Path *</label>
                            <input type="text" id="image" name="image" value="<?= htmlspecialchars($formData['image']) ?>" required>
                            <?php if (!empty($errors['image'])): ?><span class="field-error"><?= htmlspecialchars($errors['image']) ?></span><?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="image_alt">Image Alt Text *</label>
                            <input type="text" id="image_alt" name="image_alt" value="<?= htmlspecialchars($formData['image_alt']) ?>" required>
                            <?php if (!empty($errors['image_alt'])): ?><span class="field-error"><?= htmlspecialchars($errors['image_alt']) ?></span><?php endif; ?>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="buy_price">Buy Price *</label>
                                <input type="number" step="0.01" min="0.01" id="buy_price" name="buy_price" value="<?= htmlspecialchars($formData['buy_price']) ?>" required>
                                <?php if (!empty($errors['buy_price'])): ?><span class="field-error"><?= htmlspecialchars($errors['buy_price']) ?></span><?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="bid_price">Bid Price *</label>
                                <input type="number" step="0.01" min="0.01" id="bid_price" name="bid_price" value="<?= htmlspecialchars($formData['bid_price']) ?>" required>
                                <?php if (!empty($errors['bid_price'])): ?><span class="field-error"><?= htmlspecialchars($errors['bid_price']) ?></span><?php endif; ?>
                            </div>
                        </div>

                        <div class="form-row inventory-form-row">
                            <div class="form-group">
                                <label for="category">Category *</label>
                                <input list="category-options" id="category" name="category" value="<?= htmlspecialchars($formData['category']) ?>" required>
                                <datalist id="category-options">
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= htmlspecialchars($category) ?>">
                                    <?php endforeach; ?>
                                </datalist>
                                <?php if (!empty($errors['category'])): ?><span class="field-error"><?= htmlspecialchars($errors['category']) ?></span><?php endif; ?>
                            </div>
                            <div class="form-group form-checkbox">
                                <label>
                                    <input type="checkbox" name="is_active" value="1" <?= $formData['is_active'] === '1' ? 'checked' : '' ?>>
                                    Product is visible in the shop
                                </label>
                            </div>
                        </div>

                        <div class="inventory-form-actions">
                            <button type="submit" class="submit-btn"><?= $editingProduct ? 'Update Product' : 'Add Product' ?></button>
                            <?php if ($editingProduct): ?>
                                <a href="admin-products.php" class="filter-btn">Create New Product</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </section>

                <section class="inventory-table-panel">
                    <h2>Current Products</h2>
                    <div class="inventory-table-wrap">
                        <table class="inventory-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Buy</th>
                                    <th>Bid</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><?= (int)$product['id'] ?></td>
                                        <td><?= htmlspecialchars($product['name']) ?></td>
                                        <td><?= htmlspecialchars(ucfirst($product['category'])) ?></td>
                                        <td>$<?= number_format((float)$product['buy_price'], 2) ?></td>
                                        <td>$<?= number_format((float)$product['bid_price'], 2) ?></td>
                                        <td><?= (int)$product['is_active'] === 1 ? 'Active' : 'Hidden' ?></td>
                                        <td class="inventory-actions">
                                            <a href="admin-products.php?edit=<?= (int)$product['id'] ?>" class="filter-btn">Edit</a>
                                            <form method="POST" action="admin-products.php" onsubmit="return confirm('Delete this product from the database?');">
                                                <input type="hidden" name="inventory_action" value="delete">
                                                <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
                                                <button type="submit" class="clear-btn">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        <?php endif; ?>
    </div>

    <footer class="footer">
        <p>&copy; <?= date('Y') ?> 401 Thrift &mdash; Sustainable fashion, one find at a time.</p>
    </footer>
</body>
</html>
