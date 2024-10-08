export function formatDate(date: string, withTime: boolean = true): string {
  const options: Intl.DateTimeFormatOptions = {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  }

  if (withTime) {
    options.hour = '2-digit'
    options.minute = '2-digit'
  }

  return new Date(date).toLocaleString('ar-US', options)
}

export function formatMoney(money?: number): string {
  if (!money) return ''

  return new Intl.NumberFormat('en-US').format(money)
}