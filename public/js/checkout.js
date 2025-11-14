document.addEventListener("DOMContentLoaded", function() {
    var paymentMethodSelect = document.getElementById("payment-method");
    var paymentDetailsDivs = document.querySelectorAll(".payment-details > div");
    var paymentDetailsContainer = document.getElementById("payment-details");

    paymentMethodSelect.addEventListener("change", function() {
        var method = this.value;

        paymentDetailsDivs.forEach(function(div) {
            div.style.display = "none";
        });
        paymentDetailsContainer.style.display = "block";

        if (method === "credit-card") {
            document.getElementById("credit-card-details").style.display = "block";
        } else if (method === "paypal") {
            document.getElementById("paypal-details").style.display = "block";
        } else if (method === "bank-transfer") {
            document.getElementById("bank-transfer-details").style.display = "block";
        }
    });
});

const notiSuc = document.getElementById("notiSuc");
const payButton = document.getElementById('paybut');

payButton.addEventListener('click', function (event) {
    notiSuc.classList.remove("d-none");
});

const voucherSelected = document.getElementById('voucherSelected');



