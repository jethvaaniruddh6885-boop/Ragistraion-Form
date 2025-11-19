<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- ✅ Toastify CDN -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" />
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" defer></script>

</head>

  <style>
    
    body {
      font-family: 'Inter', sans-serif;
      background: radial-gradient(circle at top, #0a0a0a, #1e1e2f);
      min-height: 100vh;
      color: #fff;
    }

    .sidebar {
      background: #11101d;
      box-shadow: 0 0 20px rgba(139, 92, 246, 0.5);
    }

    .sidebar a:hover {
      background: #8B5CF6;
      color: #fff;
    }

    .topbar {
      background: rgba(17, 17, 29, 0.9);
      backdrop-filter: blur(10px);
      box-shadow: 0 2px 15px rgba(139, 92, 246, 0.2);
    }

    .card {
      background: rgba(20, 20, 40, 0.95);
      border-radius: 2rem;
      padding: 4rem 3rem;
      max-width: 600px;
      margin: auto;
      text-align: center;
      box-shadow: 0 0 50px rgba(139, 92, 246, 0.7);
    }
     #userTable,
  #userTable th,
  #userTable td {
    color: #e5e7eb; /* Tailwind gray-200 */
  }

  /* Table header background */
  #userTable th {
    background-color: #7c3aed; /* Tailwind purple-700 */
    color: #fff;
  }

  /* Table body background */
  #userTable tbody {
    background-color: #1f2937; /* Tailwind gray-800 */
  }

  /* Pagination buttons */
  .dataTable-pagination li a {
    background-color: #1f2937;
    color: #e5e7eb;
    border: 1px solid #374151;
    padding: 0.25rem 0.75rem;
    border-radius: 0.5rem;
    transition: all 0.2s;
  }

  .dataTable-pagination li.active a {
    background-color: #7c3aed;
    color: #fff;
    border-color: #7c3aed;
  }

  .dataTable-pagination li a:hover {
    background-color: #6d28d9;
    color: #fff;
  }
  .datatable-selector {
    background-color: #1f2937;
    padding: 6px;
    color: white;
}
.datatable-input {
    background-color: #1f2937;
    padding: 6px;
    color: white;
  }
  .datatable-pagination .datatable-active button
  {
    background-color: #7c3aed;
    color: white;
  }
  .datatable-pagination .datatable-active button:hover {
    background-color: #6d28d9;
    cursor: default;
  }
  .datatable-pagination button:hover {
    background-color: #6d28d9;
    color: white;
  }
  </style>

  <script>
    let userDataTable = null; 
    function toggleDropdown() {
      document.getElementById('dropdownMenu').classList.toggle('hidden');
    }

  

