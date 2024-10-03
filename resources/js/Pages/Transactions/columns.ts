import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import DataTableColumnHeader from '@/Components/data-table/DataTableColumnHeader.vue'
import { Transaction, User } from '@/types'
import { orderStatues, transactionTypes } from '@/types/enums'
import { formatMoney } from '@/lib/money'
import { Link, usePage } from '@inertiajs/vue3'

export const columns: ColumnDef<Transaction>[] = [
  {
    accessorKey: 'user.name',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'المستفيد' }),
    enableSorting: false,
  },
  {
    accessorKey: 'manager.name',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'المدير' }),
    enableSorting: false,
  },
  {
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

      return h('div', { class: 'flex items-center gap-2' }, [
        type.icon && h(type.icon),
        h('span', { class: ' text-muted-foreground' }, type.label),
      ])
    },
  },
  //   {
  //     accessorKey: 'status',
  //     header: ({ column }) =>
  //       h(DataTableColumnHeader, {
  //         column,
  //         title: 'الحالة',
  //       }),
  //     cell: ({ row }) => {
  //       const status = orderStatues.find(
  //         (status: any) => status.value === row.getValue('status'),
  //       )

  //       if (!status) return null

  //       return h('div', { class: 'flex items-center gap-2' }, [
  //         status.icon && h(status.icon),
  //         h('span', { class: ' text-muted-foreground' }, status.label),
  //       ])
  //     },
  //   },
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
  },
]
