import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import DataTableColumnHeader from '@/Components/data-table/DataTableColumnHeader.vue'
import RegionsRowActions from './Partials/SellersRowActions.vue'
import { User } from '@/types'
import { formatMoney } from '@/lib/money'
import { Link } from '@inertiajs/vue3'

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
    cell: ({ row }) =>
      h(
        Link,
        {
          href: `${route('sellers.edit', row.getValue('id'))}`,
          class: 'hover:underline',
        },
        {
          default: () => row.getValue('name'),
        },
      ),
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