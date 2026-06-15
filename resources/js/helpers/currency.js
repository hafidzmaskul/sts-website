/**
 * Format price to currency string (e.g. £1,234.56)
 * If the price is falsy, zero, or invalid, returns '-'
 * 
 * @param {number|string|null|undefined} price 
 * @returns {string}
 */
export function formatPrice(price) {
    const num = parseFloat(price);
    
    if (price === null || price === undefined || price === '' || isNaN(num) || num === 0) {
        return '-';
    }
    
    return '£' + num.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
}
