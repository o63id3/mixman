<script setup lang="ts">
import { usePage } from '@inertiajs/vue3'
import { inject } from 'vue'

import {
  DataTableDateRangeFilter,
  DataTableFacetedFilter,
} from '@/Components/data-table'

import { orderStatues } from '@/types/enums'
import { Network, Order, User } from '@/types'
import type { Table } from '@tanstack/vue-table'

interface DataTableToolbarProps {
  users: Array<User>
  managers: Array<User>
  networks: Array<Network>
}

const user = usePage().props.auth.user

defineProps<DataTableToolbarProps>()
const table = inject('table') as Table<Order>
</script>

<template>
  <DataTableFacetedFilter
    v-if="table.getColumn('user')"
    :column="table.getColumn('user')"
    title="المستفيد"
    :options="
      users.map((user) => ({
        label: user.name,
        value: String(user.id),
      }))
    "
  />
  <DataTableFacetedFilter
    v-if="table.getColumn('manager')"
    :column="table.getColumn('manager')"
    title="مدير الطلب"
    :options="
      managers.map((user) => ({
        label: user.name,
        value: String(user.id),
      }))
    "
  />
  <DataTableFacetedFilter
    v-if="table.getColumn('network') && user.role === 'ahmed'"
    :column="table.getColumn('network')"
    title="الشبكة"
    :options="
      networks.map((network) => ({
        label: network.name,
        value: String(network.id),
      }))
    "
  />
  <DataTableFacetedFilter
    v-if="table.getColumn('status')"
    :column="table.getColumn('status')"
    title="الحالة"
    :options="orderStatues"
  />
  <DataTableDateRangeFilter
    v-if="table.getColumn('updated_at')"
    :column="table.getColumn('updated_at')"
  />
</template>
