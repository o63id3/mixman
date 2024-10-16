<script setup lang="ts">
import { Network, Order, User } from '@/types'

import { orderStatues } from '@/types/enums'
import {
  DataTableDateRangeFilter,
  DataTableFacetedFilter,
} from '@/Components/data-table'
import { usePage } from '@inertiajs/vue3'
import { inject } from 'vue'

interface DataTableToolbarProps {
  users: Array<User>
  managers: Array<User>
  networks: Array<Network>
}

const user = usePage().props.auth.user

defineProps<DataTableToolbarProps>()
const table = inject<import('@tanstack/table-core').Table<Order>>('table')
</script>

<template>
  <DataTableFacetedFilter
    v-if="table && table.getColumn('user')"
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
    v-if="table && table.getColumn('manager')"
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
    v-if="table && table.getColumn('network') && user.role === 'ahmed'"
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
    v-if="table && table.getColumn('status')"
    :column="table.getColumn('status')"
    title="الحالة"
    :options="orderStatues"
  />
  <DataTableDateRangeFilter
    v-if="table && table.getColumn('updated_at')"
    :column="table.getColumn('updated_at')"
  />
</template>
