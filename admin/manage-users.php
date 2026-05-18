<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../api/db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Users - La Fleur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .table thead {
            background-color: #f8f9fa;
        }

        .badge {
            font-weight: 500;
            padding: 0.5em 0.8em;
        }

        .modal-content {
            border-radius: 12px;
            border: none;
        }
    </style>
</head>

<body class="bg-light">

    <div class="d-flex">
        <?php include 'sidebar.php'; ?>

        <main class="flex-grow-1 p-4" style="min-height: 100vh;">

            <button id="sidebarToggle" class="btn btn-dark d-md-none mb-3">
                <i class="bi bi-list"></i>
            </button>

            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold mb-1">User Management</h2>
                        <p class="text-muted">Manage administrator accounts and customer profiles.</p>
                    </div>
                    <button class="btn btn-primary shadow-sm rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="bi bi-person-plus-fill me-2"></i>Add New Admin
                    </button>
                </div>

                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Joined Date</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
                                while ($row = $stmt->fetch()) {
                                    $is_admin = ($row['role'] == 'admin');
                                    $roleBadge = $is_admin ? 'bg-danger-subtle text-danger border border-danger-subtle' : 'bg-info-subtle text-info border border-info-subtle';
                                    $fullName = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
                                ?>
                                    <tr>
                                        <td class="ps-4 text-muted fw-bold">#<?php echo $row['id']; ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width: 38px; height: 38px;">
                                                    <i class="bi bi-person text-secondary"></i>
                                                </div>
                                                <span class="fw-semibold"><?php echo $fullName; ?></span>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td>
                                            <span class="badge rounded-pill <?php echo $roleBadge; ?>">
                                                <?php echo ucfirst($row['role']); ?>
                                            </span>
                                        </td>
                                        <td class="text-muted small">
                                            <?php echo date('d M Y', strtotime($row['created_at'])); ?>
                                        </td>
                                        <td class="text-end pe-4">
                                            <button class="btn btn-sm btn-light border"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editUserModal<?php echo $row['id']; ?>">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <a href="delete-user.php?id=<?php echo $row['id']; ?>"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Delete this user? This action cannot be undone.')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="editUserModal<?php echo $row['id']; ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content shadow-lg">
                                                <div class="modal-header border-0 pb-0">
                                                    <h5 class="fw-bold">Edit Profile</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="process-edit-user.php" method="POST">
                                                    <div class="modal-body p-4">
                                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                        <div class="row mb-3">
                                                            <div class="col">
                                                                <label class="form-label small fw-bold">First Name</label>
                                                                <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($row['first_name']); ?>" required>
                                                            </div>
                                                            <div class="col">
                                                                <label class="form-label small fw-bold">Last Name</label>
                                                                <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($row['last_name']); ?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-bold">Email Address</label>
                                                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($row['email']); ?>" required>
                                                        </div>
                                                        <div class="mb-0">
                                                            <label class="form-label small fw-bold">Account Role</label>
                                                            <select name="role" class="form-select">
                                                                <option value="user" <?php echo ($row['role'] == 'user') ? 'selected' : ''; ?>>Customer</option>
                                                                <option value="admin" <?php echo ($row['role'] == 'admin') ? 'selected' : ''; ?>>Administrator</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 pt-0">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold">New Administrator Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="process-add-user.php" method="POST">
                    <div class="modal-body p-4">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">First Name</label>
                                <input type="text" name="first_name" class="form-control" placeholder="John" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Last Name</label>
                                <input type="text" name="last_name" class="form-control" placeholder="Doe" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="admin@lafleur.tn" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label small fw-bold">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>
                        <input type="hidden" name="role" value="admin">
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4">Create Admin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>