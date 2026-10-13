@push('css')
    <style>
        #scrollTopBtn {
            position: fixed;
            bottom: 30px;
            right: 1.5%;
            width: 46px;
            height: 46px;
            z-index: 999;
            display: none;
            align-items: center;
            justify-content: center;
            border-radius: 50% !important;
            background: var(--bg-card, rgba(20, 20, 25, 0.85)) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--border-bronze, rgba(197, 155, 99, 0.45)) !important;
            color: var(--bronze-light, #dfb77c) !important;
            box-shadow: 0 8px 25px var(--card-shadow, rgba(0, 0, 0, 0.25)), 0 0 15px var(--bronze-glow, rgba(197, 155, 99, 0.2)) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            cursor: pointer;
            padding: 0;
        }

        #scrollTopBtn:hover {
            background: var(--bronze-gradient, linear-gradient(135deg, #dfb77c, #96672d)) !important;
            color: #070709 !important;
            border-color: var(--bronze-light, #dfb77c) !important;
            transform: translateY(-4px) scale(1.06);
            box-shadow: 0 12px 30px var(--bronze-glow, rgba(197, 155, 99, 0.45)) !important;
        }

        #scrollTopBtn i {
            color: inherit !important;
            font-size: 1.25rem !important;
            line-height: 1 !important;
            transition: transform 0.25s ease;
        }

        #scrollTopBtn:hover i {
            transform: translateY(-2px);
        }

        @media (max-width: 500px) {
            #scrollTopBtn {
                bottom: 100px;
            }
        }
    </style>
@endpush

<button id="scrollTopBtn" class="btn rounded-circle shadow d-flex align-items-center justify-content-center"
    title="Go to top" aria-label="Go to top">
    <i class="bi bi-chevron-up"></i>
</button>

@push('js')
    <script>
        window.onscroll = function() {
            const btn = document.getElementById("scrollTopBtn");
            if (btn) {
                if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                    btn.style.display = "flex";
                } else {
                    btn.style.display = "none";
                }
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
