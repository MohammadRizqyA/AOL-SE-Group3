const checkoutButton = document.getElementById('checkout-button');
const paymentButton = document.getElementById('payment-button');

const checkout = document.getElementById('checkout');
const container = document.getElementById('container');
const navbar = document.getElementById('header');

checkoutButton.addEventListener('click', function(){
    checkout.style.display = "block";
    container.style.display = "none";
    navbar.style.display = "none";
});
paymentButton.addEventListener('click', function(){
    checkout.style.display = "none";
    container.style.display = "block";
    navbar.style.display = "flex";
});


document.querySelectorAll('input[name="paymentTypeID"]').forEach((radio) => {
    radio.addEventListener('change', function () {
        const paymentTypeID = this.value;
        const paymentFee = parseFloat(this.dataset.fee);

        const formData = new FormData();
        formData.append("paymentTypeID", paymentTypeID);
        formData.append("paymentFee", paymentFee);

        fetch('update_checkout.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                console.log("✅ updated");

                // Update UI secara langsung
                const subtotal = parseFloat(document.getElementById("subtotal").innerText.replace("$", "")) || 0;
                const tax = parseFloat((subtotal * 0.02).toFixed(2));
                const total = (subtotal + paymentFee + tax).toFixed(2);

                document.getElementById("paymentFee").innerText = "$" + paymentFee.toFixed(2);
                document.getElementById("tax").innerText = "$" + tax.toFixed(2);
                document.getElementById("total").innerText = "$" + total;
                document.getElementById("total-button").innerText = "$" + total;
            } else {
                console.error("❌ Error:", data.message);
            }
        });
    });
});

