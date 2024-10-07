<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { Filters, Network, Order, Paginator, User } from '@/types'

import { Button } from '@/Components/ui/button'

import DataTable from '@/Components/data-table/DataTable.vue'
import DataTablePagination from '@/Components/data-table/DataTablePagination.vue'
import { columns } from './columns'
import Toolbar from './Partials/Toolbar.vue'

defineProps<{
  orders: Paginator<Order>
  users: Array<User>
  managers: Array<User>
  networks: Array<Network>
  filters: Filters
  sorts: string
  can: {
    create: boolean
  }
}>()
</script>

<template>
  <Head title="Orders" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          الطلبات
          <span class="text-xs font-normal tracking-wide">
            ({{ orders.meta.total }})
          </span>
        </h2>
        <div v-if="can.create">
          <Link :href="route('orders.create')">
            <Button> إنشاء طلب </Button>
          </Link>
        </div>
      </div>
    </template>

    <div class="space-y-4">
      <DataTable
        :data="orders.data"
        :columns="columns"
        :initial-filters="filters"
        :initial-sorts="sorts"
      >
        <template
          v-if="$page.props.auth.user.role === 'ahmed'"
          #toolBar="{ table }"
        >
          <Toolbar
            :table="table"
            :users="users"
            :managers="managers"
            :networks="networks"
          />
        </template>
      </DataTable>
      <DataTablePagination
        :links="orders.links"
        :meta="orders.meta"
        :filters="filters"
        :sorts="sorts"
      />
    </div>
  </AuthenticatedLayout>
</template>
