<?php
// ============================================================
//  cart.php — Cart page
//  Place this in your root folder (same level as index.html)
// ============================================================
session_start();
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>My Cart — La Fleur</title>
    <link rel="icon" type="image/png" href="images/icon de site.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="style/style.css" />
    <style>
        body {
            padding-top: 90px;
        }

        .cart-img {
            width: 80px;
            height: 100px;
            object-fit: cover;
            border-radius: 6px;
        }

        .qty-btn {
            width: 30px;
            height: 30px;
            padding: 0;
            line-height: 1;
        }

        .cart-total {
            font-size: 1.4rem;
            font-weight: 700;
        }

        #cart-loading {
            display: none;
        }
    </style>
</head>

<body>

    <!-- Navbar (same as your other pages) -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-4 fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.html">
                <img src="images/icon de site.png" alt="site icon" />
                <span class="fw-lighter ms-2">La Fleur</span>
            </a>
            <div class="nav-btns ms-auto">
                <a href="cart.php" class="btn position-relative">
                    <i class="fa fa-shopping-cart"></i>
                    <span id="cart-badge"
                        class="position-absolute top-0 start-100 translate-middle badge bg-primary"
                        style="display:none">0</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Cart content -->
    <div class="container py-5">
        <h2 class="mb-4">My Cart <i class="bi bi-bag"></i></h2>

        <!-- Loading spinner -->
        <div id="cart-loading" class="text-center py-5">
            <div class="spinner-border text-primary"></div>
        </div>

        <!-- Not logged in message -->
        <div id="cart-login-msg" class="text-center py-5 d-none">
            <i class="bi bi-person-lock fs-1 text-muted"></i>
            <p class="mt-3 text-muted">Please log in to view your cart.</p>
            <a href="sign_in.html" class="btn btn-primary">Log In</a>
        </div>

        <!-- Empty cart message -->
        <div id="cart-empty" class="text-center py-5 d-none">
            <i class="bi bi-cart-x fs-1 text-muted"></i>
            <p class="mt-3 text-muted">Your cart is empty.</p>
            <a href="index.html" class="btn btn-primary">Continue Shopping</a>
        </div>

        <!-- Cart table -->
        <div id="cart-content" class="d-none">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="cart-tbody"></tbody>
                </table>
            </div>

            <!-- Total + checkout -->
            <div class="d-flex justify-content-end mt-4">
                <div class="text-end">
                    <p class="cart-total">Total: <span id="cart-total-price">0</span> TND</p>
                    <button class="btn btn-outline-danger me-2" id="btn-clear-cart">
                        <i class="bi bi-trash"></i> Clear Cart
                    </button>
                    <a href="#" class="btn btn-primary px-5">
                        Checkout <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ── Load cart on page open ──────────────────────────────
        async function loadCart() {
            document.getElementById('cart-loading').style.display = 'block';

            try {
                const res = await fetch('api/cart.php?action=get');
                const data = await res.json();

                document.getElementById('cart-loading').style.display = 'none';

                if (data.error === 'not_logged_in') {
                    document.getElementById('cart-login-msg').classList.remove('d-none');
                    return;
                }

                if (!data.items || data.items.length === 0) {
                    document.getElementById('cart-empty').classList.remove('d-none');
                    return;
                }

                // Render items
                document.getElementById('cart-content').classList.remove('d-none');
                const tbody = document.getElementById('cart-tbody');
                tbody.innerHTML = '';

                data.items.forEach(item => {
                    const subtotal = (item.price * item.quantity / 1000).toFixed(3);
                    const price = (item.price / 1000).toFixed(3);
                    tbody.innerHTML += `
            <tr id="row-${item.cart_id}">
              <td>
                <div class="d-flex align-items-center gap-3">
                  <img src="${item.image}" class="cart-img" alt="${item.title}" />
                  <div>
                    <p class="mb-0 fw-semibold">${item.title}</p>
                    ${item.author ? `<small class="text-muted">${item.author}</small>` : ''}
                  </div>
                </div>
              </td>
              <td>${price} TND</td>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <button class="btn btn-outline-secondary qty-btn"
                          onclick="changeQty(${item.cart_id}, ${item.quantity - 1})">−</button>
                  <span id="qty-${item.cart_id}">${item.quantity}</span>
                  <button class="btn btn-outline-secondary qty-btn"
                          onclick="changeQty(${item.cart_id}, ${item.quantity + 1})">+</button>
                </div>
              </td>
              <td id="sub-${item.cart_id}">${subtotal} TND</td>
              <td>
                <button class="btn btn-sm btn-outline-danger"
                        onclick="removeItem(${item.cart_id})">
                  <i class="bi bi-trash"></i>
                </button>
              </td>
            </tr>`;
                });

                document.getElementById('cart-total-price').textContent =
                    (data.total / 1000).toFixed(3);

            } catch (e) {
                document.getElementById('cart-loading').style.display = 'none';
                alert('Could not load cart. Please refresh.');
            }
        }

        // ── Change quantity ─────────────────────────────────────
        async function changeQty(cartId, newQty) {
            if (newQty < 1) {
                removeItem(cartId);
                return;
            }

            await fetch('api/cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `action=update&cart_id=${cartId}&quantity=${newQty}`
            });
            loadCart(); // reload to recalculate totals
        }

        // ── Remove item ─────────────────────────────────────────
        async function removeItem(cartId) {
            await fetch('api/cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `action=remove&cart_id=${cartId}`
            });
            loadCart();
        }

        // ── Clear entire cart ───────────────────────────────────
        document.getElementById('btn-clear-cart').addEventListener('click', async () => {
            if (!confirm('Clear your entire cart?')) return;
            await fetch('api/cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'action=clear'
            });
            loadCart();
        });

        loadCart();
    </script>
</body>

</html>