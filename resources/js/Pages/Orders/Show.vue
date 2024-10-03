<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

import { Data, Order } from '@/types'
import DataTable from '@/Components/data-table/DataTable.vue'
import { columns, summaryFields } from './Partials/columns'

defineProps<{
  order: Data<Order>
}>()
</script>

<template>
  <Head title="Orders" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        طلب رقم: {{ order.data.id }}
      </h2>
    </template>

    <p class="px-4 text-sm font-medium tracking-wide"># الكروت</p>
    <DataTable
      :data="order.data.items"
      :columns="columns(false)"
      :summaryFields="order.data.items.length ? summaryFields : undefined"
    />
  </AuthenticatedLayout>
</template>
