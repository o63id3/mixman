<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Filters, Network, Paginator, Payment, User } from '@/types'
import { Head, Link } from '@inertiajs/vue3'
import Button from '@/Components/ui/button/Button.vue'
import { DataTable, DataTablePagination } from '@/Components/data-table/index'

import { columns } from './columns'
import Toolbar from './Partials/Toolbar.vue'

defineProps<{
  payments: Paginator<Payment>
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
  <Head title="Payments" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          المدفوعات
          <span class="text-xs font-normal tracking-wide">
            ({{ payments.meta.total }})
          </span>
        </h2>
        <div v-if="can.create">
          <Link :href="route('payments.create')">
            <Button> إضافة </Button>
          </Link>
        </div>
      </div>
    </template>

    <div class="space-y-4">
      <DataTable
        :data="payments.data"
        :columns="columns"
        :initial-filters="filters"
        :initial-sorts="sorts"
      >
        <template
          v-if="$page.props.auth.user.role !== 'seller'"
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
        :links="payments.links"
        :meta="payments.meta"
        :filters="filters"
        :sorts="sorts"
      />
    </div>
  </AuthenticatedLayout>
</template>
