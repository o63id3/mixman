import {
  BaggageClaim,
  CircleCheck,
  CircleDashed,
  CircleFadingArrowUp,
  HandCoins,
  ShoppingCart,
  UserCog,
  Users,
  XCircle,
} from 'lucide-vue-next'
import { h } from 'vue'

export const roles = [
  {
    value: 'ahmed',
    label: 'مدير ',
    icon: h(UserCog, { class: 'text-green-500' }),
  },
  {
    value: 'partner',
    label: 'شريك',
    icon: h(Users, { class: 'text-blue-500' }),
  },
  {
    value: 'seller',
    label: 'بائع',
    icon: h(BaggageClaim, { class: 'text-yellow-500' }),
  },
]

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
