document.addEventListener('DOMContentLoaded', function() {
    const orderButtons = document.querySelectorAll('.order-button');

    orderButtons.forEach(button => {
        button.addEventListener('click', function() {
            const nama = this.getAttribute('data-nama');
            const harga = this.getAttribute('data-harga');
            const messageContainer = this.nextElementSibling; // Get the adjacent message container

            // Update the message container with the order details
            messageContainer.textContent = `You ordered: ${nama} | Price: Rp ${harga}`;
        });
    });
});


