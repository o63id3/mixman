export function formatMoney(money?: number): string {
  if (money === undefined) return ''

  return new Intl.NumberFormat('ar').format(money)
}
