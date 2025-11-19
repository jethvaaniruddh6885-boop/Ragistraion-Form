<?php
include 'db_connect.php'; // your database connection file

$id = $_GET['id'] ?? 0;
$query = $conn->prepare("SELECT * FROM users WHERE id = ?");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

if (!$user) {
  echo "<div class='text-red-400 p-4 text-center'>User not found.</div>";
  exit;
}
?>

<h2 class="text-xl font-semibold mb-4 text-purple-400 text-center">Edit User</h2>
<form id="editForm" class="space-y-4">
  <input type="hidden" name="id" value="<?= $user['id'] ?>">

  <div>
    <label class="block text-sm font-medium">Full Name</label>
    <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" class="w-full mt-1 p-2 bg-gray-900 border border-gray-700 rounded-lg focus:ring-2 focus:ring-purple-600">
  </div>

  <div>
    <label class="block text-sm font-medium">Email</label>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="w-full mt-1 p-2 bg-gray-900 border border-gray-700 rounded-lg focus:ring-2 focus:ring-purple-600">
  </div>

  <div>
    <label class="block text-sm font-medium">Phone</label>
    <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" class="w-full mt-1 p-2 bg-gray-900 border border-gray-700 rounded-lg focus:ring-2 focus:ring-purple-600">
  </div>

  <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 py-2 rounded-lg font-semibold">
    Save Changes
  </button>
</form>

<p id="editMsg" class="text-center mt-3 text-green-400 hidden">✅ User updated successfully!</p>

<script>
document.getElementById("editForm").addEventListener("submit", async function(e){
  e.preventDefault();
  const form = e.target;
  const submitBtn = form.querySelector('button[type="submit"]');
  const msg = document.getElementById("editMsg");

  // clear previous inline errors
  form.querySelectorAll("input").forEach(i => i.classList.remove("border-red-500"));
  form.querySelectorAll("p[id^='error_']").forEach(p => { p.textContent = ""; p.classList.add("hidden"); });
  msg.classList.add("hidden");

  submitBtn.disabled = true;
  submitBtn.textContent = "Saving...";

  try {
    const res = await fetch("user_update.php", { method: "POST", body: new FormData(form) });
    const data = await res.json();

    submitBtn.disabled = false;
    submitBtn.textContent = "Save Changes";

    if (data.success) {
      msg.textContent = data.message || "Updated";
      msg.classList.remove("hidden");
      // refresh users list in parent dashboard without full reload
      if (window.parent && window.parent.showUsers) window.parent.showUsers();
      // close modal after short delay
      setTimeout(() => {
        if (window.parent && window.parent.closeModal) window.parent.closeModal();
      }, 900);
      return;
    }

    // If server returned field-wise errors
    if (data.errors) {
      Object.keys(data.errors).forEach(key => {
        const input = form.querySelector(`[name="${key}"]`);
        const errP = form.querySelector(`#error_${key}`);
        if (input) input.classList.add("border-red-500");
        if (errP) { errP.textContent = data.errors[key]; errP.classList.remove("hidden"); }
      });
      return;
    }

    // Otherwise show general message nicely
    alert("Update failed: " + (data.message || "Unknown error"));
  } catch (err) {
    submitBtn.disabled = false;
    submitBtn.textContent = "Save Changes";
    alert("AJAX error: " + err);
    console.error(err);
  }
});
</script>
