import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import DataTableColumnHeader from '@/Components/data-table/DataTableColumnHeader.vue'
import { User } from '@/types'
import { Link } from '@inertiajs/vue3'
import { active, roles } from '@/types/enums'
import { formatMoney } from '@/lib/formatters'

export const columns: ColumnDef<User>[] = [
  {
    accessorKey: 'name',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'الاسم' }),
    cell: ({ row }) =>
      h(
        Link,
        {
          href: `${route('users.edit', row.original.id)}`,
          class: 'hover:underline',
        },
        {
          default: () => row.getValue('name'),
        },
      ),
    enableSorting: false,
  },
  {
    accessorKey: 'username',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'اسم المستخدم' }),
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
    accessorKey: 'role',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'الصلاحية' }),
    cell: ({ row }) => {
      const role = roles.find(
        (role: any) => role.value === row.getValue('role'),
      )

      if (!role) return null

      return h('div', { class: 'flex items-center gap-2' }, [
        role.icon && h(role.icon),
        h(
          'span',
          { class: ' text-muted-foreground' },
          role.value === 'partner' && row.original.network
            ? 'مدير مالي'
            : role.label,
        ),
      ])
    },
    enableSorting: false,
  },
  {
    accessorKey: 'balance',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'صافي الحساب' }),
    cell: ({ row }) =>
      row.original.balance ? `${formatMoney(row.original.balance)} شيكل` : '-',
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
]
