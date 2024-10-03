<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { Network, Paginator } from '@/types'

import { Button } from '@/Components/ui/button'

import DataTable from '@/Components/data-table/DataTable.vue'
import DataTablePagination from '@/Components/data-table/DataTablePagination.vue'
import { columns } from './columns'
import DataTableToolbar from '@/Components/data-table/DataTableToolbar.vue'

defineProps<{
  networks: Paginator<Network>
  can: {
    create: boolean
    update: boolean
  }
}>()
</script>

<template>
  <Head title="Networks" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          الشبكات
          <span class="text-xs tracking-wide">({{ networks.meta.total }})</span>
        </h2>
        <div v-if="can.create">
          <Link :href="route('networks.create')">
            <Button> إنشاء شبكة </Button>
          </Link>
        </div>
      </div>
    </template>

    <div class="space-y-4">
      <DataTable :data="networks.data" :columns="columns(can.update)">
        <template v-if="$page.props.auth.user.admin" #toolBar="{ table }">
          <DataTableToolbar :table="table" />
        </template>
      </DataTable>
      <DataTablePagination :links="networks.links" :meta="networks.meta" />
    </div>
  </AuthenticatedLayout>
</template>
