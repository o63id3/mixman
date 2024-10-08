import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import DataTableColumnHeader from '@/Components/data-table/DataTableColumnHeader.vue'
import { Transaction } from '@/types'
import { orderStatues, transactionTypes } from '@/types/enums'
import { formatDate, formatMoney } from '@/lib/formatters'

export const columns: ColumnDef<Transaction>[] = [
  {
    accessorKey: 'user',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'المستفيد' }),
    cell: ({ row }) => row.original.user?.name,
    enableSorting: false,
  },
  {
    id: 'manager',
    accessorKey: 'manager.name',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'المدير' }),
    enableSorting: false,
  },
  {
    id: 'network',
    accessorKey: 'network.name',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'الشبكة' }),
    enableSorting: false,
  },
  {
    accessorKey: 'type',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'نوع الحركة',
      }),
    cell: ({ row }) => {
      const type = transactionTypes.find(
        (type: any) => type.value === row.getValue('type'),
      )

      if (!type) return null

      if (type.value === 'order') {
        const status = orderStatues.find(
          (status: any) => status.value === row.original.status,
        )
        if (!status) return null

        return h('div', { class: 'flex items-center gap-2' }, [
          status.icon && h(status.icon),
          h('span', { class: ' text-muted-foreground' }, type.label),
        ])
      }

      return h('div', { class: 'flex items-center gap-2' }, [
        type.icon && h(type.icon),
        h('span', { class: ' text-muted-foreground' }, type.label),
      ])
    },
  },
  {
    accessorKey: 'amount',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'المبلغ',
      }),
    cell: ({ row }) =>
      row.getValue('amount')
        ? `${formatMoney(row.getValue('amount'))} شيكل`
        : '-',
  },
  {
    accessorKey: 'description',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'الوصف',
      }),
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
