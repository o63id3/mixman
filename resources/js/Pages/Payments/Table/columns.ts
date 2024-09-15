import { Payment, User } from '@/types'
import { Link } from '@inertiajs/vue3'
import { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

export const columns: ColumnDef<Payment>[] = [
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
          href: route('payments.edit', row.getValue('id')),
          class: 'text-right font-medium hover:underline',
        },
        {
          default: () => row.getValue<User>('seller').name,
        },
      )
    },
    meta: {
      class: 'w-1/4',
    },
  },
  {
    accessorKey: 'registerer',
    header: 'تم الاستلام بواسطة',
    cell: ({ row }) => {
      return row.getValue<User>('registerer').name
    },
    meta: {
      class: 'w-1/4',
    },
  },
  {
    accessorKey: 'amount',
    header: 'المبلغ',
    meta: {
      class: 'w-1/4',
    },
  },
  {
    accessorKey: 'created_at',
    header: 'التاريخ',
    meta: {
      class: 'w-1/4',
    },
  },
]
