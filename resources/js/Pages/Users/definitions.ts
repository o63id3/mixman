import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import { DataTableColumnHeader } from '@/Components/data-table'
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
    accessorKey: 'telegram',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'حساب التلغرام' }),
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
    cell: ({ row }) => formatMoney(row.original.balance),
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

import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

export const formSchema = toTypedSchema(
  z.object({
    name: z
      .string({ message: 'هذا الحقل مطلوب' })
      .min(2, { message: 'الاسم يجيب ان يكون حرفين على الاقل' }),
    username: z
      .string({ message: 'هذا الحقل مطلوب' })
      .min(2, { message: 'اسم المستخدم يجيب ان يكون حرفين على الاقل' }),
    telegram: z.string().nullable().optional(),
    password: z
      .string({ message: 'هذا الحقل مطلوب' })
      .min(4, { message: 'كلمة المرور يجب ان تكون 4 احرف على الاقل' }),
    role: z.string({ message: 'هذا الحقل مطلوب' }),
    network_id: z.string({ message: 'هذا الحقل مطلوب' }).optional(),
    percentage: z.number({ message: 'هذا الحقل مطلوب' }).optional(),
    contact_info: z.string().optional().nullable(),
  }),
)
