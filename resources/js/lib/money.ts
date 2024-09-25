export function formatMoney(money?: number): string {
  if (!money) return ''

  return new Intl.NumberFormat('en-US').format(money)
}
