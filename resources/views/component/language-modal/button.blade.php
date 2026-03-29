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
<a class="flags border-0 bg-transparent" onclick="openLangModal(event)">
    <span>{{$label}}</span>
</a>
