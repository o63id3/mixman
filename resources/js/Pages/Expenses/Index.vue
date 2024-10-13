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

import { DataTable, DataTablePagination } from '@/Components/data-table/index'
import { columns } from './definitions'
import Toolbar from './Partials/Toolbar.vue'

import { Expense, Network, Paginator, User } from '@/types'
import { PlusCircle } from 'lucide-vue-next'

defineProps<{
  expenses: Paginator<Expense>
  managers: Array<User>
  networks: Array<Network>
  can: {
    create: boolean
  }
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
                المصروفات
                <span class="text-xs font-normal tracking-wide">
                  ({{ expenses.meta.total }})
                </span>
              </BreadcrumbPage>
            </BreadcrumbItem>
          </BreadcrumbList>
        </Breadcrumb>

        <Link :href="route('expenses.create')">
          <Button size="sm" class="h-7">
            <div class="flex items-center gap-1">
              <PlusCircle class="h-3.5 w-3.5" />
              <span class="sr-only sm:not-sr-only sm:whitespace-nowrap">
                إضافة
              </span>
            </div>
          </Button>
        </Link>
      </div>
    </template>

    <div class="space-y-4">
      <DataTable :data="expenses.data" :columns="columns">
        <template
          v-if="$page.props.auth.user.role !== 'seller'"
          #toolBar="{ table }"
        >
          <Toolbar :table="table" :managers="managers" :networks="networks" />
        </template>
      </DataTable>
      <DataTablePagination :links="expenses.links" :meta="expenses.meta" />
    </div>
  </AuthenticatedLayout>
</template>
