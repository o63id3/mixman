import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import DataTableColumnHeader from '@/Components/data-table/DataTableColumnHeader.vue'
import { Network } from '@/types'
import { Link } from '@inertiajs/vue3'
import { active } from '@/types/enums'
import { formatMoney } from '@/lib/formatters'

export function columns(canUpdate: boolean): ColumnDef<Network>[] {
  const columns: ColumnDef<Network>[] = [
    {
      accessorKey: 'name',
      header: ({ column }) =>
        h(DataTableColumnHeader, { column, title: 'الاسم' }),
      cell: ({ row }) =>
        h(
          Link,
          {
            href: `${route(`networks.${canUpdate ? 'edit' : 'show'}`, row.original.id)}`,
            class: 'hover:underline',
          },
          {
            default: () => row.getValue('name'),
          },
        ),
      enableSorting: false,
    },
    {
      accessorKey: 'manager',
      header: ({ column }) =>
        h(DataTableColumnHeader, { column, title: 'المدير المالي' }),
      cell: ({ row }) => row.original.manager?.name,
      enableSorting: false,
    },
    {
      accessorKey: 'balance',
      header: ({ column }) =>
        h(DataTableColumnHeader, { column, title: 'صافي الحساب' }),
      cell: ({ row }) =>
        row.original.balance
          ? `${formatMoney(row.original.balance)} شيكل`
          : '-',
      enableSorting: false,
    },
    {
      accessorKey: 'internet_price_per_week',
      header: ({ column }) =>
        h(DataTableColumnHeader, { column, title: 'سعر الانترنت أسبوعيا' }),
      cell: ({ row }) =>
        `${formatMoney(row.original.internet_price_per_week)} شيكل`,
      enableSorting: false,
    },
    {
      accessorKey: 'active',
      header: ({ column }) =>
        h(DataTableColumnHeader, {
          column,
          title: 'الحالة',
        }),
      cell: ({ row }) => {
        const value = active.find(
          (a: any) => a.value === row.getValue('active'),
        )

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
  ]

  return columns
}
