@php
    $locale = App::getLocale();

    switch ($locale) {
        case 'vi':
            $label = 'VI';
            break;
        case 'en':
            $label = 'EN';
            break;
        case 'ja':
            $label = 'JP';
            break;
        case 'zh':
            $label = 'CN';
            break;
        case 'fr':
            $label = 'FR';
            break;
        case 'es':
            $label = 'ES';
            break;
        default:
            $label = 'EN'; // fallback
    } 
@endphp
<button class="flags btn border-0 bg-transparent nav-link" onclick="openLangModal(event)">
    <span class="text-white">{{$label}}</span>
</button>
