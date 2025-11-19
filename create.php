<form id="createUserForm" class="bg-gray-900 p-6 rounded-2xl shadow-xl w-[400px] space-y-4 text-white relative">
    <h2 class="text-xl font-semibold mb-2">Create New User</h2>

    <div>
        <label class="block mb-1">Full Name</label>
        <input type="text" id="fullName" class="w-full p-2 rounded-lg bg-gray-800 border border-gray-600 focus:ring-2 focus:ring-purple-500 outline-none"/>
    </div>

    <div>
        <label class="block mb-1">Username</label>
        <input type="text" id="username" class="w-full p-2 rounded-lg bg-gray-800 border border-gray-600 focus:ring-2 focus:ring-purple-500 outline-none"/>
    </div>

    <div>
        <label class="block mb-1">Email</label>
        <input type="email" id="email" class="w-full p-2 rounded-lg bg-gray-800 border border-gray-600 focus:ring-2 focus:ring-purple-500 outline-none"/>
    </div>

    <div>
        <label class="block mb-1">Phone</label>
        <input type="text" id="phone" class="w-full p-2 rounded-lg bg-gray-800 border border-gray-600 focus:ring-2 focus:ring-purple-500 outline-none"/>
    </div>

    <div>
        <label class="block mb-1">Password</label>
        <input type="password" id="password" class="w-full p-2 rounded-lg bg-gray-800 border border-gray-600 focus:ring-2 focus:ring-purple-500 outline-none"/>
    </div>

    <div>
        <label class="block mb-1">Confirm Password</label>
        <input type="password" id="confirmPassword" class="w-full p-2 rounded-lg bg-gray-800 border border-gray-600 focus:ring-2 focus:ring-purple-500 outline-none"/>
    </div>

    <div class="flex justify-end space-x-3 pt-2">
        <button type="button" id="cancelBtn" class="px-4 py-2 bg-gray-700 rounded-lg hover:bg-gray-600">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-purple-600 rounded-lg hover:bg-purple-700">Create</button>
    </div>
</form>
