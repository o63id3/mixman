<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { Paginator, User } from '@/types'

import { Button } from '@/Components/ui/button'

import DataTable from '@/Components/data-table/DataTable.vue'
import DataTablePagination from '@/Components/data-table/DataTablePagination.vue'
import { columns } from './columns'
import DataTableToolbar from '@/Components/data-table/DataTableToolbar.vue'

defineProps<{
  admins: Paginator<User>
  can: { create: boolean }
}>()
</script>

<template>
  <Head title="Admins" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          المستخدمين
          <span class="text-xs tracking-wide">({{ admins.meta.total }})</span>
        </h2>
        <div v-if="can.create">
          <Link :href="route('admins.create')">
            <Button> إنشاء مستخدم </Button>
          </Link>
        </div>
      </div>
    </template>

    <div class="space-y-4">
      <DataTable :data="admins.data" :columns="columns">
        <template v-if="$page.props.auth.user.admin" #toolBar="{ table }">
          <DataTableToolbar :table="table" />
        </template>
      </DataTable>
      <DataTablePagination :links="admins.links" :meta="admins.meta" />
    </div>
  </AuthenticatedLayout>
</template>