function showUsers() {
  const main = document.getElementById('mainContent');
  main.innerHTML = `
    <div class="w-full max-w-5xl mx-auto bg-gray-900 bg-opacity-70 p-6 rounded-2xl shadow-lg border border-purple-700">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-purple-400">👥 User List</h2>
        <button onclick="openCreateModal()" class="bg-gradient-to-r from-purple-600 to-blue-500 text-white font-semibold px-4 py-2 rounded-lg">+ Add User</button>
      </div>
      
      <div class="overflow-x-auto">
        <table id="userTable" class="min-w-full border border-gray-700 rounded-lg overflow-hidden">
          <thead class="bg-purple-700 text-white">
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Phone</th>
              <th>Email</th>
               <th>Role</th>
              <th>Action</th>
             
            </tr>
          </thead>
          <tbody id="userTableBody" class="bg-gray-800 divide-y divide-gray-700 text-gray-200">
            <tr><td colspan="6" class="text-center py-4 text-gray-400">Loading users...</td></tr>
          </tbody>
        </table>
      </div>
    </div>`;

  fetch("get_users.php")
    .then(res => res.json())
    .then(data => {
      const tbody = document.getElementById("userTableBody");
      tbody.innerHTML = "";
      if (!data.length) {
        tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-gray-400">No users found.</td></tr>`;
      } else {
        let count = 1;
        data.forEach(u => {
          tbody.innerHTML += `
            <tr>
              <td class="px-4 py-2">${count++}</td>
              <td class="px-4 py-2">${u.full_name}</td>
              <td class="px-4 py-2">${u.phone}</td>
              <td class="px-4 py-2">${u.email}</td>
             <td class="px-4 py-2">${
    (u.role_id == 1 || u.role_id == "1" || u.role_id == "Admin" || u.role_id == "admin")
      ? "Admin"
      : "User"
  }
</td>
              <td class="px-4 py-2 flex space-x-2">
                <button data-view="${u.id}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-3 py-1 rounded-lg">View</button>
                <button data-edit="${u.id}" class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm px-3 py-1 rounded-lg">Edit</button>
                <button data-delete="${u.id}" class="bg-red-600 hover:bg-red-700 text-white text-sm px-3 py-1 rounded-lg">Delete</button>
              </td>
            </tr>`;
        });
      }

      // --- Initialize DataTable ---
      if (userDataTable) userDataTable.destroy();
      userDataTable = new simpleDatatables.DataTable("#userTable", {
        searchable: true,
        fixedHeight: true,
        perPage: 5,
        perPageSelect: [5, 10, 15],
      });

      // --- Event delegation for buttons ---
      document.querySelector("#userTable").addEventListener("click", function(e) {
        if (e.target.dataset.view) viewUser(e.target.dataset.view);
        if (e.target.dataset.edit) openEditModal(e.target.dataset.edit);
        if (e.target.dataset.delete) deleteUser(e.target.dataset.delete);
      });
    })
    .catch(err => console.error(err));
}

    // ✅ Edit Modal
    function openEditModal(userId) {
      const modal = document.getElementById("createModal");
      const content = document.getElementById("createModalContent");
      modal.classList.remove("hidden");
      content.innerHTML = `<div class='text-center text-gray-300 p-6'>Loading user data...</div>`;

      fetch(`user_edit.php?id=${userId}`)
        .then(res => res.text())
        .then(html => {
          content.innerHTML = html;
          const editForm = content.querySelector("#editForm");
          if (editForm) attachEditHandler(editForm);
        })
        .catch(() => {
          content.innerHTML = `<div class='text-red-500 p-4'>Error loading user info.</div>`;
        });
    }

    function attachEditHandler(form) {
      form.addEventListener("submit", async function(e) {
        e.preventDefault();
        const btn = form.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.textContent = "Saving...";

        const res = await fetch("user_update.php", {
          method: "POST",
          body: new FormData(form)
        });
        const data = await res.json();

        btn.disabled = false;
        btn.textContent = "Save Changes";

        if (data.success) {
          Toastify({
            text: "✅ User updated successfully!",
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: "#22c55e",
            close: true,
          }).showToast();

          showUsers();
          closeModal();
        } else {
          Toastify({
            text: data.message || "Update failed!",
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: "#ef4444",
            close: true,
          }).showToast();
        }
      });
    }

    // ✅ Delete Confirmation
    let deleteId = null;

    function deleteUser(id) {
      deleteId = id;
      document.getElementById('deleteModal').classList.remove('hidden');
    }

    function confirmDelete() {
      if (!deleteId) return;

      const formData = new FormData();
      formData.append('id', deleteId);

      fetch('user_delete.php', {
          method: 'POST',
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          document.getElementById('deleteModal').classList.add('hidden');
          if (data.success) {
            Toastify({
              text: "🗑️ User deleted successfully!",
              duration: 3000,
              gravity: "top",
              position: "right",
              backgroundColor: "#ef4444",
              close: true,
            }).showToast();
            showUsers();
          } else {
            Toastify({
              text: data.message || "Delete failed!",
              duration: 3000,
              gravity: "top",
              position: "right",
              backgroundColor: "#f97316",
              close: true,
            }).showToast();
          }
        })
        .catch(err => {
          Toastify({
            text: "Server error!",
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: "#f97316",
            close: true,
          }).showToast();
        });
    }

    function cancelDelete() {
      document.getElementById('deleteModal').classList.add('hidden');
    }

    // ✅ Common Modal Controls
    function openCreateModal() {
      // prevent page scroll while modal open
      document.body.style.overflow = "hidden";

      const modal = document.getElementById("createModal");
      const content = document.getElementById("createModalContent");
      modal.classList.remove("hidden");
      content.innerHTML = `<div class='text-center text-gray-300 p-6'>Loading...</div>`;

      fetch('user_reg.php')
        .then(r => r.text())
        .then(html => {
          content.innerHTML = html;

          // IMPORTANT: after injecting HTML, find the form and attach the JS handler
          const form = content.querySelector("#registerForm");
          if (form) {
            attachCreateFormHandler(form);
          } else {
            console.warn("registerForm not found inside user_reg.php output");
          }
        })
        .catch(err => {
          console.error("Failed to load user_reg.php:", err);
          content.innerHTML = `<div class='text-red-500 p-4'>Failed to load form.</div>`;
        });
    }

    function attachCreateFormHandler(form) {
      // Remove any previous listeners (safe guard)
      form.addEventListener("submit", async function onSubmit(e) {
        e.preventDefault();

        // clear old error UI
        form.querySelectorAll("input").forEach(i => i.classList.remove("border-red-500"));
        form.querySelectorAll("p[id^='error_']").forEach(p => {
          p.classList.add("hidden");
          p.textContent = "";
        });
        const successMsg = form.querySelector("#successMsg");
        if (successMsg) successMsg.classList.add("hidden");

        // prepare data
        const fd = new FormData(form);

        // send
        try {
          const res = await fetch("user_insert.php", {
            method: "POST",
            body: fd
          });
          const data = await res.json();

          if (data.success) {
            // toast + close
            Toastify({
              text: "✅ User created successfully!",
              duration: 3000,
              gravity: "top",
              position: "right",
              backgroundColor: "#22c55e",
              close: true,
            }).showToast();

            form.reset();
            if (successMsg) successMsg.classList.remove("hidden");

            // refresh list and close modal
            showUsers();
            setTimeout(() => {
              closeModal(); // your existing closeModal()
            }, 800);
            return;
          }

          // show validation errors (field wise)
          if (data.errors) {
            Object.keys(data.errors).forEach(key => {
              const errP = form.querySelector(`#error_${key}`);
              const input = form.querySelector(`[name="${key}"]`);
              if (input) input.classList.add("border-red-500");
              if (errP) {
                errP.textContent = data.errors[key];
                errP.classList.remove("hidden");
              }
            });
            return;
          }

          // general message
          Toastify({
            text: data.message || "Failed to save user",
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: "#ef4444",
            close: true,
          }).showToast();

        } catch (err) {
          console.error("AJAX error:", err);
          Toastify({
            text: "Server error. See console.",
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: "#f97316",
            close: true,
          }).showToast();
        }
      }, {
        once: true
      }); // attach once to avoid duplicate handlers
    }

    function closeCreateModal() {
      document.getElementById('createUserModal').classList.add('hidden');
    }

    function closeModal() {
      document.body.style.overflow = "auto";
      document.getElementById("createModal").classList.add("hidden");
    }

    function viewUser(id) {
      fetch(`view_user.php?id=${id}`)
        .then(res => res.json())
        .then(user => {
          if (user) {
            document.getElementById("view_full_name").value = user.full_name;
            document.getElementById("view_email").value = user.email;
            document.getElementById("view_phone").value = user.phone;
            document.getElementById("view_role").value = (user.role_id == 1 ? "Admin" : "User");
            document.getElementById("viewUserModal").classList.remove("hidden");
          } else {
            alert("User data not found!");
          }
        })
        .catch(err => {
          console.error(err);
          alert("Error loading user data.");
        });
    }

    function closeViewModal() {
      document.getElementById("viewUserModal").classList.add("hidden");
    }
 let bookDataTable = null;

