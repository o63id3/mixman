<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Filters, Paginator, Payment, User } from '@/types'
import { Head, Link } from '@inertiajs/vue3'
import Button from '@/Components/ui/button/Button.vue'
import { DataTable, DataTablePagination } from '@/Components/data-table/index'

import { columns } from './columns'
import Toolbar from './Partials/Toolbar.vue'

defineProps<{
  payments: Paginator<Payment>
  sellers: Array<User>
  filters: Filters
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

    <div class="space-y-4">
      <DataTable
        :data="payments.data"
        :columns="columns"
        :filters="filters"
        href="payments.index"
      >
        <template v-if="$page.props.auth.user.admin" #toolBar="{ table }">
          <Toolbar :table="table" :sellers="sellers" />
        </template>
      </DataTable>
      <DataTablePagination :links="payments.links" :meta="payments.meta" />
    </div>
  </AuthenticatedLayout>
</template>
