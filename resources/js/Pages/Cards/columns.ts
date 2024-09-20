import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import DataTableColumnHeader from '@/Components/data-table/DataTableColumnHeader.vue'
import CardsRowActions from './Partials/CardsRowActions.vue'
import { Card } from '@/types'
import { active } from '@/types/enums'

export const columns: ColumnDef<Card>[] = [
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
    accessorKey: 'price_for_consumer',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'السعر للمستهلك',
      }),
    cell: ({ row }) => `${row.getValue('price_for_consumer')} شيكل`,
  },
  {
    accessorKey: 'price_for_seller',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'السعر للبائع',
      }),
    cell: ({ row }) => `${row.getValue('price_for_seller')} شيكل`,
  },
  {
    accessorKey: 'active',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'الحالة',
      }),
    cell: ({ row }) => {
      const value = active.find((a: any) => a.value === row.getValue('active'))

      if (!value) return null

      return h('div', { class: 'flex items-center' }, [
        value.icon &&
          h(value.icon, {
            class: 'mr-2 rtl:ml-2',
          }),
        h('span', { class: ' text-muted-foreground' }, value.label),
      ])
    },
  },
  {
    id: 'actions',
    cell: ({ row }) => h(CardsRowActions, { row }),
  },
]
