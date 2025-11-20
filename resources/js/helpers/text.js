export function getMaxWords(text, maxWords = 50) {
    if (!text) return '';

    const words = String(text).trim().split(/\s+/);

    if (words.length <= maxWords) {
        return text;
    }

    return words.slice(0, maxWords).join(' ') + '...';
}

export function getMaxCharacters(text, maxCharacters = 20) {
    if (!text) return '';

    const plainText = String(text).replace(/<[^>]*>/g, '').trim();

    if (plainText.length <= maxCharacters) {
        return plainText;
    }

    return plainText.slice(0, maxCharacters) + '...';
}
