import { Updater } from '@tanstack/vue-table'
import { type ClassValue, clsx } from 'clsx'
import { twMerge } from 'tailwind-merge'
import { Ref } from 'vue'

export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs))
}

export function valueUpdater<T extends Updater<any>>(
  updaterOrValue: T,
  ref: Ref,
) {
  ref.value =
    typeof updaterOrValue === 'function'
      ? updaterOrValue(ref.value)
      : updaterOrValue
}

export function getNestedProperty(obj: Record<string, any>, path: string) {
  return path.split('.').reduce((current, key) => current && current[key], obj)
}
