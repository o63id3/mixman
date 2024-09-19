import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import DataTableColumnHeader from '@/Components/data-table/DataTableColumnHeader.vue'
import RegionsRowActions from './Partials/SellersRowActions.vue'
import { User } from '@/types'

export const columns: ColumnDef<User>[] = [
  {
    accessorKey: 'id',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: '#', class: 'text-right' }),
    cell: ({ row }) =>
      h('div', { class: 'text-nowrap text-right' }, row.getValue('id')),
    enableSorting: false,
    enableHiding: false,
  },
  {
    accessorKey: 'name',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'الاسم', class: 'text-right' }),
    cell: ({ row }) =>
      h('div', { class: 'text-nowrap text-right' }, row.getValue('name')),
  },
  {
    accessorKey: 'region',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'المنطقة',
        class: 'text-right',
      }),
    cell: ({ row }) =>
      h('div', { class: 'text-nowrap text-right' }, row.original.region.name),
  },
  {
    accessorKey: 'balance',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'صافي الحساب',
        class: 'text-right',
      }),
    cell: ({ row }) => {
      const balance = Number.parseFloat(row.getValue('balance'))
      const formatted = new Intl.NumberFormat('en-US').format(balance)

      return h('div', { class: 'text-right' }, `${formatted} شيكل`)
    },
  },

  {
    id: 'actions',
    cell: ({ row }) => h(RegionsRowActions, { row }),
  },
]
