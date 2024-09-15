<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Paginator, Payment } from '@/types'
import { columns } from './Table/columns'
import { Head, Link } from '@inertiajs/vue3'
import Button from '@/Components/ui/button/Button.vue'
import { DataTable, DataTablePagination } from '@/Components/data-table/index'

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

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <DataTable :columns="columns" :data="payments.data">
              <template #pagination>
                <DataTablePagination
                  :meta="payments.meta"
                  href="payments.index"
                />
              </template>
            </DataTable>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
