<?php
// Get the current page filename to highlight the active link in the menu
$current_page = basename($_SERVER['PHP_SELF']);
?>

<style>
    /* The Sidebar Container */
    #sidebar {
        min-width: 260px;
        max-width: 260px;
        background: #1a1d20;
        /* Deep dark professional background */
        color: #fff;
        transition: all 0.3s;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        z-index: 1000;
    }

    /* Header Section */
    #sidebar .sidebar-header {
        padding: 25px 20px;
        background: rgba(0, 0, 0, 0.2);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    #sidebar .sidebar-header h4 {
        color: #fff;
        font-size: 1.1rem;
        letter-spacing: 1px;
        margin: 0;
    }

    /* Navigation Links */
    #sidebar .nav-pills {
        padding: 20px 15px;
    }

    #sidebar .nav-link {
        color: rgba(255, 255, 255, 0.7);
        padding: 12px 15px;
        margin-bottom: 8px;
        transition: all 0.3s ease;
        border-radius: 8px;
        display: flex;
        align-items: center;
        text-decoration: none;
    }

    #sidebar .nav-link i {
        font-size: 1.2rem;
        margin-right: 12px;
        width: 25px;
        text-align: center;
    }

    #sidebar .nav-link:hover {
        color: #fff;
        background: rgba(255, 255, 255, 0.1);
        transform: translateX(5px);
    }

    /* Blue "Active" State */
    #sidebar .nav-link.active {
        background-color: #0d6efd !important;
        color: #fff !important;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);
    }

    /* Logout Section at Bottom */
    .sidebar-footer {
        margin-top: auto;
        padding: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    #sidebar .logout-link {
        color: #ff6b6b;
    }

    #sidebar .logout-link:hover {
        background: rgba(255, 107, 107, 0.1);
        color: #ff6b6b;
    }

    /* Mobile Adaptability */
    @media (max-width: 768px) {
        #sidebar {
            margin-left: -260px;
            position: fixed;
            height: 100%;
        }

        #sidebar.active {
            margin-left: 0;
        }
    }
</style>

<nav id="sidebar" class="shadow">
    <div class="sidebar-header">
        <a href="../index.php" class="text-decoration-none text-dark">
            <div class="d-flex align-items-center">
                <img src="../images/icon de site.png" alt="Logo" width="30" class="me-2">
                <h4 class="fw-bold text-uppercase mb-0">Admin Panel</h4>
            </div>
        </a>
    </div>

    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="index.php" class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="products.php" class="nav-link <?php echo ($current_page == 'products.php') ? 'active' : ''; ?>">
                <i class="bi bi-book"></i>
                <span>Products</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="manage-orders.php" class="nav-link <?php echo ($current_page == 'manage-orders.php' || $current_page == 'view-order.php') ? 'active' : ''; ?>">
                <i class="bi bi-cart3"></i>
                <span>Orders</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="manage-users.php" class="nav-link <?php echo ($current_page == 'manage-users.php') ? 'active' : ''; ?>">
                <i class="bi bi-people"></i>
                <span>Users</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <a href="../logout.php" class="nav-link logout-link px-2">
            <i class="bi bi-box-arrow-right"></i>
            <span>Logout</span>
        </a>
    </div>
</nav>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const toggleBtn = document.getElementById('sidebarToggle');
        if (toggleBtn) {
            toggleBtn.onclick = function() {
                document.getElementById('sidebar').classList.toggle('active');
            };
        }
    });
</script>