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
            background: linear-gradient(135deg, #F45B3A, #F9A23B, #B56BB3, #6A6FF5) !important;
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
