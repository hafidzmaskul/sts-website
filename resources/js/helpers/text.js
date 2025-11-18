export function getMaxWords(text, maxWords = 50) {
    if (!text) return '';

    const words = String(text).trim().split(/\s+/);

    if (words.length <= maxWords) {
        return text;
    }

    return words.slice(0, maxWords).join(' ') + '...';
}

