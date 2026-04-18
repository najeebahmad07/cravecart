<?php
require_once 'includes/header.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM users WHERE id = $id");
    header("Location: users.php");
    exit;
}

// Fetch all users with order count
$users = $conn->query("SELECT u.*,
                       (SELECT COUNT(*) FROM orders WHERE user_id = u.id) as order_count,
                       (SELECT SUM(total_amount) FROM orders WHERE user_id = u.id) as total_spent
                       FROM users u
                       ORDER BY u.created_at DESC");
?>

<div class="admin-content">
    <h2 class="fw-bold mb-4">Users Management</h2>

    <div class="table-card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Orders</th>
                        <th>Total Spent</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $users->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td class="fw-bold"><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['phone']); ?></td>
                        <td><?php echo htmlspecialchars(substr($user['address'], 0, 30)); ?>...</td>
                        <td>
                            <span class="badge bg-primary"><?php echo $user['order_count']; ?> orders</span>
                        </td>
                        <td class="fw-bold">Rs. <?php echo number_format($user['total_spent'] ?? 0, 2); ?></td>
                        <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                        <td>
                            <button class="btn btn-action btn-sm btn-info" onclick="viewUserDetails(<?php echo htmlspecialchars(json_encode($user)); ?>)">
                                <i class="bi bi-eye"></i>
                            </button>
                            <a href="?delete=<?php echo $user['id']; ?>"
                               class="btn btn-action btn-sm btn-danger"
                               onclick="return confirm('Are you sure you want to delete this user?')">
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

<!-- User Details Modal -->
<div class="modal fade" id="userDetailsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="userDetailsContent">
            </div>
        </div>
    </div>
</div>

<script>
function viewUserDetails(user) {
    const content = `
        <div class="user-details">
            <div class="mb-3">
                <label class="text-muted small">Full Name</label>
                <p class="fw-bold">${user.name}</p>
            </div>
            <div class="mb-3">
                <label class="text-muted small">Email Address</label>
                <p class="fw-bold">${user.email}</p>
            </div>
            <div class="mb-3">
                <label class="text-muted small">Phone Number</label>
                <p class="fw-bold">${user.phone}</p>
            </div>
            <div class="mb-3">
                <label class="text-muted small">Delivery Address</label>
                <p class="fw-bold">${user.address}</p>
            </div>
            <div class="mb-3">
                <label class="text-muted small">Member Since</label>
                <p class="fw-bold">${new Date(user.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
            </div>
            <div class="row">
                <div class="col-6">
                    <label class="text-muted small">Total Orders</label>
                    <p class="fw-bold text-primary">${user.order_count}</p>
                </div>
                <div class="col-6">
                    <label class="text-muted small">Total Spent</label>
                    <p class="fw-bold text-success">Rs. ${parseFloat(user.total_spent || 0).toFixed(2)}</p>
                </div>
            </div>
        </div>
    `;

    document.getElementById('userDetailsContent').innerHTML = content;
    new bootstrap.Modal(document.getElementById('userDetailsModal')).show();
}
</script>

<?php require_once 'includes/footer.php'; ?>