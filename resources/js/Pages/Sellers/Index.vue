<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { Paginator, User } from '@/types'

import { Button } from '@/Components/ui/button'

import DataTable from '@/Components/data-table/DataTable.vue'
import DataTablePagination from '@/Components/data-table/DataTablePagination.vue'
import { columns } from './columns'

defineProps<{
  sellers: Paginator<User>
}>()
</script>

<template>
  <Head title="Sellers" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          الباعة
        </h2>
        <div v-if="$page.props.auth.user.can.cards.create">
          <Link :href="route('sellers.create')">
            <Button> إنشاء بائع </Button>
          </Link>
        </div>
      </div>
    </template>

    <div class="py-8">
      <div class="mx-auto max-w-7xl lg:px-2">
        <div class="space-y-4">
          <div class="overflow-hidden bg-white shadow-sm lg:rounded-md">
            <DataTable :data="sellers.data" :columns="columns" />
          </div>
          <DataTablePagination :links="sellers.links" :meta="sellers.meta" />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
