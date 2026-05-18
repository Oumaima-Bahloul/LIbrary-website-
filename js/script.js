console.log("JS LOADED");

// init Isotope on the collection list
var $grid = $(".collection-list").isotope({
  itemSelector: ".col-md-6, .col-lg-4, .col-xl-3", // Select the child elements to be arranged by Isotope (your columns) or whatever your column class is
  layoutMode: "fitRows", // Arrange items in rows, filling each row before starting a new one
  filter: ".bks", // Show only items with the class "bks" (books) on page load
  // options
});
// filter items on button click
$(".filter-button-group").on("click", "button", function () {
  var filterValue = $(this).attr("data-filter"); // Get the filter value from the button's data-filter attribute
  resetFilterBtns(); // Reset all filter buttons to inactive
  $(this).addClass("active-filter-btn"); // Highlight the clicked button as active
  $grid.isotope({ filter: filterValue }); // Apply the filter to the Isotope grid
});

// Select all buttons in the filter button group for later use
var filterBtns = $(".filter-button-group").find("button");

// Function to remove the active class from all filter buttons
function resetFilterBtns() {
  filterBtns.each(function () {
    // Loop through each button
    $(this).removeClass("active-filter-btn"); // Remove the "active" styling
  });
}

// i used isotrope in this case because i wanted to
// arrange things on my page nicely and "filter" through them.
//i signed different buttons in my sale section to take me to the
// different categories : each button in the sale section corresponds to a category, so clicking it
// tells isotope to only show the items matching that filter button
//i also have the reset button because once i reload my page all the products start
// showing by default in the first section and i dont want that
// so tthe reset is to make sure each section stays neatly organised
// and no interference happens and that each button clicked gets highlighted and it's the only active one
// This function updates the text and image inside the modal
// Function to update the modal content dynamically

// This listens for any click on a button with the class "preview-btn"
document.addEventListener("DOMContentLoaded", () => {
  const buttons = document.querySelectorAll(".preview-btn");

  buttons.forEach((button) => {
    button.addEventListener("click", () => {
      const title = button.getAttribute("data-title");
      const author = button.getAttribute("data-author");
      const img = button.getAttribute("data-img");
      const price = button.getAttribute("data-price");

      document.getElementById("modalTitle").textContent = title;
      document.getElementById("modalAuthor").textContent = author;
      document.getElementById("modalImg").src = img;
      document.getElementById("modalPrice").textContent = price;
    });
  });
});
// ── On page load: update cart badge ──────────────────────
document.addEventListener("DOMContentLoaded", () => {
  updateCartBadge();

  // Add to Cart buttons
  document.querySelectorAll(".add-to-cart").forEach((btn) => {
    btn.addEventListener("click", async () => {
      const productId = btn.dataset.id;
      if (!productId) return;

      btn.disabled = true;
      const original = btn.innerHTML;
      btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Adding...';

      try {
        const res = await fetch("api/cart.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: `action=add&product_id=${productId}&quantity=1`,
        });

        const data = await res.json();

        if (data.success) {
          btn.innerHTML = '<i class="bi bi-check-lg"></i> Added!';
          btn.style.backgroundColor = "#28a745";
          btn.style.color = "#fff";
          btn.style.borderColor = "#28a745";
          setCartBadge(data.count);

          setTimeout(() => {
            btn.innerHTML = original;
            btn.style.backgroundColor = "";
            btn.style.color = "";
            btn.style.borderColor = "";
            btn.disabled = false;
          }, 2000);
        } else if (data.error === "not_logged_in") {
          btn.innerHTML = original;
          btn.disabled = false;
          showToast("Please log in to add items to your cart.", "warning");
        } else if (data.error === "out_of_stock") {
          btn.innerHTML = '<i class="bi bi-x-circle"></i> Out of stock';
          btn.style.backgroundColor = "#dc3545";
          btn.style.color = "#fff";
          setTimeout(() => {
            btn.innerHTML = original;
            btn.style.backgroundColor = "";
            btn.style.color = "";
            btn.disabled = false;
          }, 2000);
        } else {
          btn.innerHTML = original;
          btn.disabled = false;
          showToast(data.message || "Something went wrong.", "danger");
        }
      } catch (err) {
        console.error("Cart error:", err);
        btn.innerHTML = original;
        btn.disabled = false;
        showToast("Network error. Please try again.", "danger");
      }
    });
  });
});

// ── Cart badge helpers ──────────────────────────────────
async function updateCartBadge() {
  try {
    const res = await fetch("api/cart.php?action=count");
    const data = await res.json();
    setCartBadge(data.count);
  } catch (e) {}
}

function setCartBadge(count) {
  const badge = document.getElementById("cart-badge");
  if (!badge) return;
  badge.textContent = count;
  badge.style.display = count > 0 ? "flex" : "none";
}

// ── Toast notification ──────────────────────────────────
function showToast(message, type = "primary") {
  let container = document.getElementById("toast-container");
  if (!container) {
    container = document.createElement("div");
    container.id = "toast-container";
    container.className = "position-fixed bottom-0 end-0 p-3";
    container.style.zIndex = "9999";
    document.body.appendChild(container);
  }

  const id = "toast-" + Date.now();
  container.insertAdjacentHTML(
    "beforeend",
    `
    <div id="${id}" class="toast align-items-center text-bg-${type} border-0" role="alert">
      <div class="d-flex">
        <div class="toast-body">${message}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto"
                data-bs-dismiss="toast"></button>
      </div>
    </div>
  `,
  );

  const toastEl = document.getElementById(id);
  const toast = new bootstrap.Toast(toastEl, { delay: 3500 });
  toast.show();
  toastEl.addEventListener("hidden.bs.toast", () => toastEl.remove());
}
