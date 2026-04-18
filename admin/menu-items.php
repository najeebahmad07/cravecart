<?php
require_once 'includes/header.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM menu_items WHERE id = $id");
    header("Location: menu-items.php");
    exit;
}

// Handle add/edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $restaurant_id = (int)$_POST['restaurant_id'];
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = (float)$_POST['price'];
    $category = $conn->real_escape_string($_POST['category']);
    $image = $_POST['existing_image'] ?? '';

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $upload_result = uploadImage($_FILES['image'], MENU_UPLOAD_PATH);
        if ($upload_result['success']) {
            $image = 'uploads/menu/' . $upload_result['filename'];
        }
    }

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = (int)$_POST['id'];
        $query = "UPDATE menu_items SET restaurant_id=$restaurant_id, name='$name', description='$description',
                  price=$price, image='$image', category='$category' WHERE id=$id";
    } else {
        $query = "INSERT INTO menu_items (restaurant_id, name, description, price, image, category)
                  VALUES ($restaurant_id, '$name', '$description', $price, '$image', '$category')";
    }

    $conn->query($query);
    header("Location: menu-items.php");
    exit;
}

// Fetch restaurants for dropdown
$restaurants = $conn->query("SELECT id, name FROM restaurants ORDER BY name");

// Fetch all menu items
$menu_items = $conn->query("SELECT mi.*, r.name as restaurant_name
                            FROM menu_items mi
                            JOIN restaurants r ON mi.restaurant_id = r.id
                            ORDER BY mi.created_at DESC");
?>

<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Menu Items Management</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#menuModal" onclick="resetForm()">
            <i class="bi bi-plus-circle"></i> Add Menu Item
        </button>
    </div>

    <div class="table-card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Restaurant</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = $menu_items->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $item['id']; ?></td>
                        <td>
                            <img src="<?php echo SITE_URL . '/' . $item['image']; ?>"
                                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                        </td>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo htmlspecialchars($item['restaurant_name']); ?></td>
                        <td><span class="badge bg-info"><?php echo htmlspecialchars($item['category']); ?></span></td>
                        <td>Rs. <?php echo number_format($item['price'], 2); ?></td>
                        <td>
                            <button class="btn btn-action btn-sm btn-primary" onclick='editMenuItem(<?php echo json_encode($item); ?>)'>
                                <i class="bi bi-pencil"></i>
                            </button>
                            <a href="?delete=<?php echo $item['id']; ?>"
                               class="btn btn-action btn-sm btn-danger"
                               onclick="return confirm('Are you sure?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Menu Item Modal -->
<div class="modal fade" id="menuModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTitle">Add Menu Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id" id="item_id">
                    <input type="hidden" name="existing_image" id="existing_image">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Restaurant</label>
                            <select class="form-select" name="restaurant_id" id="restaurant_id" required>
                                <option value="">Select Restaurant</option>
                                <?php
                                $restaurants->data_seek(0);
                                while ($r = $restaurants->fetch_assoc()): ?>
                                    <option value="<?php echo $r['id']; ?>"><?php echo htmlspecialchars($r['name']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Item Name</label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="description" rows="2" required></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price (Rs.)</label>
                            <input type="number" step="0.01" class="form-control" name="price" id="price" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category</label>
                            <input type="text" class="form-control" name="category" id="category" placeholder="e.g., Main Course" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editMenuItem(item) {
    document.getElementById('modalTitle').textContent = 'Edit Menu Item';
    document.getElementById('item_id').value = item.id;
    document.getElementById('restaurant_id').value = item.restaurant_id;
    document.getElementById('name').value = item.name;
    document.getElementById('description').value = item.description;
    document.getElementById('price').value = item.price;
    document.getElementById('category').value = item.category;
    document.getElementById('existing_image').value = item.image;
    new bootstrap.Modal(document.getElementById('menuModal')).show();
}

function resetForm() {
    document.getElementById('modalTitle').textContent = 'Add Menu Item';
    document.getElementById('item_id').value = '';
    document.getElementById('restaurant_id').value = '';
    document.getElementById('name').value = '';
    document.getElementById('description').value = '';
    document.getElementById('price').value = '';
    document.getElementById('category').value = '';
    document.getElementById('existing_image').value = '';
}
</script>

<?php require_once 'includes/footer.php'; ?>