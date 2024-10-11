<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { Link } from '@inertiajs/vue3'

import { Button } from '@/Components/ui/button'

import { DataTable, DataTablePagination } from '@/Components/data-table'
import { columns } from './definitions'
import Toolbar from './Partials/Toolbar.vue'

import { Filters, Network, Paginator, User } from '@/types'

defineProps<{
  users: Paginator<User>
  networks: Array<Network>
  filters: Filters
  sorts: string
  can: { create: boolean }
}>()
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          المستخدمين
          <span class="text-xs font-normal tracking-wide">
            ({{ users.meta.total }})
          </span>
        </h2>
        <div v-if="can.create">
          <Link :href="route('users.create')">
            <Button> إنشاء </Button>
          </Link>
        </div>
      </div>
    </template>

    <div class="space-y-4">
      <DataTable
        :data="users.data"
        :columns="columns"
        :initial-filters="filters"
        :initial-sorts="sorts"
      >
        <template
          v-if="$page.props.auth.user.role === 'ahmed'"
          #toolBar="{ table }"
        >
          <Toolbar :table="table" :networks="networks" />
        </template>
      </DataTable>
      <DataTablePagination
        :links="users.links"
        :meta="users.meta"
        :initial-filters="filters"
        :initial-sorts="sorts"
      />
    </div>
  </AuthenticatedLayout>
</template>