function showBook() {
  const main = document.getElementById('mainContent');

  main.innerHTML = `
    <div class="w-full max-w-5xl mx-auto bg-gray-900 bg-opacity-70 p-6 rounded-2xl shadow-lg border border-purple-700">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-purple-400">📚 Book List</h2>
        <button onclick="openAddBookModal()" class="bg-gradient-to-r from-purple-600 to-blue-500 text-white font-semibold px-4 py-2 rounded-lg">+ Add Book</button>
      </div>

      <div class="overflow-x-auto">
        <table id="bookTable" class="min-w-full border border-gray-700 rounded-lg overflow-hidden">
          <thead class="bg-purple-700 text-white">
            <tr class="text-left bg-purple-700 text-white">
              <th class="px-4 py-2 text-left">S.No</th>
              <th class="px-4 py-2 text-left">Book Name</th>
              <th class="px-4 py-2 text-left">Author Name</th>
              <th class="px-4 py-2 text-left">Action</th>
            </tr>
          </thead>
          <tbody id="bookTableBody" class="bg-gray-800 divide-y divide-gray-700 text-gray-200">
            <tr><td colspan="4" class="text-center py-4 text-gray-400">Loading books...</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  `;

  fetch("get_books.php")
    .then(res => res.json())
    .then(data => {
      const tbody = document.getElementById("bookTableBody");
      tbody.innerHTML = "";

      if (!Array.isArray(data) || data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="4" class="text-center py-4 text-gray-400">No books found.</td></tr>`;
        return;
      }

      data.forEach((b, idx) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td class="px-4 py-2">${idx + 1}</td>
          <td class="px-4 py-2">${escapeHtml(b.book_name)}</td>
          <td class="px-4 py-2">${escapeHtml(b.author_name)}</td>
         <td class="px-4 py-2 space-x-2">

  <button onclick="viewBook('${encodeURIComponent(b.pdf_file)}')" 
    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-sm">
    View
  </button>

  <button onclick="assignBook(${b.book_id})" 
    class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg text-sm">
    Assign
  </button>

  <button onclick="openDeleteModal(${b.book_id}, '${escapeJs(b.pdf_file)}', this.closest('tr'))" 
    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-sm">
    Delete
  </button>

</td>
       `;
        tbody.appendChild(tr);
      });

      // Initialize DataTable AFTER rows are added
      if (bookDataTable) bookDataTable.destroy();
      bookDataTable = new simpleDatatables.DataTable("#bookTable", {
        searchable: true,
        fixedHeight: true,
        perPage: 5,
        perPageSelect: [5, 10, 15],
      });

    })
    .catch(err => { 
      console.error(err);
      document.getElementById("bookTableBody").innerHTML = `<tr><td colspan="4" class="text-center py-4 text-red-400">Error loading books.</td></tr>`;
    });
}


