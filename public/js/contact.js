const address = "Tran Nao, An Khanh, Quan 2, Ho Chi Minh City, Vietnam";
const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(
    address
)}`;

fetch(url)
    .then((response) => response.json())
    .then((data) => {
        if (data && data.length > 0) {
            const lat = data[0].lat;
            const lon = data[0].lon;
            var map = L.map("map").setView([lat, lon], 15);

            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                attribution:
                    '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            }).addTo(map);

            L.marker([lat, lon])
                .addTo(map)
                .bindPopup("Tran Nao, Quan 2 ,Ho Chi Minh City, VN")
                .openPopup();
        } else {
            console.log("Address not found");
        }
    })
    .catch((error) => console.error("Error:", error));

document
    .getElementById("contactForm")
    .addEventListener("submit", function (event) {
        event.preventDefault();

        const form = event.target;
        const email = form.email;
        const phone = form.phone;
        const name = form.name;
        const subject = form.subject;
        const country = form.country;
        const message = form.message;

        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailRegex.test(email.value)) {
            email.classList.add("is-invalid");
            email.classList.remove("is-valid");
        } else {
            email.classList.add("is-valid");
            email.classList.remove("is-invalid");
        }

        const phoneRegex = /^\+?\d{10,15}$/;
        if (!phoneRegex.test(phone.value)) {
            phone.classList.add("is-invalid");
            phone.classList.remove("is-valid");
        } else {
            phone.classList.add("is-valid");
            phone.classList.remove("is-invalid");
        }

        if(name.value == ""){
            name.classList.add("is-invalid");
            name.classList.remove("is-valid");
        }
        else{
            name.classList.add("is-valid");
            name.classList.remove("is-invalid");
        }

        if(country.value == ""){
            country.classList.add("is-invalid");
            country.classList.remove("is-valid");
        }
        else{
            country.classList.add("is-valid");
            country.classList.remove("is-invalid");
        }

        if(subject.value == ""){
            subject.classList.add("is-invalid");
            subject.classList.remove("is-valid");
        }
        else{
            subject.classList.add("is-valid");
            subject.classList.remove("is-invalid");
        }

        if(message.value == ""){
            message.classList.add("is-invalid");
            message.classList.remove("is-valid");
        }
        else{
            message.classList.add("is-valid");
            message.classList.remove("is-invalid");
        }

        if (
            form.checkValidity() === false ||
            !email.classList.contains("is-valid") ||
            !phone.classList.contains("is-valid")
        ) {
            event.stopPropagation();
        } else {
            document.getElementById('notiSuc').classList.remove('d-none');
            form.classList.add("was-validated");
            form.submit();
        }
    });
