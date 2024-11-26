document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.querySelector('input[name="gambar"]');
    const errorContainer = document.getElementById('upload-error');

    fileInput.addEventListener('change', (e) => {
        const file = fileInput.files[0];
        errorContainer.textContent = ''; // Clear previous error messages

        if (file) {
            const validImageTypes = ['image/jpeg', 'image/png'];
            const maxFileSize = 10 * 1024 * 1024; // 10 MB

            // Validate file type
            if (!validImageTypes.includes(file.type)) {
                errorContainer.textContent = 'Hanya file JPEG dan PNG yang diperbolehkan.';
                fileInput.value = ''; // Reset input file
                return;
            }

            // Validate file size
            if (file.size > maxFileSize) {
                errorContainer.textContent = 'Ukuran file tidak boleh lebih dari 10 MB.';
                fileInput.value = ''; // Reset input file
                return;
            }

            // Sanitize the file name
            const sanitizedFileName = file.name.replace(/[^a-zA-Z0-9_\-\.]/g, ''); // Only allow certain characters

            // Update file name back to input (this is not necessary in most browsers)
            // This line is commented out because it doesn't change the file's actual name
            // fileInput.files[0].name = sanitizedFileName; // This is not supported in most browsers
        }
    });
});
