<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { Order, Paginator } from '@/types'

import { Button } from '@/Components/ui/button'

import DataTable from '@/Components/data-table/DataTable.vue'
import DataTablePagination from '@/Components/data-table/DataTablePagination.vue'
import { columns } from './columns'

defineProps<{
  orders: Paginator<Order>
}>()
</script>

<template>
  <Head title="Orders" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          الطلبيات
        </h2>
        <div v-if="$page.props.auth.user.can.cards.create">
          <Link :href="route('orders.create')">
            <Button> إنشاء طلبية </Button>
          </Link>
        </div>
      </div>
    </template>

    <div class="space-y-4">
      <DataTable :data="orders.data" :columns="columns" />
      <DataTablePagination :links="orders.links" :meta="orders.meta" />
    </div>
  </AuthenticatedLayout>
</template>
