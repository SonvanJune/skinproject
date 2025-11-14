window.addEventListener("DOMContentLoaded", function () {
    // const route = document.body.getAttribute('data-route-translate');
    const csrfToken = document.body.getAttribute("data-csrf-translate");

    const textsToTranslate = Array.from(
        document.querySelectorAll(".translate-text")
    ).map(el => el.textContent.trim());

    const sl = "en";
    const tl = document.documentElement.lang;

    textsToTranslate.forEach((text, index) => {
        const url = `https://translate.googleapis.com/translate_a/single?client=gtx&ie=UTF-8&oe=UTF-8&dt=t&sl=${sl}&tl=${tl}&q=${encodeURIComponent(text)}`;
    
        fetch(url)
            .then(res => res.json())
            .then(data => {
                const translated = data[0][0][0];                
                document.querySelectorAll(".translate-text")[index].textContent = translated;
            })
            .catch(err => console.error('Translate:', err));
    });
});
