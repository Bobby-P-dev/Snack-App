/**
 * Generate the correct URL for product/carousel images.
 * Supports both full URLs (S3/MinIO) and local storage paths.
 */
export function getImageUrl(url) {
  if (!url) return ''
  // If it's already a full URL (http:// or https://), use as-is
  if (url.startsWith('http')) return url
  // Otherwise, treat as local storage path
  return `/storage/${url}`
}

/**
 * Format number to IDR currency format.
 */
export function formatNumber(num) {
  if (num === null || num === undefined || num === '') return '0'
  return new Intl.NumberFormat('id-ID').format(num)
}
