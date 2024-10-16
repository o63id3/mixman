<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { Link } from '@inertiajs/vue3'

import { Button } from '@/Components/ui/button'
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbPage,
  BreadcrumbSeparator,
} from '@/Components/ui/breadcrumb'

import {
  DataTable,
  DataTableTable,
  DataTableToolbar,
} from '@/Components/data-table'
import { columns } from './definitions'
import Toolbar from './Partials/Toolbar.vue'

import { Filters, Network, Paginator, User } from '@/types'
import { PlusCircle } from 'lucide-vue-next'

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
    <template #secondaryHeader>
      <div class="flex flex-1 items-center justify-between">
        <Breadcrumb>
          <BreadcrumbList>
            <BreadcrumbItem>
              <BreadcrumbLink :href="route('dashboard')">
                الرئيسة
              </BreadcrumbLink>
            </BreadcrumbItem>
            <BreadcrumbSeparator />
            <BreadcrumbItem>
              <BreadcrumbPage>
                المستخدمين
                <span class="text-xs font-normal tracking-wide">
                  ({{ users.meta.total }})
                </span>
              </BreadcrumbPage>
            </BreadcrumbItem>
          </BreadcrumbList>
        </Breadcrumb>

        <Link v-if="can.create" :href="route('users.create')">
          <Button size="sm" class="h-7 gap-1">
            <PlusCircle class="h-3.5 w-3.5" />
            <span class="sr-only sm:not-sr-only sm:whitespace-nowrap">
              إنشاء
            </span>
          </Button>
        </Link>
      </div>
    </template>

    <DataTable
      :columns="columns"
      :data="users"
      table-id="users"
      :initial-filters="filters"
      :initial-sorts="sorts"
    >
      <DataTableToolbar>
        <Toolbar :networks="networks" />
      </DataTableToolbar>
      <DataTableTable />
    </DataTable>
  </AuthenticatedLayout>
</template>
