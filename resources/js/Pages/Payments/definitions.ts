import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import { DataTableColumnHeader } from '@/Components/data-table'
import { Payment } from '@/types'
import { Link } from '@inertiajs/vue3'
import { formatDate, formatMoney } from '@/lib/formatters'

export const columns: ColumnDef<Payment>[] = [
  {
    accessorKey: 'recipient',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'المستلم' }),
    cell: ({ row }) =>
      h(
        Link,
        {
          href: `${route(`payments.${row.original.can.update ? 'edit' : 'show'}`, row.original.id)}`,
          class: 'hover:underline',
        },
        {
          default: () => row.original.recipient.name,
        },
      ),
    enableSorting: false,
  },
  {
    accessorKey: 'user',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'الاسم',
      }),
    cell: ({ row }) => row.original.user.name,
    enableSorting: false,
  },
  {
    accessorKey: 'network',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'الشبكة',
      }),
    cell: ({ row }) => row.original.network.name,
    enableSorting: false,
  },
  {
    accessorKey: 'amount',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'المبلغ',
      }),
    cell: ({ row }) => formatMoney(row.original.amount),
  },
  {
    accessorKey: 'created_at',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'التاريخ',
      }),
    cell: ({ row }) => formatDate(row.original.created_at_date, false),
  },
]

import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

export const formSchema = toTypedSchema(
  z.object({
    user_id: z.number({ message: 'هذا الحقل مطلوب' }),
    amount: z.number({ message: 'هذا الحقل مطلوب' }),
    notes: z.string().nullable().optional(),
  }),
)
