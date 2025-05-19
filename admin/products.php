<?php
    require_once __DIR__ . '/../includes/config.php';
    require_once __DIR__ . '/../includes/db.php';
    require_once __DIR__ . '/../templates/header.php';

// Handle form submissions via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
    // Check CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
        exit;
    }

    $response = ['success' => false, 'message' => ''];

    // Add new product
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        try {
            // Validate input
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
            $stock = filter_var($_POST['stock'], FILTER_VALIDATE_INT);

            if (empty($name)) {
                throw new Exception('Product name is required');
            }
            if ($price === false || $price < 0) {
                throw new Exception('Price must be a valid number');
            }
            if ($stock === false || $stock < 0) {
                throw new Exception('Stock must be a valid number');
            }

            // Insert product
            $stmt = $pdo->prepare("INSERT INTO products (name, description, price, stock) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $description, $price, $stock]);

            $response['success'] = true;
            $response['message'] = 'Product added successfully';
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }
    }

    // Update product
    else if (isset($_POST['action']) && $_POST['action'] === 'edit') {
        try {
            // Validate input
            $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
            $stock = filter_var($_POST['stock'], FILTER_VALIDATE_INT);

            if ($id === false) {
                throw new Exception('Invalid product ID');
            }
            if (empty($name)) {
                throw new Exception('Product name is required');
            }
            if ($price === false || $price < 0) {
                throw new Exception('Price must be a valid number');
            }
            if ($stock === false || $stock < 0) {
                throw new Exception('Stock must be a valid number');
            }

            // Update product
            $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ? WHERE id = ?");
            $stmt->execute([$name, $description, $price, $stock, $id]);

            $response['success'] = true;
            $response['message'] = 'Product updated successfully';
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }
    }

    // Delete product
    else if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        try {
            $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
            
            if ($id === false) {
                throw new Exception('Invalid product ID');
            }

            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$id]);

            $response['success'] = true;
            $response['message'] = 'Product deleted successfully';
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Get products for display
$search = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

// Prepare query to fetch products with search and pagination
$query = "SELECT * FROM products";
$countQuery = "SELECT COUNT(*) FROM products";
$params = [];

// Add search condition if search parameter is provided
if (!empty($search)) {
    $query .= " WHERE name LIKE ? OR id LIKE ?";
    $countQuery .= " WHERE name LIKE ? OR id LIKE ?";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$query .= " ORDER BY id DESC LIMIT $offset, $perPage";

// Get total products (for pagination)
$stmt = $pdo->prepare($countQuery);
$stmt->execute($params);
$totalProducts = $stmt->fetchColumn();
$totalPages = ceil($totalProducts / $perPage);

// Get products for current page
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-2 col-md-3 bg-dark sidebar py-3">
                <div class="d-flex flex-column">
                    <h1 class="text-white fs-4 mb-4">Admin Panel</h1>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="#" class="nav-link active">
                                <i class="bi bi-box me-2"></i> Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-white">
                                <i class="bi bi-people me-2"></i> Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-white">
                                <i class="bi bi-cart me-2"></i> Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-white">
                                <i class="bi bi-gear me-2"></i> Settings
                            </a>
                        </li>
                        // back to Admin
                        <div class="row">
                            <div class="col-12 text-center mt-4">
                                <a href="<?php echo SITE_URL; ?>/admin/index.php" class="btn btn-secondary">Back to Dashboard</a>
                        </div>
                    </ul>
                </div>
            </div>
            
            <!-- Main content -->
            <div class="col-lg-10 col-md-9 ms-auto px-4 main-content">
                <h2 class="mt-4 mb-4">Products Management</h2>
                
                <!-- Alert container for notifications -->
                <div id="alertContainer"></div>
                
                <!-- Search and Add Product row -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <form method="get" action="" class="search-form">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" id="searchInput" placeholder="Search by name or ID" value="<?php echo htmlspecialchars($search); ?>">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-search"></i> Search
                                </button>
                                <?php if (!empty($search)): ?>
                                <a href="products.php" class="btn btn-outline-secondary">Clear</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 text-md-end mt-3 mt-md-0">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal">
                            <i class="bi bi-plus-circle"></i> Add Product
                        </button>
                    </div>
                </div>
                
                <!-- Products table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($products) > 0): ?>
                                <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($product['id']); ?></td>
                                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                                    <td class="description-cell"><?php echo htmlspecialchars($product['description']); ?></td>
                                    <td>$<?php echo number_format($product['price'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($product['stock']); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary edit-product" 
                                                data-id="<?php echo $product['id']; ?>"
                                                data-name="<?php echo htmlspecialchars($product['name']); ?>"
                                                data-description="<?php echo htmlspecialchars($product['description']); ?>"
                                                data-price="<?php echo $product['price']; ?>"
                                                data-stock="<?php echo $product['stock']; ?>">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-product" data-id="<?php echo $product['id']; ?>" data-name="<?php echo htmlspecialchars($product['name']); ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No products found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        
                        <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                <?php endif; ?>
                
                <!-- Add Product Modal -->
                <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="addProductForm">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                    <input type="hidden" name="action" value="add">
                                    
                                    <div class="mb-3">
                                        <label for="addName" class="form-label">Product Name</label>
                                        <input type="text" class="form-control" id="addName" name="name" required>
                                        <div class="invalid-feedback">Please enter a product name.</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="addDescription" class="form-label">Description</label>
                                        <textarea class="form-control" id="addDescription" name="description" rows="3"></textarea>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="addPrice" class="form-label">Price</label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" class="form-control" id="addPrice" name="price" step="0.01" min="0" required>
                                                <div class="invalid-feedback">Please enter a valid price.</div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="addStock" class="form-label">Stock</label>
                                            <input type="number" class="form-control" id="addStock" name="stock" min="0" required>
                                            <div class="invalid-feedback">Please enter a valid stock quantity.</div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-success" id="saveNewProduct">Save Product</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Edit Product Modal -->
                <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="editProductForm">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                    <input type="hidden" name="action" value="edit">
                                    <input type="hidden" id="editId" name="id">
                                    
                                    <div class="mb-3">
                                        <label for="editName" class="form-label">Product Name</label>
                                        <input type="text" class="form-control" id="editName" name="name" required>
                                        <div class="invalid-feedback">Please enter a product name.</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="editDescription" class="form-label">Description</label>
                                        <textarea class="form-control" id="editDescription" name="description" rows="3"></textarea>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="editPrice" class="form-label">Price</label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" class="form-control" id="editPrice" name="price" step="0.01" min="0" required>
                                                <div class="invalid-feedback">Please enter a valid price.</div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="editStock" class="form-label">Stock</label>
                                            <input type="number" class="form-control" id="editStock" name="stock" min="0" required>
                                            <div class="invalid-feedback">Please enter a valid stock quantity.</div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" id="updateProduct">Save Changes</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteProductModalLabel">Confirm Delete</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete the product "<span id="deleteProductName"></span>"?</p>
                                <p class="text-danger"><strong>This action cannot be undone.</strong></p>
                                <form id="deleteProductForm">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" id="deleteId" name="id">
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Custom JS -->
    <script src="products.js"></script>
</body>
</html>