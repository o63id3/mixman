<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

import { Order } from '@/types'
import DataTable from '@/Components/data-table/DataTable.vue'
import { columns, summaryFields } from './Partials/columns'

defineProps<{
  order: Order
}>()
</script>

<template>
  <Head title="Orders" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        طلب رقم: {{ order.id }}
      </h2>
    </template>

    <p class="px-4 text-sm font-medium tracking-wide"># الكروت</p>
    <DataTable
      :data="order.items"
      :columns="columns(false)"
      :summaryFields="order.items.length ? summaryFields : undefined"
    />
  </AuthenticatedLayout>
</template>