// small helpers to avoid XSS
function escapeHtml(str = '') {
  return String(str).replace(/[&<>"'`=\/]/g, s => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','/':'&#x2F;','`':'&#x60;','=':'&#x3D;'}[s]));
}
function escapeJs(str = '') {
  return String(str).replace(/'/g, "\\'").replace(/"/g, '\\"');
}

function viewBook(fileNameEncoded) {
  const fileName = decodeURIComponent(fileNameEncoded);
  const url = `http://user_ragistraionform.test:8081/books/${fileName}`;
  window.open(url, "_blank");
}




function openAddBookModal() {
  const modal = document.getElementById("createModal");
  const content = document.getElementById("createModalContent");

  modal.classList.remove("hidden");
  content.innerHTML = `<div class="text-center text-gray-300 p-6">Loading...</div>`;

  fetch("add_book_form.php")
    .then(res => res.text())
    .then(html => {
      content.innerHTML = html;

      // 🔥 Attach form handler after loading HTML
      const form = content.querySelector("#addBookForm");
      if (form) {
        attachBookFormHandler(form);
      } else {
        console.error("❌ Book form not found!");
      }
    })
    .catch(() => {
      content.innerHTML = `<p class="text-red-500 p-4">Failed to load book form.</p>`;
    });
}
// deleteTarget will be filled by openDeleteModal()
let deleteTarget = { id: null, file: null, rowElement: null };

function openDeleteModal(book_id, fileName, rowEl = null) {
  deleteTarget.id = book_id;
  deleteTarget.file = fileName || '';
  deleteTarget.rowElement = rowEl;
  document.getElementById('deleteModalText').textContent = "Are you sure you want to delete this book?";
  document.getElementById('bookDeleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
  document.getElementById('bookDeleteModal').classList.add('hidden');
}

// Ensure DOM loaded before attaching listeners
document.addEventListener('DOMContentLoaded', () => {
  // Cancel button
  document.getElementById('cancelBookDeleteBtn').addEventListener('click', closeDeleteModal);

  // Confirm Delete
  document.getElementById('confirmBookDeleteBtn').addEventListener('click', async () => {
    if (!deleteTarget.id) return;

    try {
      const fd = new FormData();
      fd.append('id', deleteTarget.id);
      fd.append('file', deleteTarget.file);

      const res = await fetch('delete_book.php', { method: 'POST', body: fd });
      const data = await res.json();

      if (data.success) {
        // show toast
        Toastify({ text: "🗑️ Book deleted", duration: 2500, gravity: "top", position: "right", backgroundColor: "#ef4444", close: true }).showToast();

        // remove row from table if available
        if (deleteTarget.rowElement) {
          deleteTarget.rowElement.remove();
        } else if (typeof showBook === 'function') {
          showBook();
        }

        closeDeleteModal();
      } else {
        Toastify({ text: data.message || "Delete failed", duration: 3000, gravity: "top", position: "right", backgroundColor: "#f97316", close: true }).showToast();
        console.error('Delete failed:', data);
      }
    } catch (err) {
      console.error(err);
      Toastify({ text: "Server error", duration: 3000, gravity: "top", position: "right", backgroundColor: "#f97316", close: true }).showToast();
    }
  });
});


function attachBookFormHandler(form) {
  form.addEventListener("submit", async function(e) {
    e.preventDefault();

    const fd = new FormData(form);

    // Clear old errors
    form.querySelectorAll("p[id^='error_']").forEach(p => p.classList.add("hidden"));
    form.querySelectorAll("input").forEach(i => i.classList.remove("border-red-500"));

    try {
      const res = await fetch("insert_book.php", {
        method: "POST",
        body: fd
      });

      const data = await res.json();

      if (data.success) {
        Toastify({
          text: "📚 Book Added Successfully!",
          duration: 3000,
          gravity: "top",
          position: "right",
          backgroundColor: "#22c55e",
          close: true,
        }).showToast();

        form.reset();
        showBook();
        setTimeout(() => closeModal(), 800);

      } else if (data.errors) {
        Object.entries(data.errors).forEach(([key, msg]) => {
          const err = form.querySelector("#error_" + key);
          const input = form.querySelector(`[name="${key}"]`);

          if (err) {
            err.textContent = msg;
            err.classList.remove("hidden");
          }
          if (input) input.classList.add("border-red-500");
        });

      }

    } catch (error) {
      console.error("Insert error:", error);
    }
  });
}
let currentAssignBookId = null;

function assignBook(bookId) {
  currentAssignBookId = bookId;

  document.getElementById("assignModal").classList.remove("hidden");

  fetch("get_users.php")
    .then(res => res.json())
    .then(users => {
      let list = "";

      users.forEach(u => {
        list += `
          <label class="flex items-center space-x-3 bg-gray-800 p-2 rounded-lg">
            <input type="checkbox" value="${u.id}" class="assign-user" />
            <span>${u.full_name} (${u.email})</span>
          </label>
        `;
      });

      document.getElementById("assignUserList").innerHTML = list;
    });
}
function closeAssignModal() {
  document.getElementById("assignModal").classList.add("hidden");
}
function submitAssign() {
  const selectedUsers = [...document.querySelectorAll(".assign-user:checked")].map(ch => ch.value);

  if (selectedUsers.length === 0) {
    alert("Please select at least one user.");
    return;
  }

  fetch("assign_book.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      book_id: currentAssignBookId,
      users: selectedUsers
    })
  })
  .then(res => res.text())
  .then(msg => {
    alert(msg);
    closeAssignModal();
  })
  .catch(err => console.error(err));
}


    

  </script>
