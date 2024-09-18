import { Transaction, User } from '@/types'
import { Link } from '@inertiajs/vue3'
import { ColumnDef } from '@tanstack/vue-table'
import {
  CheckCircle2Icon,
  CircleDashed,
  CircleFadingArrowUpIcon,
  HandCoinsIcon,
  ShoppingCartIcon,
} from 'lucide-vue-next'
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
          href: route(`${row.getValue('type')}s.edit`, row.getValue('id')),
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
        icon = ShoppingCartIcon
        color = 'text-yellow-500'
      }

      return h(icon, { class: `text-right ${color}` })
    },
  },
  {
    accessorKey: 'status',
    header: 'الحالة',
    cell: ({ row }) => {
      if (row.getValue('status') === '') {
        return ''
      }

      var tag = CircleDashed
      var color = 'text-yellow-500'

      if (row.getValue('status') === 'مكتمل') {
        tag = CheckCircle2Icon
        color = 'text-green-500'
      } else if (row.getValue('status') === 'مرجع') {
        tag = CircleFadingArrowUpIcon
        color = 'text-red-500'
      }

      return h(
        tag,
        { class: `text-right ${color}` },
        { default: () => row.getValue('status') },
      )
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
