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

import { Filters, Network, Paginator, Payment, User } from '@/types'
import { PlusCircle } from 'lucide-vue-next'

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
                الدفعات
                <span class="text-xs font-normal tracking-wide">
                  ({{ payments.meta.total }})
                </span>
              </BreadcrumbPage>
            </BreadcrumbItem>
          </BreadcrumbList>
        </Breadcrumb>

        <Link :href="route('payments.create')">
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
