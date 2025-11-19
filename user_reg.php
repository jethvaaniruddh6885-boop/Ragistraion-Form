<form id="registerForm" class="space-y-4" novalidate>
  <!-- Full Name -->
  <div>
    <label class="block text-sm font-medium">Full Name</label>
    <input type="text" name="full_name" autocomplete="name" class="mt-1 w-full p-2 rounded-lg bg-gray-900 border border-gray-700">
    <p id="error_full_name" class="text-red-400 text-sm hidden"></p>
  </div>

  <!-- Username -->
  <div>
    <label class="block text-sm font-medium">Username</label>
    <input type="text" name="username" autocomplete="username" class="mt-1 w-full p-2 rounded-lg bg-gray-900 border border-gray-700">
    <p id="error_username" class="text-red-400 text-sm hidden"></p>
  </div>

  <!-- Email -->
  <div>
    <label class="block text-sm font-medium">Email</label>
    <input type="email" name="email" autocomplete="email" class="mt-1 w-full p-2 rounded-lg bg-gray-900 border border-gray-700">
    <p id="error_email" class="text-red-400 text-sm hidden"></p>
  </div>

  <!-- Phone -->
  <div>
    <label class="block text-sm font-medium">Phone</label>
    <input type="text" name="phone" maxlength="10" autocomplete="tel" class="mt-1 w-full p-2 rounded-lg bg-gray-900 border border-gray-700">
    <p id="error_phone" class="text-red-400 text-sm hidden"></p>
  </div>
 <!-- Role -->
 <div>
    <label class="block text-sm font-medium">Role</label>
    <select name="role_id" class="mt-1 w-full p-2 rounded-lg bg-gray-900 border border-gray-700">
      <option value="1">Admin</option>
      <option value="2" selected>User</option>
    </select>
    <p id="error_role_id" class="text-red-400 text-sm hidden"></p>  
  </div>
  <!-- Password -->
  <div>
    <label class="block text-sm font-medium">Password</label>
    <input type="password" name="password" autocomplete="new-password" class="mt-1 w-full p-2 rounded-lg bg-gray-900 border border-gray-700">
    <p id="error_password" class="text-red-400 text-sm hidden"></p>
  </div>

  <!-- Confirm -->
  <div>
    <label class="block text-sm font-medium">Confirm Password</label>
    <input type="password" name="confirm_password" autocomplete="new-password" class="mt-1 w-full p-2 rounded-lg bg-gray-900 border border-gray-700">
    <p id="error_confirm_password" class="text-red-400 text-sm hidden"></p>
  </div>

  <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-blue-500 py-2 rounded-lg font-semibold">Submit</button>
  <p id="successMsg" class="text-green-400 text-center mt-3 hidden font-medium">Form submitted successfully!</p>
</form>
