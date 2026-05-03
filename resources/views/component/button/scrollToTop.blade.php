@push('css')
    <style>
        #scrollTopBtn {
            position: fixed;
            bottom: 30px;
            right: 1.5%;
            width: 48px;
            height: 48px;
            z-index: 999;
            display: none;
            background: rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid #000;
        }

        @media (max-width: 500px) {
            #scrollTopBtn {
                bottom: 100px;
            }
        }
    </style>
@endpush

<button id="scrollTopBtn" class="btn rounded-circle shadow d-flex align-items-center justify-content-center"
    title="Go to top">
    <i class="bi bi-chevron-up fs-5"></i>
</button>

@push('js')
    <script>
        window.onscroll = function() {
            const btn = document.getElementById("scrollTopBtn");
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                btn.style.display = "block";
            } else {
                btn.style.display = "none";
            }
        };

        document.getElementById("scrollTopBtn").onclick = function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        };
    </script>
@endpush
