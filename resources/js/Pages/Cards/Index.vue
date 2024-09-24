<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { Card, Paginator } from '@/types'

import { Button } from '@/Components/ui/button'

import DataTable from '@/Components/data-table/DataTable.vue'
import DataTablePagination from '@/Components/data-table/DataTablePagination.vue'
import { columns } from './columns'

defineProps<{
  cards: Paginator<Card>
}>()
</script>

<template>
  <Head title="Cards" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          الكروت
          <span class="text-xs tracking-wide">({{ cards.meta.total }})</span>
        </h2>
        <div v-if="$page.props.auth.user.can.cards.create">
          <Link :href="route('cards.create')">
            <Button> إنشاء كرت </Button>
          </Link>
        </div>
      </div>
    </template>

    <div class="space-y-4">
      <DataTable :data="cards.data" :columns="columns" />
      <DataTablePagination :links="cards.links" :meta="cards.meta" />
    </div>
  </AuthenticatedLayout>
</template>
