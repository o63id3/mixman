<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Order, Paginator } from '@/types'
import DataTable from './Table/DataTable.vue'
import { columns } from './Table/columns'
import { Head, Link } from '@inertiajs/vue3'
import Button from '@/Components/ui/button/Button.vue'

defineProps<{
  orders: Paginator<Order>
  filters: Pick<Order, 'seller' | 'status'>
  statuses: Array<string>
}>()
</script>

<template>
  <Head title="Orders" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          الطلبات
        </h2>
        <div v-if="$page.props.auth.user.can.orders.create">
          <Link :href="route('orders.create')">
            <Button> طلبية جديدة </Button>
          </Link>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <DataTable
              :statuses="statuses"
              :columns="columns"
              :data="orders.data"
              :meta="orders.meta"
              :filters="filters"
            />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
