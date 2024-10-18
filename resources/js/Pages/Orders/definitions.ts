import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import { DataTableColumnHeader } from '@/Components/data-table'
import { Order } from '@/types'
import { orderStatues } from '@/types/enums'
import { Link } from '@inertiajs/vue3'
import { formatDate, formatMoney } from '@/lib/formatters'

export const columns: ColumnDef<Order>[] = [
  {
    accessorKey: 'user',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'المستفيد' }),
    cell: ({ row }) =>
      h(
        Link,
        {
          href: `${route(`orders.${row.original.can.update ? 'edit' : 'show'}`, row.original.id)}`,
          class: 'hover:underline',
        },
        {
          default: () => row.original.user.name,
        },
      ),
    enableSorting: false,
  },
  {
    accessorKey: 'manager',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'مدير الطلب' }),
    cell: ({ row }) => row.original.manager.name,
    enableSorting: false,
  },
  {
    accessorKey: 'network',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'الشبكة' }),
    cell: ({ row }) => row.original.network.name,
    enableSorting: false,
  },
  {
    accessorKey: 'status',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'الحالة',
      }),
    cell: ({ row }) => {
      const status = orderStatues.find(
        (status: any) => status.value === row.getValue('status'),
      )

      if (!status) return null

      return h('div', { class: 'flex items-center gap-2' }, [
        status.icon && h(status.icon),
        h('span', { class: ' text-muted-foreground' }, status.label),
      ])
    },
  },
  {
    accessorKey: 'total_price_for_seller',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'اجمالي السعر',
      }),
    cell: ({ row }) => formatMoney(row.getValue('total_price_for_seller')),
  },
  {
    accessorKey: 'total_price_for_consumer',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'اجمالي السعر للمستهلك',
      }),
    cell: ({ row }) => formatMoney(row.getValue('total_price_for_consumer')),
    enableSorting: false,
  },
  {
    accessorKey: 'updated_at',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'التاريخ',
      }),
    cell: ({ row }) => formatDate(row.original.updated_at_date, false),
    enableSorting: false,
  },
]

import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

export const formSchema = toTypedSchema(
  z.object({
    user_id: z.number({ message: 'هذا الحقل مطلوب' }),
    status: z.string({ message: 'هذا الحقل مطلوب' }),
    notes: z.string().nullable().optional(),
    cards: z
      .array(
        z.object({
          card_id: z.string({ message: 'حقل الفئة مطلوب' }),
          number_of_packages: z.number({ message: 'حقل الفئة مطلوب' }).min(1),
          number_of_cards_per_package: z.number(),
        }),
      )
      .optional(),
  }),
)
