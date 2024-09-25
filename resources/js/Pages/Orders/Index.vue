<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { Filters, Order, Paginator, User } from '@/types'

import { Button } from '@/Components/ui/button'

import DataTable from '@/Components/data-table/DataTable.vue'
import DataTablePagination from '@/Components/data-table/DataTablePagination.vue'
import { columns } from './columns'
import Toolbar from './Partials/Toolbar.vue'

defineProps<{
  orders: Paginator<Order>
  sellers: Array<User>
  filters: Filters
  sorts: string
}>()
</script>

<template>
  <Head title="Orders" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          الطلبيات
          <span class="text-xs tracking-wide">({{ orders.meta.total }})</span>
        </h2>
        <div v-if="$page.props.auth.user.can.cards.create">
          <Link :href="route('orders.create')">
            <Button> إنشاء طلبية </Button>
          </Link>
        </div>
      </div>
    </template>

    <div class="space-y-4">
      <DataTable :data="orders.data" :columns="columns" :filters="filters">
        <template v-if="$page.props.auth.user.admin" #toolBar="{ table }">
          <Toolbar :table="table" :sellers="sellers" />
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