</head>

<body class="flex h-screen">
  <aside class="sidebar w-64 flex flex-col text-white">
    <div class="p-5 text-2xl font-bold border-b border-gray-700">🌌 SpaceDash</div>
    <nav class="flex-1 p-4 space-y-2">
      <a href="#" onclick="location.reload()" class="block px-3 py-2 rounded">🏠 Home</a>
      <a href="#" onclick="showUsers()" class="block px-3 py-2 rounded">👥 Users</a>
      <a href="#" onclick="showBook()" class="block px-3 py-2 rounded">📚 Books</a>
    </nav>
  </aside>

  <div class="flex-1 flex flex-col">
    <header class="topbar flex items-center justify-between px-6 py-4">
      <h1 class="text-xl font-bold text-purple-400">Dashboard</h1>
      <div class="relative">
        <button onclick="toggleDropdown()" class="flex items-center space-x-2 bg-gray-800 px-4 py-2 rounded-lg hover:bg-gray-700">
          <span class="font-medium text-white"><?php echo htmlspecialchars($username); ?></span>
        </button>
        <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-40 bg-gray-800 border border-gray-700 rounded shadow-lg z-10">
          <a href="logout.php" class="block px-4 py-2 text-white rounded">Logout</a>
        </div>
      </div>
    </header>

    <main id="mainContent" class="flex-1 flex items-center justify-center p-6">
      <div class="card">
        <h1>Welcome, <?php echo htmlspecialchars($username); ?> 🌌</h1>
        <p>Glad to have you back! Explore your dashboard and manage your account easily.</p>
      </div>
    </main>
  </div>

  <!-- ✅ Scrollable Modal -->
  <div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 overflow-y-auto">
    <div class="relative bg-gray-900 text-white p-6 rounded-xl w-full max-w-lg shadow-lg border border-purple-700 my-10">
      <button onclick="closeModal()" class="absolute top-3 right-3 text-gray-400 hover:text-white text-xl">&times;</button>
      <div id="createModalContent" class="overflow-y-auto max-h-[80vh] pr-2"></div>
    </div>
  </div>

  <!-- ✅ Delete Confirmation Modal -->
  <div id="deleteModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-gray-900 border border-purple-600 p-6 rounded-lg shadow-lg w-96 text-center">
      <h2 class="text-lg font-semibold mb-4 text-white">Are you sure you want to delete this user?</h2>
      <div class="flex justify-center space-x-3">
        <button onclick="cancelDelete()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Cancel</button>
        <button onclick="confirmDelete()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">Yes, Delete</button>
      </div>
    </div>
  </div>
 
 

  <!-- View User Modal -->
  <div id="viewUserModal" class="hidden fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50">
    <div class="bg-gray-900 text-white p-6 rounded-2xl shadow-lg w-full max-w-md border border-purple-700">
      <h2 class="text-2xl font-bold text-purple-400 mb-4">👁️ View User Details</h2>

      <div class="space-y-3">
        <div>
          <label class="block text-sm text-gray-400">Full Name</label>
          <input id="view_full_name" class="w-full bg-gray-800 rounded-lg px-3 py-2 text-gray-300" readonly />
        </div>
        <div>
          <label class="block text-sm text-gray-400">Email</label>
          <input id="view_email" class="w-full bg-gray-800 rounded-lg px-3 py-2 text-gray-300" readonly />
        </div>
        <div>
          <label class="block text-sm text-gray-400">Phone</label>
          <input id="view_phone" class="w-full bg-gray-800 rounded-lg px-3 py-2 text-gray-300" readonly />
        </div>
        <div>
          <label class="block text-sm text-gray-400">Role</label>
          <input id="view_role" class="w-full bg-gray-800 rounded-lg px-3 py-2 text-gray-300" readonly />
        </div>
      </div>

      <div class="flex justify-end mt-6">
        <button onclick="closeViewModal()"
          class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg shadow">
          Close
        </button>
      </div>
    </div>
  </div>
  <!-- BOOK DELETE Modal -->
