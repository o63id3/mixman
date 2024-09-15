import { Order, User } from '@/types'
import { Link } from '@inertiajs/vue3'
import { ColumnDef } from '@tanstack/vue-table'
import { CheckCircle2Icon, CircleDashed, XCircleIcon } from 'lucide-vue-next'
import { h } from 'vue'

interface CustomColumnMeta {
  class?: string
}

export const columns: ColumnDef<Order, CustomColumnMeta>[] = [
  {
    accessorKey: 'id',
    header: '#',
    meta: {
      class: 'w-5',
    },
  },
  {
    accessorKey: 'seller',
    header: 'البائع',
    cell: ({ row }) => {
      return h(
        Link,
        {
          href: route('orders.edit', row.getValue('id')),
          class: 'text-right font-medium hover:underline',
        },
        {
          default: () => row.getValue<User>('seller').name,
        },
      )
    },
    meta: {
      class: 'w-1/5',
    },
  },
  {
    accessorKey: 'status',
    header: 'الحالة',
    cell: ({ row }) => {
      var tag = CircleDashed
      var color = 'text-yellow-500'

      if (row.getValue('status') === 'C') {
        tag = CheckCircle2Icon
        color = 'text-green-500'
      } else if (row.getValue('status') === 'X') {
        tag = XCircleIcon
        color = 'text-red-500'
      }

      return h(
        tag,
        { class: `text-right ${color}` },
        { default: () => row.getValue('status') },
      )
    },
    meta: {
      class: 'w-5',
    },
  },
  {
    accessorKey: 'action',
    header: 'تم اتخاذ الاجراء بواسطة',
    cell: ({ row }) => {
      return h(
        'div',
        { class: 'text-right' },
        row.getValue<User>('action')?.name,
      )
    },
    meta: {
      class: 'w-1/5',
    },
  },
  {
    accessorKey: 'total_price_for_seller',
    header: 'اجمالي السعر للبائع',
    meta: {
      class: 'w-1/5',
    },
  },
  {
    accessorKey: 'total_price_for_consumer',
    header: 'اجمالي السعر للمستهلك',
    meta: {
      class: 'w-1/5',
    },
  },
  {
    accessorKey: 'updated_at',
    header: 'التاريخ',
    meta: {
      class: 'w-1/5',
    },
  },
]
