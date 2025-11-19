<div class="text-xl font-bold text-purple-400 mb-4">➕ Add Book</div>

<form id="addBookForm" enctype="multipart/form-data" class="space-y-4">

    <!-- Book Name -->
    <div>
        <label class="block text-sm text-gray-300">Book Name</label>
        <input type="text" name="book_name" 
               class="w-full px-3 py-2 bg-gray-800 rounded-lg border border-gray-600 text-white"
               placeholder="Enter Book Name">
        <p id="error_book_name" class="text-red-400 text-sm hidden"></p>
    </div>

    <!-- Author Name -->
    <div>
        <label class="block text-sm text-gray-300">Author Name</label>
        <input type="text" name="author_name" 
               class="w-full px-3 py-2 bg-gray-800 rounded-lg border border-gray-600 text-white"
               placeholder="Enter Author Name">
        <p id="error_author_name" class="text-red-400 text-sm hidden"></p>
    </div>

    <!-- PDF Upload -->
    <div>
        <label class="block text-sm text-gray-300">Upload PDF</label>
        <input type="file" name="pdf_file" accept="application/pdf"
               class="w-full px-3 py-2 bg-gray-800 rounded-lg border border-gray-600 text-white">
        <p id="error_pdf_file" class="text-red-400 text-sm hidden"></p>
    </div>

    <!-- Success Message -->
    <p id="successMsg" class="text-green-400 hidden">Book Added Successfully!</p>

    <!-- Submit Button -->
    <button type="submit"
        class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg">
        Save Book
    </button>

</form>

<script>
document.getElementById("addBookForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    const form = e.target;
    const fd = new FormData(form);

    document.querySelectorAll("p[id^='error_']").forEach(p => p.classList.add("hidden"));
    document.querySelectorAll("input").forEach(i => i.classList.remove("border-red-500"));

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
            setTimeout(() => { closeModal(); }, 800);

        } else if (data.errors) {
            Object.entries(data.errors).forEach(([key, msg]) => {
                document.getElementById("error_" + key).textContent = msg;
                document.getElementById("error_" + key).classList.remove("hidden");
                const input = form.querySelector(`[name="${key}"]`);
                if (input) input.classList.add("border-red-500");
            });

        } else {
            Toastify({
                text: "Failed to add book!",
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: "#ef4444",
                close: true,
            }).showToast();
        }

    } catch (err) {
        console.error(err);
    }
});
</script>
