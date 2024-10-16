<script setup lang="ts">
import { Network, Transaction, User } from '@/types'

import {
  DataTableFacetedFilter,
  DataTableDateRangeFilter,
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
const table = inject<import('@tanstack/table-core').Table<Transaction>>('table')
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
    title="المدير"
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
  <DataTableDateRangeFilter
    v-if="table && table.getColumn('created_at')"
    :column="table.getColumn('created_at')"
  />
</template>