<div id="bookDeleteModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
  <div class="bg-gray-900 text-white p-6 rounded-lg w-96 text-center">
    <h3 class="text-lg font-semibold mb-2">Delete Book</h3>
    <p id="deleteModalText" class="text-sm text-gray-300 mb-4">This book will be permanently deleted.</p>

    <div class="flex justify-center gap-3">
      <button id="confirmBookDeleteBtn" class="bg-red-600 px-4 py-2 rounded">Yes, Delete</button>
      <button id="cancelBookDeleteBtn" class="bg-gray-600 px-4 py-2 rounded">Cancel</button>
    </div>
  </div>
</div>
<div id="assignModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
  <div class="bg-gray-900 p-6 rounded-xl w-full max-w-xl border border-purple-700">
      
      <h2 class="text-xl font-bold text-purple-400 mb-4">Assign Book</h2>

      <div id="assignUserList" class="max-h-64 overflow-y-auto space-y-2 text-gray-300">
        Loading users...
      </div>

      <div class="flex justify-end mt-4 space-x-2">
        <button onclick="closeAssignModal()" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
          Cancel
        </button>
        <button onclick="submitAssign()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
          Submit
        </button>
      </div>

  </div>
</div>


 
 
<script>
function showToast(message, type = "success") {
    let bg = type === "error" ? "bg-red-600" : "bg-green-600";

    const toast = document.createElement("div");
    toast.className =
        bg + " text-white px-4 py-2 rounded shadow fixed top-4 right-4 z-50";
    toast.textContent = message;

    document.body.appendChild(toast);

    setTimeout(() => toast.remove(), 3000);
}
</script>




</body>
<?php if (isset($_GET['book']) && $_GET['book'] == "added") : ?>
  <script>
    setTimeout(() => {
      Toastify({
        text: "📚 Book Added Successfully!",
        duration: 3000,
        close: true,
        gravity: "top",
        position: "right",
        backgroundColor: "#4ade80"
      }).showToast();
    }, 300);
  </script>
<?php endif; ?>

</html>