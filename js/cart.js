const checkoutButton = document.getElementById('checkout-button');
const paymentButton = document.getElementById('payment-button');

const checkout = document.getElementById('checkout');
const container = document.getElementById('container');
const navbar = document.getElementById('header');
const footer = document.getElementById('footer');

checkoutButton.addEventListener('click', function(){
    checkout.style.display = "block";
    container.style.display = "none";
    navbar.style.display = "none";
    footer.style.display = "none";
});
paymentButton.addEventListener('click', function(){
    checkout.style.display = "none";
    container.style.display = "block";
    navbar.style.display = "flex";
    footer.style.display = "flex";
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
                console.log("✅ updated", data);

                const formatIDR = num => "IDR " + num.toLocaleString("id-ID");

                document.getElementById("subtotal").innerText = formatIDR(data.subtotal);
                document.getElementById("paymentFee").innerText = formatIDR(data.paymentFee);
                document.getElementById("tax").innerText = formatIDR(data.tax);
                document.getElementById("total").innerText = formatIDR(data.total);
                document.getElementById("total-button").innerText = formatIDR(data.total);
            } else {
                console.error("❌ Error dari PHP:", data.message);
            }
        })
        .catch(err => {
            console.error("❌ Fetch failed:", err);
        });
    });
});



