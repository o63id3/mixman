import { Payment, Transaction, User } from '@/types'
import { Link } from '@inertiajs/vue3'
import { ColumnDef } from '@tanstack/vue-table'
import { HandCoinsIcon, TicketMinusIcon } from 'lucide-vue-next'
import { h } from 'vue'

export const columns: ColumnDef<Transaction>[] = [
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
    accessorKey: 'type',
    header: 'النوع',
    cell: ({ row }) => {
      var icon = HandCoinsIcon
      var color = 'text-green-500'

      if (row.getValue('type') === 'order') {
        icon = TicketMinusIcon
        color = 'text-yellow-500'
      }

      return h(icon, { class: `text-right ${color}` })
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
