<?php
require_once 'includes/header.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM restaurants WHERE id = $id");
    header("Location: restaurants.php");
    exit;
}

// Handle add/edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $address = $conn->real_escape_string($_POST['address']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $rating = (float)$_POST['rating'];
    $delivery_time = $conn->real_escape_string($_POST['delivery_time']);
    $image = $_POST['existing_image'] ?? '';

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $upload_result = uploadImage($_FILES['image'], RESTAURANT_UPLOAD_PATH);
        if ($upload_result['success']) {
            $image = 'uploads/restaurants/' . $upload_result['filename'];
        }
    }

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update
        $id = (int)$_POST['id'];
        $query = "UPDATE restaurants SET name='$name', description='$description', image='$image',
                  address='$address', phone='$phone', rating='$rating', delivery_time='$delivery_time'
                  WHERE id=$id";
    } else {
        // Insert
        $query = "INSERT INTO restaurants (name, description, image, address, phone, rating, delivery_time)
                  VALUES ('$name', '$description', '$image', '$address', '$phone', '$rating', '$delivery_time')";
    }

    $conn->query($query);
    header("Location: restaurants.php");
    exit;
}

// Get restaurant for editing
$edit_restaurant = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $edit_restaurant = $conn->query("SELECT * FROM restaurants WHERE id = $id")->fetch_assoc();
}

// Fetch all restaurants
$restaurants = $conn->query("SELECT * FROM restaurants ORDER BY created_at DESC");
?>

<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Restaurants Management</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#restaurantModal" onclick="resetForm()">
            <i class="bi bi-plus-circle"></i> Add Restaurant
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
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Rating</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($restaurant = $restaurants->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $restaurant['id']; ?></td>
                        <td>
                            <img src="<?php echo SITE_URL . '/' . $restaurant['image']; ?>"
                                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                        </td>
                        <td><?php echo htmlspecialchars($restaurant['name']); ?></td>
                        <td><?php echo htmlspecialchars(substr($restaurant['address'], 0, 30)); ?>...</td>
                        <td><?php echo htmlspecialchars($restaurant['phone']); ?></td>
                        <td><span class="badge bg-warning"><?php echo $restaurant['rating']; ?> ★</span></td>
                        <td>
                            <button class="btn btn-action btn-sm btn-primary" onclick="editRestaurant(<?php echo htmlspecialchars(json_encode($restaurant)); ?>)">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <a href="?delete=<?php echo $restaurant['id']; ?>"
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

<!-- Restaurant Modal -->
<div class="modal fade" id="restaurantModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTitle">Add Restaurant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id" id="restaurant_id">
                    <input type="hidden" name="existing_image" id="existing_image">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Restaurant Name</label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" id="phone" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="description" rows="2" required></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" id="address" rows="2" required></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Rating</label>
                            <input type="number" step="0.1" min="0" max="5" class="form-control" name="rating" id="rating" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Delivery Time</label>
                            <input type="text" class="form-control" name="delivery_time" id="delivery_time" placeholder="30-40 min" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Restaurant</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editRestaurant(restaurant) {
    document.getElementById('modalTitle').textContent = 'Edit Restaurant';
    document.getElementById('restaurant_id').value = restaurant.id;
    document.getElementById('name').value = restaurant.name;
    document.getElementById('description').value = restaurant.description;
    document.getElementById('address').value = restaurant.address;
    document.getElementById('phone').value = restaurant.phone;
    document.getElementById('rating').value = restaurant.rating;
    document.getElementById('delivery_time').value = restaurant.delivery_time;
    document.getElementById('existing_image').value = restaurant.image;
    new bootstrap.Modal(document.getElementById('restaurantModal')).show();
}

function resetForm() {
    document.getElementById('modalTitle').textContent = 'Add Restaurant';
    document.getElementById('restaurant_id').value = '';
    document.getElementById('name').value = '';
    document.getElementById('description').value = '';
    document.getElementById('address').value = '';
    document.getElementById('phone').value = '';
    document.getElementById('rating').value = '';
    document.getElementById('delivery_time').value = '';
    document.getElementById('existing_image').value = '';
}
</script>

<?php require_once 'includes/footer.php'; ?>