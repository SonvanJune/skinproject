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
            background-color: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            overflow: auto;
        }

        .lang-modal .modal-content {
            position: absolute;
            transform: translate(-50%, -50%);
            padding: 14px 10px;
            background: var(--bg-card, #12100f);
            color: var(--text-heading, #fff6ed);
            border: 1px solid var(--border-bronze, rgba(223, 183, 124, 0.35));
            border-radius: 14px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5), 0 0 15px var(--bronze-glow, rgba(197, 155, 99, 0.15));
            text-align: center;
            width: 80px;
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
        }

        .lang-modal .modal-close {
            position: absolute;
            top: 2px;
            right: 6px;
            font-size: 18px;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-muted, #b3a99c);
            transition: all 0.2s ease;
            line-height: 1;
        }

        .lang-modal .modal-close:hover {
            color: var(--bronze-light, #dfb77c);
        }

        .lang-modal .lang-option {
            color: var(--text-body, #d4cbbe);
            transition: all 0.2s ease;
            padding: 3px 6px;
            border-radius: 6px;
            justify-content: center;
        }

        .lang-modal .lang-option:hover {
            color: var(--bronze-light, #dfb77c);
            background: rgba(223, 183, 124, 0.12);
        }

        .lang-modal .lang-option.active-lang {
            color: var(--bronze-light, #dfb77c) !important;
            font-weight: 700;
            background: rgba(223, 183, 124, 0.18);
        }

        .lang-modal .modal-body {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin-top: 10px;
            padding: 0;
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

            <div class="d-flex flex-column flex-wrap gap-2 justify-content-center w-100">
                @foreach ($languages as $lang)
                    <a href="{{ route('setLocale', $lang['code']) }}" title="{{ $lang['label'] }}"
                        class="lang-option d-flex flex-row align-items-center text-decoration-none 
                @if ($currentLocale === $lang['code']) active-lang @endif">
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
