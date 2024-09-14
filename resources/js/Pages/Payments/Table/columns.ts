import { Payment, User } from '@/types'
import { Link } from '@inertiajs/vue3'
import { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

export const columns: ColumnDef<Payment>[] = [
  {
    accessorKey: 'id',
    header: '#',
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
  },
  {
    accessorKey: 'registerer',
    header: 'تم الاستلام بواسطة',
    cell: ({ row }) => {
      return row.getValue<User>('registerer').name
    },
  },
  {
    accessorKey: 'amount',
    header: 'المبلغ',
  },
  {
    accessorKey: 'created_at',
    header: 'التاريخ',
  },
]
