export function formatMoney(money?: number): string {
  if (!money) return ''

  return new Intl.NumberFormat('ar').format(money)
}
