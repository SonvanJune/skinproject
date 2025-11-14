function highlightMatch(text, keyword) {
    if (!keyword) return text;
    const escapedKeyword = keyword.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); // escape regex
    const regex = new RegExp(`(${escapedKeyword})`, 'gi');
    return text.replace(
        regex,
        '<span class="text-primary fw-bold border border-primary bg-primary bg-opacity-10">$1</span>'
    );
}