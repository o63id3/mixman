<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Link } from '@inertiajs/vue3'
import { Card, Paginator } from '@/types'

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
  DataTablePagination,
  DataTableToolbar,
} from '@/Components/data-table'
import { columns } from './definitions'
import { PlusCircle } from 'lucide-vue-next'

defineProps<{
  cards: Paginator<Card>
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
                الكروت
                <span class="text-xs font-normal tracking-wide">
                  ({{ cards.meta.total }})
                </span>
              </BreadcrumbPage>
            </BreadcrumbItem>
          </BreadcrumbList>
        </Breadcrumb>

        <Link :href="route('cards.create')">
          <Button size="sm" class="h-7">
            <div class="flex items-center gap-1">
              <PlusCircle class="h-3.5 w-3.5" />
              <span class="sr-only sm:not-sr-only sm:whitespace-nowrap">
                إنشاء
              </span>
            </div>
          </Button>
        </Link>
      </div>
    </template>

    <div class="space-y-4">
      <DataTable :data="cards.data" :columns="columns">
        <template
          v-if="$page.props.auth.user.role === 'ahmed'"
          #toolBar="{ table }"
        >
          <DataTableToolbar :table="table" table-id="cards" />
        </template>
      </DataTable>
      <DataTablePagination :links="cards.links" :meta="cards.meta" />
    </div>
  </AuthenticatedLayout>
</template>
