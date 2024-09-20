import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import DataTableColumnHeader from '@/Components/data-table/DataTableColumnHeader.vue'
import RegionsRowActions from './Partials/SellersRowActions.vue'
import { User } from '@/types'
import { formatMoney } from '@/lib/money'

export const columns: ColumnDef<User>[] = [
  {
    accessorKey: 'id',
    header: ({ column }) => h(DataTableColumnHeader, { column, title: '#' }),
    enableSorting: false,
    enableHiding: false,
  },
  {
    accessorKey: 'name',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'الاسم' }),
  },
  {
    accessorKey: 'region.name',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'المنطقة',
      }),
  },
  {
    accessorKey: 'balance',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'صافي الحساب',
      }),
    cell: ({ row }) => `${formatMoney(row.getValue('balance'))} شيكل`,
  },

  {
    id: 'actions',
    cell: ({ row }) => h(RegionsRowActions, { row }),
  },
]
