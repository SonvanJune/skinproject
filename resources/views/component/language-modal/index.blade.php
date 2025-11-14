@push('css')
    <style>
        .lang-modal {
            display: none;
            position: fixed;
            z-index: 100006;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5) overflow: auto;
        }

        .lang-modal .modal-content {
            position: absolute;
            transform: translate(-50%, -50%);
            padding: 10px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 60px;
        }

        .lang-modal .modal-close {
            position: absolute;
            top: 0;
            right: 0;
            font-size: 20px;
            background: none;
            border: none;
            cursor: pointer;
            color: #333;
        }

        .lang-modal .flag-img {
            width: 20px;
            transition: transform 0.2s ease;
            margin: 5px 5px;
        }

        .lang-modal .flag-img:hover {
            transform: scale(1.1);
        }

        .lang-modal .modal-body {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin-top: 5px;
        }
    </style>
@endpush
<div id="languageModal" class="lang-modal">
    <div class="modal-content" id="langModalContent">
        <button class="modal-close" id="closeLangModal">&times;</button>
        <div class="modal-body">
            @php
                $currentLocale = app()->getLocale();
                $languages = [
                    ['code' => 'vi', 'label' => 'VI', 'flag' => 'vn'],
                    ['code' => 'en', 'label' => 'EN', 'flag' => 'us'],
                    ['code' => 'ja', 'label' => 'JP', 'flag' => 'jp'],
                    ['code' => 'es', 'label' => 'ES', 'flag' => 'es'],
                    ['code' => 'fr', 'label' => 'FR', 'flag' => 'fr'],
                    ['code' => 'zh', 'label' => 'CN', 'flag' => 'cn'],
                ];
            @endphp

            <div class="d-flex flex-column flex-wrap gap-3 justify-content-center">
                @foreach ($languages as $lang)
                    <a href="{{ route('setLocale', $lang['code']) }}" title="{{ $lang['label'] }}"
                        class="lang-option d-flex flex-row align-items-center text-decoration-none 
                @if ($currentLocale === $lang['code']) fw-bold text-primary @endif">
                        <span class="small">{{ $lang['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>


@push('js')
    <script>
        const langModal = document.getElementById('languageModal');
        const closeLangModal = document.getElementById('closeLangModal');
        const modalContent = document.getElementById('langModalContent');

        function openLangModal(event) {
            const btnRect = event.target.getBoundingClientRect();

            langModal.style.display = 'block';

            modalContent.style.top = (btnRect.bottom + 140) + 'px';
            modalContent.style.left = (btnRect.left + 15) + 'px';
        }

        closeLangModal.addEventListener('click', function() {
            langModal.style.display = 'none';
        });

        window.addEventListener('click', function(event) {
            if (event.target === langModal) {
                langModal.style.display = 'none';
            }
        });
    </script>
@endpush
