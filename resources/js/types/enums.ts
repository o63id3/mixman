import {
  CircleCheck,
  CircleDashed,
  CircleFadingArrowUp,
  HandCoins,
  RotateCcw,
  ShoppingCart,
  XCircle,
} from 'lucide-vue-next'
import { h } from 'vue'

export const active = [
  {
    value: true,
    label: 'فعال',
    icon: h(CircleCheck, { class: 'text-green-500' }),
  },
  {
    value: false,
    label: 'معطل',
    icon: h(XCircle, { class: 'text-red-500' }),
  },
]

export const orderStatues = [
  {
    value: 'معلق',
    label: 'معلق',
    icon: h(CircleDashed, { class: 'text-yellow-500' }),
  },
  {
    value: 'مرجع',
    label: 'مرجع',
    icon: h(CircleFadingArrowUp, { class: 'text-red-500' }),
  },
  {
    value: 'مكتمل',
    label: 'مكتمل',
    icon: h(CircleCheck, { class: 'text-green-500' }),
  },
]

export const transactionTypes = [
  {
    value: 'order',
    label: 'طلبية',
    icon: h(ShoppingCart, { class: 'text-yellow-500' }),
  },
  {
    value: 'payment',
    label: 'دفعة',
    icon: h(HandCoins, { class: 'text-green-500' }),
  },
]
