<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { Link } from '@inertiajs/vue3'

import { Button } from '@/Components/ui/button'

import {
  DataTable,
  DataTablePagination,
  DataTableToolbar,
} from '@/Components/data-table'
import { columns } from './definitions'

import { Network, Paginator } from '@/types'

defineProps<{
  networks: Paginator<Network>
  can: {
    create: boolean
    update: boolean
  }
}>()
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          الشبكات
          <span class="text-xs font-normal tracking-wide">
            ({{ networks.meta.total }})
          </span>
        </h2>
        <div v-if="can.create">
          <Link :href="route('networks.create')">
            <Button> إنشاء </Button>
          </Link>
        </div>
      </div>
    </template>

    <div class="space-y-4">
      <DataTable :data="networks.data" :columns="columns(can.update)">
        <template
          v-if="$page.props.auth.user.role === 'ahmed'"
          #toolBar="{ table }"
        >
          <DataTableToolbar :table="table" table-id="networks" />
        </template>
      </DataTable>
      <DataTablePagination :links="networks.links" :meta="networks.meta" />
    </div>
  </AuthenticatedLayout>
</template>
