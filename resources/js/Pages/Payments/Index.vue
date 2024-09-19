<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Paginator, Payment } from '@/types'
import { Head, Link } from '@inertiajs/vue3'
import Button from '@/Components/ui/button/Button.vue'
import { DataTable, DataTablePagination } from '@/Components/data-table/index'

import { columns } from './columns'

defineProps<{
  payments: Paginator<Payment>
}>()
</script>

<template>
  <Head title="Payments" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          المدفوعات
        </h2>
        <div v-if="$page.props.auth.user.can.payments.create">
          <Link :href="route('payments.create')">
            <Button> إنشاء </Button>
          </Link>
        </div>
      </div>
    </template>

    <div class="py-8">
      <div class="mx-auto max-w-7xl lg:px-2">
        <div class="space-y-4">
          <div class="overflow-hidden bg-white shadow-sm lg:rounded-md">
            <DataTable :data="payments.data" :columns="columns" />
          </div>
          <DataTablePagination :links="payments.links" :meta="payments.meta" />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
